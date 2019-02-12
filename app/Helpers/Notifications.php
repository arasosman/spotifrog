<?php
/**
 * Created by PhpStorm.
 * User: abdulkadir.posul
 * Date: 12/19/2017
 * Time: 5:56 AM
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;

class Notifications
{
    public static function informSystemForFirstLogin(){

        $data = array();
        $data["subject"] = trans("login.first_panel_authentication");
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["introLines"][] = trans("login.first_panel_authentication_body",array("email"=>Auth::user()->email, "name"=>Auth::user()->name));
        $data["introLines"][] = trans("login.login_date",array("login_date"=>date("d/m/Y H:i:s")));

        $mail_result = self::sendMail($data);
        self::createNotificationRecord("first_login",Auth::user()->id,$data["introLines"][0] );

        if($mail_result == "ERROR")
            return $mail_result;
        else{
            DB::table('users')
                ->where('id',Auth::user()->id)
                ->update([
                    'first_login'=>0
                ]);
            return "SUCCESS";
        }
    }

    public static function informSystemForFirstAuthentication($email){

        $the_user = DB::table('users')
            ->where('email',$email)
            ->first();


        $data = array();
        $data["subject"] = trans("login.first_installation_authentication");
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["introLines"][] = trans("login.first_installation_authentication_body",array("email"=>$email, "name"=>$the_user->name));
        $data["introLines"][] = trans("login.authentication_date",array("authentication_date"=>date("d/m/Y H:i:s")));

        $mail_result = self::sendMail($data);
        self::createNotificationRecord("first_authentication",$the_user->id, $data["introLines"][0]);

        if($mail_result == "ERROR")
            return $mail_result;
        else{
            return "SUCCESS";
        }
    }

    public static function informSystemTooManyAttempts($request){
        $data = array();
        $data["subject"] = trans("login.too_many_mail_subject");
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["introLines"][] = trans("login.too_many_mail_body",array("email"=>$request->input("email"), "attempt_count"=>session("custom_login_counter")));


        $mail_result = self::sendMail($data);
        self::createNotificationRecord("too_many_login_attempts",0,$data["introLines"][0]);

        if($mail_result == "ERROR")
            return $mail_result;
        else{
            return "SUCCESS";
        }
    }

    public static function informSystemResetPassword($user){

        //firstly, record an event log
        Helper::fire_event("reset_password",$user,"users",$user->id);
        $description = trans("login.user_has_reset_password",array("name"=>$user->name, "email"=>$user->email));
        self::createNotificationRecord("reset_password",$user->id,$description);
    }

    public static function informSystemLicenceDistributionToSuperDistributor($user_id, $the_packet_info, $super_distributor_id,$count){



        //fire an event
        Helper::fire_event("distribute_licence_to_distributor",Auth::user(),"licence_packets", $the_packet_info->id, $super_distributor_id);

        $the_sup_dist = DB::table('super_distributors')
            ->where('id',$super_distributor_id)
            ->first();

        $the_sup_user = DB::table('users')
            ->where('org_id',$super_distributor_id)
            ->where('status',1)
            ->where('user_type',6)
            ->first();

        App::setLocale($the_sup_user->language);

        $the_user = Helper::getUserInfo($user_id);


        $data = array();
        $data["subject"] = trans("notifications.subject_licence_distribution");
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["introLines"][] = trans("notifications.body_licence_distribution_super_distributor",
            [
                'user'=>$the_user["user"]->name,
                'assigned_to' => $the_sup_dist->name,
                'count' => $count
            ]);

        $currency = "TL";
        if($the_packet_info->price_currency == 2)
            $currency = "USD";
        else if($the_packet_info->price_currency == 3)
            $currency = "EUR";

        $duration = trans("licence_management.monthly");
        if($the_packet_info->duration == 0)
            $duration = trans("licence_management.yearly");

        $data["introLines"][] = trans("notifications.packet_name",["packet_name"=>$the_packet_info->name]);
        $data["introLines"][] = trans("notifications.packet_size",["packet_size"=>$the_packet_info->size, "size_type"=>$the_packet_info->size_type]);
        //$data["introLines"][] = trans("notifications.packet_price",["packet_price"=>$the_packet_info->price, "currency"=>$currency]);
        $data["introLines"][] = trans("notifications.packet_duration",["duration"=>$duration]);
        $data["introLines"][] = trans("notifications.user_count",["user_count"=>$the_packet_info->user_count]);



        $mail_result = self::sendMail($data);
        //$mail_result2 = self::sendMail($data,$the_sup_dist->email);
        $mail_result2 = self::sendMail($data,$the_sup_user->email, $the_sup_user->language);
        self::createNotificationRecord("distribute_licence",$user_id,$data["introLines"][0]);

        if($mail_result == "ERROR" || $mail_result2 =="ERROR")
            return $mail_result;
        else{
            return "SUCCESS";
        }
    }

    public static function informSystemLicenceDistributionToDistributor($user_id, $the_packet_info, $distributor_id,$count){

        //fire an event
        Helper::fire_event("distribute_licence_to_reseller",Auth::user(),"licence_packets", $the_packet_info->id, $distributor_id);

        $the_dist = DB::table('distributors')
            ->where('id',$distributor_id)
            ->first();

        $the_dist_user = DB::table('users')
            ->where('org_id',$distributor_id)
            ->where('user_type',3)
            ->where('status',1)
            ->first();

        App::setLocale($the_dist_user->language);

        $the_user = Helper::getUserInfo($user_id);


        $data = array();
        $data["subject"] = trans("notifications.subject_licence_distribution");
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["introLines"][] = trans("notifications.body_licence_distribution_distributor",
            [
                'user'=>$the_user["user_org"]->name." -> ".$the_user["user"]->name." ( ".$the_user["user"]->email." ) ",
                'assigned_to' => $the_dist->name,
                'count' => $count
            ]);

        $currency = "TL";
        if($the_packet_info->price_currency == 2)
            $currency = "USD";
        else if($the_packet_info->price_currency == 3)
            $currency = "EUR";

        $duration = trans("licence_management.monthly");
        if($the_packet_info->duration == 0)
            $duration = trans("licence_management.yearly");

        $data["introLines"][] = trans("notifications.packet_name",["packet_name"=>$the_packet_info->name]);
        $data["introLines"][] = trans("notifications.packet_size",["packet_size"=>$the_packet_info->size, "size_type"=>$the_packet_info->size_type]);
        //$data["introLines"][] = trans("notifications.packet_price",["packet_price"=>$the_packet_info->price, "currency"=>$currency]);
        $data["introLines"][] = trans("notifications.packet_duration",["duration"=>$duration]);
        $data["introLines"][] = trans("notifications.user_count",["user_count"=>$the_packet_info->user_count]);



        $mail_result = self::sendMail($data);
        //$mail_result2 = self::sendMail($data, $the_dist->email);
        $mail_result2 = self::sendMail($data, $the_dist_user->email, $the_dist_user->language);
        self::createNotificationRecord("distribute_licence",$user_id,$data["introLines"][0]);

        if($mail_result == "ERROR" || $mail_result2=="ERROR")
            return $mail_result;
        else{
            return "SUCCESS";
        }
    }

    public static function informSystemLicenceDistributionToClient($user_id, $the_packet_info, $client_id,$count){

        //fire an event
        Helper::fire_event("distribute_licence_to_client",Auth::user(),"licence_packets", $the_packet_info->id, $client_id);

        $the_client = DB::table('clients')
            ->where('id',$client_id)
            ->first();

        $the_client_user = DB::table('users')
            ->where('status',1)
            ->where('user_type',4)
            ->where('org_id',$client_id)
            ->first();

        App::setLocale($the_client_user->language);
        $the_user = Helper::getUserInfo($user_id);


        $data = array();
        $data["subject"] = trans("notifications.subject_licence_distribution");
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["introLines"][] = trans("notifications.body_licence_distribution_client",
            [
                'user'=>$the_user["user_org"]->name." -> ".$the_user["user"]->name." ( ".$the_user["user"]->email." ) ",
                'assigned_to' => $the_client->name,
                'count' => $count
            ]);

        $currency = "TL";
        if($the_packet_info->price_currency == 2)
            $currency = "USD";
        else if($the_packet_info->price_currency == 3)
            $currency = "EUR";

        $duration = trans("licence_management.monthly");
        if($the_packet_info->duration == 0)
            $duration = trans("licence_management.yearly");

        $data["introLines"][] = trans("notifications.packet_name",["packet_name"=>$the_packet_info->name]);
        $data["introLines"][] = trans("notifications.packet_size",["packet_size"=>$the_packet_info->size, "size_type"=>$the_packet_info->size_type]);
        //$data["introLines"][] = trans("notifications.packet_price",["packet_price"=>$the_packet_info->price, "currency"=>$currency]);
        $data["introLines"][] = trans("notifications.packet_duration",["duration"=>$duration]);
        $data["introLines"][] = trans("notifications.user_count",["user_count"=>$the_packet_info->user_count]);



        $mail_result = self::sendMail($data);
        //$mail_result2 = self::sendMail($data,$the_client->email);
        $mail_result2 = self::sendMail($data,$the_client_user->email, $the_client_user->language);

        self::createNotificationRecord("distribute_licence",$user_id,$data["introLines"][0]);

        if($mail_result == "ERROR" || $mail_result2 =="ERROR")
            return $mail_result;
        else{
            return "SUCCESS";
        }
    }

    public static function informSystemLicenceDistributionToEnduser($user_id, $the_packet_info, $enduser_id,$count){

        //fire an event
        Helper::fire_event("distribute_licence_to_enduser",Auth::user(),"licence_packets", $the_packet_info->id, $enduser_id);

        $the_enduser = DB::table('users')
            ->where('id',$enduser_id)
            ->first();

        App::setLocale($the_enduser->language);

        $the_user = Helper::getUserInfo($user_id);

        //calculate licence_summary
        ScheduledTasks::_summarizeUserLicence($the_enduser);

        $data = array();
        $data["subject"] = trans("notifications.subject_licence_distribution");
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["introLines"][] = trans("notifications.body_licence_distribution_enduser",
            [
                'user'=>$the_user["user_org"]->name." -> ".$the_user["user"]->name." ( ".$the_user["user"]->email." ) ",
                'assigned_to' => $the_enduser->name,
                'count' => $count
            ]);

        $currency = "TL";
        if($the_packet_info->price_currency == 2)
            $currency = "USD";
        else if($the_packet_info->price_currency == 3)
            $currency = "EUR";

        $duration = trans("licence_management.monthly");
        if($the_packet_info->duration == 0)
            $duration = trans("licence_management.yearly");

        $data["introLines"][] = trans("notifications.packet_name",["packet_name"=>$the_packet_info->name]);
        $data["introLines"][] = trans("notifications.packet_size",["packet_size"=>$the_packet_info->size, "size_type"=>$the_packet_info->size_type]);
        //$data["introLines"][] = trans("notifications.packet_price",["packet_price"=>$the_packet_info->price, "currency"=>$currency]);
        $data["introLines"][] = trans("notifications.packet_duration",["duration"=>$duration]);
        $data["introLines"][] = trans("notifications.user_count",["user_count"=>$the_packet_info->user_count]);



        $mail_result = self::sendMail($data);
        $mail_result2 = self::sendMail($data,$the_enduser->email, $the_enduser->language);
        self::createNotificationRecord("distribute_licence",$user_id,$data["introLines"][0]);

        if($mail_result == "ERROR" || $mail_result2 =="ERROR")
            return $mail_result;
        else{
            return "SUCCESS";
        }
    }

    public static function detectLicenceExpires(){

        //firstly create empty record for scheduled_scripts_responses table
        $record_id = DB::table('scheduled_scripts_responses')
            ->insertGetId([

                'script_name' => 'detectLicenceExpires',
                'working_date' => date('Y-m-d')
            ]);

        $record_start_date = date('Y-m-d H:i:s');

        //set licence expire dates if needed
        DB::table('enduser_packets')
            ->where('expire_date','<',date('Y-m-d H:i:s'))
            ->update([
                'status'=>3
            ]);


        //get all installations
        $the_installations = DB::table('installations as I')
            ->select('I.*','U.name as enduser','U.id as enduser_id','U.email as email','U.name as username','C.name as org_name', 'U.language as language')
            ->join('users as U','U.id','I.user_id')
            ->join('clients as C','C.id','U.org_id')
            ->where('I.status',2)
            ->where('U.user_type',5)
            ->where('U.status',1)
            ->get();

        foreach ($the_installations as $one_installation){


            App::setLocale($one_installation->language);

            $active_licences = DB::table('enduser_packets')
                ->where('enduser',$one_installation->enduser_id)
                ->where('status',2)
                ->whereRaw('DATE_SUB(expire_date, INTERVAL 7 DAY) >= "'.date('Y-m-d H:i:s').'"')
                ->get();

            $soon_expired_licences = DB::table('enduser_packets as E')
                ->select(DB::raw('DATEDIFF(E.expire_date, "'.date('Y-m-d H:i:s').'") as remaining_time'),'E.expire_date','E.start_date','LP.name as licence_key','DP.process_id as process_id','LP.size as size','LP.size_type','LP.duration')
                ->leftJoin('distributed_packets as DP','DP.id','E.distributed_packet_id')
                ->leftJoin('licence_packets as LP','LP.id','DP.packet_id')
                ->where('E.enduser',$one_installation->enduser_id)
                ->where('E.status',2)
                ->whereRaw('DATE_SUB(E.expire_date, INTERVAL 7 DAY) < "'.date('Y-m-d H:i:s').'" AND E.expire_date>="'.date('Y-m-d H:i:s').'"')
                ->get();


            $expired_licences = DB::table('enduser_packets as E')
                ->select('E.*','LP.name as licence_key','DP.process_id as process_id','LP.size as size','LP.size_type','LP.duration')
                ->leftJoin('distributed_packets as DP','DP.id','E.distributed_packet_id')
                ->leftJoin('licence_packets as LP','LP.id','DP.packet_id')
                ->where('E.enduser',$one_installation->enduser_id)
                ->where('E.status',3)
                ->get();

            if(COUNT($active_licences) > 0){

            }
            else if(COUNT($soon_expired_licences)>0){

                $data = array();
                $data["subject"] = trans("notifications.subject_licence_about_the_expired");
                $data["introLines"] = array();
                $data["outroLines"] = array();

                $data["logopath"] = "/var/www/html/NarBulutPanel/public/img/slider/Logoü.png";
                //$data["logourl"] = "https://panel.narbulut.com";



                foreach($soon_expired_licences as $one_licence){
                    $data["introLines"] = array();
                    $data["introLines"][0] = trans("notifications.body_licence_about_the_expired_self",
                        [
                            'days'=>$one_licence->remaining_time
                        ]);
                    $data["introLines"][] = trans("finance.licence_key") . " : " . $one_licence->licence_key;
                    $data["introLines"][] = trans("finance.licence_id") . " : " . $one_licence->process_id;
                    $data["introLines"][] = trans("finance.start_date") . " : " . $one_licence->start_date;
                    $data["introLines"][] = trans("finance.end_date") . " : " . $one_licence->expire_date;
                    $data["introLines"][] = trans("finance.duration") . " : " . $one_licence->duration==1 ? trans('finance.monthly'): trans('finance.yearly');
                    $mail_result2 = self::sendMail($data,$one_installation->email, $one_installation->language);


                    $data["introLines"] = array();

                    $data["introLines"][0] = trans("notifications.body_licence_about_the_expired_admin",
                        [
                            'user'=>$one_installation->org_name." -> ".$one_installation->username." ( ".$one_installation->email." )",
                            'days'=>$one_licence->remaining_time
                        ]);
                    $data["introLines"][] = trans("finance.licence_key") . " : " . $one_licence->licence_key;
                    $data["introLines"][] = trans("finance.licence_id") . " : " . $one_licence->process_id;
                    $data["introLines"][] = trans("finance.start_date") . " : " . $one_licence->start_date;
                    $data["introLines"][] = trans("finance.end_date") . " : " . $one_licence->expire_date;
                    $data["introLines"][] = trans("finance.duration") . " : " . $one_licence->expire_date==1 ? trans('finance.monthly'): trans('finance.yearly');
                    $mail_result = self::sendMail($data);


                    self::createNotificationRecord("expire_licence",$one_installation->enduser_id,$data["introLines"][0]);

                    echo "soon_expired_licence<br/>";
                }

            }
            else if(COUNT($expired_licences)>0){

                $data = array();
                $data["subject"] = trans("notifications.subject_licence_expired");
                $data["introLines"] = array();
                $data["outroLines"] = array();
                $data["logopath"] = "/var/www/html/NarBulutPanel/public/img/slider/Logoü.png";
                //$data["logourl"] = "https://panel.narbulut.com";



                foreach($expired_licences as $one_licence){
                    $data["introLines"] = array();
                    $data["introLines"][0] = trans("notifications.body_licence_expired_self");
                    $data["introLines"][] = trans("finance.licence_key") . " : " . $one_licence->licence_key;
                    $data["introLines"][] = trans("finance.licence_id") . " : " . $one_licence->process_id;
                    $data["introLines"][] = trans("finance.start_date") . " : " . $one_licence->start_date;
                    $data["introLines"][] = trans("finance.end_date") . " : " . $one_licence->expire_date;
                    $data["introLines"][] = trans("finance.duration") . " : " . $one_licence->duration==1 ? trans('finance.monthly'): trans('finance.yearly');
                    //$mail_result2 = self::sendMail($data,$one_installation->email);
                    //$mail_result2 = self::sendMail($data,$one_installation->email, $one_installation->language);



                    $data["introLines"] = array();
                    $data["introLines"][0] = trans("notifications.body_licence_expired_admin",
                        [
                            'user'=>$one_installation->org_name." -> ".$one_installation->username." ( ".$one_installation->email." )"
                        ]);
                    $data["introLines"][] = trans("finance.licence_key") . " : " . $one_licence->licence_key;
                    $data["introLines"][] = trans("finance.licence_id") . " : " . $one_licence->process_id;
                    $data["introLines"][] = trans("finance.start_date") . " : " . $one_licence->start_date;
                    $data["introLines"][] = trans("finance.end_date") . " : " . $one_licence->expire_date;
                    $data["introLines"][] = trans("finance.duration") . " : " . $one_licence->duration==1 ? trans('finance.monthly'): trans('finance.yearly');
                    $mail_result = self::sendMail($data);


                    echo "expired_licence<br/>";
                    self::createNotificationRecord("expire_licence",$one_installation->enduser_id,$data["introLines"][0]);
                }
            }
        }

        $record_end_date = date('Y-m-d H:i:s');

        DB::table('scheduled_scripts_responses')
            ->where('id',$record_id)
            ->update([
                'start_date' => $record_start_date,
                'end_date' => $record_end_date
            ]);
    }

    public static function detectNoActiveUsers(){

        //this function is to detect the users whose installations have not sent file, or version for 5 days
        $result = DB::select('SELECT 
    U.id as id,
	U.name, 
	U.email, 
	C.name as client,
	temp_table.closest_date as basefile, 
	temp_table2.closest_date as basesql, 
	temp_table3.closest_date as sqldelta,
	temp_table4.closest_date as filedelta,
	temp_table5.closest_date as basepst,
	temp_table6.closest_date as pstdelta
FROM 
	installations as I
	
	LEFT JOIN
(
	SELECT 
		F.installation_id as inst_id ,
		MAX(MF.created_at) as closest_date 
	FROM 
		monitored_files as MF, 
		monitored_folders as F 
	WHERE 
		MF.folder_id = F.id 
	GROUP BY 
		F.installation_id
) as temp_table ON temp_table.inst_id = I.id
LEFT JOIN 
(
	SELECT 
		S.installation_id as inst_id ,
		MAX(S.created_at) as closest_date 
	FROM 
		sql_backup_files as S 
	GROUP BY 
		S.installation_id
) as temp_table2 
ON 
	temp_table2.inst_id = I.id

LEFT JOIN 
(
	SELECT 
		S.installation_id as inst_id ,
		MAX(D.version_date) as closest_date 
	FROM 
		sql_delta as D
	LEFT JOIN 
		sql_backup_files as S 
	ON S.id=D.file_id 
	GROUP BY S.installation_id
) as temp_table3 
ON 
	temp_table3.inst_id = I.id 

LEFT JOIN 
(
	SELECT 
		F.installation_id as inst_id ,
		MAX(D.version_date) as closest_date 
	FROM 
		file_delta as D
	LEFT JOIN 
		monitored_files as MF 
	ON 
		MF.id = D.file_id
	LEFT JOIN 
		monitored_folders as F
	ON	
		F.id = MF.folder_id
	GROUP BY F.installation_id
) as temp_table4 
ON 
	temp_table4.inst_id = I.id
	
LEFT JOIN 
(
	SELECT 
		P.installation_id as inst_id ,
		MAX(P.created_at) as closest_date 
	FROM 
		pst_files as P 
	GROUP BY 
		P.installation_id
) as temp_table5 
ON 
	temp_table5.inst_id = I.id

LEFT JOIN 
(
	SELECT 
		P.installation_id as inst_id ,
		MAX(D.version_date) as closest_date 
	FROM 
		pst_delta as D
	LEFT JOIN 
		pst_files as P 
	ON P.id=D.file_id 
	GROUP BY P.installation_id
) as temp_table6 
ON 
	temp_table6.inst_id = I.id 
LEFT JOIN 
	users as U 
ON 
	U.id = I.user_id
LEFT JOIN 
    clients as C 
ON 
    C.id = U.org_id
WHERE 
	U.status = 1 AND
	I.status = 2 AND
	U.user_type = 5 AND
	(DATEDIFF(NOW(),temp_table.closest_date) > 5 OR ISNULL(temp_table.closest_date)) AND 
	(DATEDIFF(NOW(),temp_table2.closest_date) > 5 OR ISNULL(temp_table2.closest_date)) AND 
	(DATEDIFF(NOW(),temp_table3.closest_date) > 5 OR ISNULL(temp_table3.closest_date)) AND 
	(DATEDIFF(NOW(),temp_table4.closest_date) > 5 OR ISNULL(temp_table4.closest_date)) AND
	(DATEDIFF(NOW(),temp_table5.closest_date) > 5 OR ISNULL(temp_table5.closest_date)) AND
	(DATEDIFF(NOW(),temp_table6.closest_date) > 5 OR ISNULL(temp_table6.closest_date))
ORDER BY 
	temp_table.closest_date DESC');


        $data = array();
        $data["subject"] = trans("notifications.no_active_users");
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["logopath"] = "/var/www/html/NarBulutPanel/public/img/slider/Logoü.png";
        //$data["logourl"] = "https://panel.narbulut.com";

        $description = "";
        foreach ($result as $one_result){

            $data["introLines"][] = $one_result->name."::".$one_result->email."::".$one_result->client;
            $description .="<br/>".$one_result->name."::".$one_result->email."::".$one_result->client;
        }

        self::createNotificationRecord("no_active_users",0,$description);

        $mail_result = self::sendMail($data);
    }

    public static function informUserNotEnoughSpace($the_user, $currentUsedSize){

        App::setLocale($the_user->language);

        $user_id = $the_user->id;
        $the_installation = DB::table('installations')
            ->where('user_id',$user_id)
            ->where('status',2)
            ->first();

        $perform_notification = false;
        $totalSize = 0;

        if(isset($the_installation->id)){

            //set licence expire dates if needed
            DB::table('enduser_packets')
                ->where('enduser',$user_id)
                ->where('expire_date','<',date('Y-m-d H:i:s'))
                ->update([
                    'status'=>3
                ]);


            //calculate the total size of the user
            $active_licences = DB::table('enduser_packets')
                ->where('enduser',$user_id)
                ->where('status',2)
                ->get();


            foreach ($active_licences as $one_licence){
                $totalSize += Helper::convertToByte($one_licence->size, $one_licence->size_type);
            }

            if($totalSize == 0)
                return "FAILURE";

            //check if it must be really notified,
            $notificationRatio = DB::table('system_configuration')
                ->where('conf_key','space_notification_buffer')
                ->first();
            $notificationRatio = $notificationRatio->conf_value;
            $notificationLengthLimit = 0;

            if($notificationRatio <= 0 || $notificationRatio >= 100)
            {
                $notificationLengthLimit = $totalSize;
            }
            else
            {
                $notificationLengthLimit = $totalSize - ($totalSize * $notificationRatio) / 100;
            }

            if($currentUsedSize > $notificationLengthLimit){

                //check if it is already notified in a day, then not notify
                $alreadyNotification = DB::table('notifications')
                    ->where('user_id',$user_id)
                    ->where('notification_type',5)
                    ->orderBy('created_at','DESC')
                    ->first();

                if(isset($alreadyNotification->created_at)){

                    $diff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($alreadyNotification->created_at))/(60*60));

                    if($diff >=24){

                        $perform_notification = true;
                    }
                    else{
                        return "FAILURE";
                    }

                }
                else{
                    $perform_notification = true;
                }

            }
            else{
                return "FAILURE";
            }

        }
        else{
            return "FAILURE";
        }

        if($perform_notification){

            //if not, fire a notification and the mail
             $data = array();
            $data["introLines"] = array();
            $data["outroLines"] = array();

             if($currentUsedSize <= $totalSize){

                 $usage_ratio = ($currentUsedSize * 100) / $totalSize;

                 $data["subject"] = trans("notifications.about_not_enough_space_subject");
                 $data["introLines"][] = trans("notifications.about_not_enough_space_body",[
                     'totalSize' => Helper::human_filesize($totalSize),
                     'usegae_ratio' => number_format($usage_ratio)
                 ]);
             }
             else{
                 $data["subject"] = trans("notifications.not_enough_space_subject");
                 $data["introLines"][] = trans("notifications.not_enough_space_body",[
                     'totalSize' => Helper::human_filesize($totalSize)
                 ]);
             }


             $mail_result = self::sendMail($data, $the_user->email, $the_user->language);
             self::createNotificationRecord("not_enough_space",$user_id,$data["introLines"][0]);

             if($mail_result == "ERROR")
                 return $mail_result;
             else{
                 return "SUCCESS";
             }

        }
    }

    public static function createNotificationRecord($notification_type, $user_id, $description){

        $notification_type = DB::table('notification_type')
            ->where("name",$notification_type)
            ->first();

        if(isset($notification_type->id)){

            DB::table("notifications")
                ->insert([
                    "notification_type" => $notification_type->id,
                    "user_id" => $user_id,
                    "description" => $description
                ]);
        }
        else{
            abort(404);
        }
    }

    public static function informUserPasswordChange($the_user){

        App::setLocale($the_user->language);
        $data = array();
        $data["introLines"] = array();
        $data["outroLines"] = array();

        $data["subject"] = trans("notifications.password_change_subject");

        $data["introLines"][] = trans("notifications.password_change_body");

        $mail_result = self::sendMail($data, $the_user->email, $the_user->language);



        if($mail_result == "ERROR")
            return $mail_result;
        else{
            return "SUCCESS";
        }

    }

    public static function sendMail($data, $to=null, $language=false, $view = false){


        if($language != false)
            App::setLocale($language);

        if($to == null)
            $mail_to = env('MAIL_USERNAME');
        else
            $mail_to = $to;

        $data["mail_to"] = $mail_to;


        if($view ==false)
            $view = 'vendor.notifications.email';

        Mail::send($view, $data, function($message) use ($data) {
            $message->to($data["mail_to"], $data["mail_to"])->subject($data["subject"]);
            $message->from(env('MAIL_USERNAME'),trans("global.mail_sender_name"));

            if(isset($data["attachment_stream"])){
                $message->attachData($data["attachment_stream"]["stream"], $data["attachment_stream"]["name"], [
                    'mime' => $data["attachment_stream"]["mime"]
                ]);
            }
        });

        if(count(Mail::failures())>0){

            return "ERROR";
        }
        else{
            return "SUCCESS";
        }
    }
}