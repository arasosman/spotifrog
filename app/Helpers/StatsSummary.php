<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StatsSummary
{
    public static function statsDistFileLength(){


        $param_array = array();
        $param_array[] = date('Y-m-d 00:00:00');
        $param_array[] = date('Y-m-d 23:59:59');


        $where_clause = 'MF.created_at BETWEEN ? AND ? ';

        $result = DB::select('
              SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    SD.id as id,
                    first_temp.date as the_date
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count	
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1 AND '.$where_clause.'
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY SD.id, first_temp.date
                ORDER BY the_date
        ',$param_array);


        foreach ($result as $one_result){
            DB::insert('INSERT INTO stats_dist_file_length (dist_id, the_date, the_count, the_length) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE the_count=?, the_length=?',[$one_result->id, date('Y-m-d',strtotime($param_array[0])),$one_result->the_count,$one_result->the_length,$one_result->the_count,$one_result->the_length]);
        }
    }

    public static function statsDistFileLengthNew(){

        //create a temp table
        DB::statement('CREATE TABLE `stats_dist_file_length_temp` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `dist_id` int(11) NOT NULL,
             `the_date` date NOT NULL,
             `the_count` bigint(20) NOT NULL,
             `the_length` bigint(20) NOT NULL,
             PRIMARY KEY (`id`),
             UNIQUE KEY `unique_index` (`dist_id`,`the_date`)
            ) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8
        ');

        //run query
        DB::insert('
            INSERT INTO stats_dist_file_length_temp (the_count, the_length, dist_id, the_date)
                SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    SD.id as id,
                    first_temp.date as the_date
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count	
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY SD.id, first_temp.date
                ORDER BY the_date
        ');

        //drop original table
        DB::statement('DROP TABLE stats_dist_file_length');


        //rename table name
        DB::statement('RENAME TABLE stats_dist_file_length_temp to stats_dist_file_length');

    }

    public static function statsResellerFileLength(){

        $param_array = array();
        $param_array[] = date('Y-m-d 00:00:00');
        $param_array[] = date('Y-m-d 23:59:59');


        $where_clause = 'MF.created_at BETWEEN ? AND ? ';

        $result = DB::select('
              SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    D.id as id,
                    first_temp.date as the_date
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count	
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1 AND '.$where_clause.'
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY D.id, first_temp.date
                ORDER BY the_date
        ',$param_array);


        foreach ($result as $one_result){
            DB::insert('INSERT INTO stats_reseller_file_length (reseller_id, the_date, the_count, the_length) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE the_count=?, the_length=?',[$one_result->id, date('Y-m-d',strtotime($param_array[0])),$one_result->the_count,$one_result->the_length,$one_result->the_count,$one_result->the_length]);
        }
    }

    public static function statsResellerFileLengthNew(){

        //create a temp table
        DB::statement('CREATE TABLE `stats_reseller_file_length_temp` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `reseller_id` int(11) NOT NULL,
             `the_date` date NOT NULL,
             `the_count` bigint(20) NOT NULL,
             `the_length` bigint(20) NOT NULL,
             PRIMARY KEY (`id`),
             UNIQUE KEY `unique_index` (`reseller_id`,`the_date`)
            ) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8
        ');

        //run query
        DB::insert('
            INSERT INTO stats_reseller_file_length_temp (the_count, the_length, reseller_id, the_date)
                SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    D.id as id,
                    first_temp.date as the_date
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count	
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY D.id,first_temp.date
                ORDER BY the_date
        ');

        //drop original table
        DB::statement('DROP TABLE stats_reseller_file_length');


        //rename table name
        DB::statement('RENAME TABLE stats_reseller_file_length_temp to stats_reseller_file_length');

    }

    public static function statsClientFileLength(){

        $param_array = array();
        $param_array[] = date('Y-m-d 00:00:00');
        $param_array[] = date('Y-m-d 23:59:59');


        $where_clause = 'MF.created_at BETWEEN ? AND ? ';

        $result = DB::select('
              SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    C.id as id,
                    first_temp.date as the_date
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count	
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1 AND '.$where_clause.'
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY C.id, first_temp.date
                ORDER BY the_date
        ',$param_array);


        foreach ($result as $one_result){
            DB::insert('INSERT INTO stats_reseller_file_length (reseller_id, the_date, the_count, the_length) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE the_count=?, the_length=?',[$one_result->id, date('Y-m-d',strtotime($param_array[0])),$one_result->the_count,$one_result->the_length,$one_result->the_count,$one_result->the_length]);
        }
    }

    public static function statsClientFileLengthNew(){

        //create a temp table
        DB::statement('CREATE TABLE `stats_client_file_length_temp` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `client_id` int(11) NOT NULL,
             `the_date` date NOT NULL,
             `the_count` bigint(20) NOT NULL,
             `the_length` bigint(20) NOT NULL,
             PRIMARY KEY (`id`),
             UNIQUE KEY `unique_index` (`client_id`,`the_date`)
            ) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8
        ');

        //run query
        DB::insert('
            INSERT INTO stats_client_file_length_temp (the_count, the_length, client_id, the_date)
                SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    C.id as id,
                    first_temp.date as the_date
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count	
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY C.id,first_temp.date
                ORDER BY the_date
        ');

        //drop original table
        DB::statement('DROP TABLE stats_client_file_length');


        //rename table name
        DB::statement('RENAME TABLE stats_client_file_length_temp to stats_client_file_length');

    }

    public static function statsEnduserFileLength(){

        $param_array = array();
        $param_array[] = date('Y-m-d 00:00:00');
        $param_array[] = date('Y-m-d 23:59:59');


        $where_clause = 'MF.created_at BETWEEN ? AND ? ';

        $result = DB::select('
              SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    U.id as id,
                    first_temp.date as the_date
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count	
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1 AND '.$where_clause.'
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY U.id, first_temp.date
                ORDER BY the_date
        ',$param_array);


        foreach ($result as $one_result){
            DB::insert('INSERT INTO stats_enduser_file_length (user_id, the_date, the_count, the_length) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE the_count=?, the_length=?',[$one_result->id, date('Y-m-d',strtotime($param_array[0])),$one_result->the_count,$one_result->the_length,$one_result->the_count,$one_result->the_length]);
        }
    }

    public static function statsEnduserFileLengthNew(){

        //create a temp table
        DB::statement('CREATE TABLE `stats_enduser_file_length_temp` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `user_id` int(11) NOT NULL,
             `the_date` date NOT NULL,
             `the_count` bigint(20) NOT NULL,
             `the_length` bigint(20) NOT NULL,
             PRIMARY KEY (`id`),
             UNIQUE KEY `unique_index` (`user_id`,`the_date`)
            ) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8
        ');

        //run query
        DB::insert('
            INSERT INTO stats_enduser_file_length_temp (the_count, the_length, user_id, the_date)
                SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    U.id as id,
                    first_temp.date as the_date
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count	
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY U.id,first_temp.date
                ORDER BY the_date
        ');

        //drop original table
        DB::statement('DROP TABLE stats_enduser_file_length');


        //rename table name
        DB::statement('RENAME TABLE stats_enduser_file_length_temp to stats_enduser_file_length');

    }

    public static function statsEnduserConnection(){

        $the_installations = DB::table('installations as I')
            ->select('I.id as id')
            ->leftJoin('users as U','U.id','I.user_id')
            ->where('I.status',2)
            ->where('U.status',1)
            ->get();

        foreach($the_installations as $one_installation){

            $last_heart_beat = DB::table('heartbeat')
                ->where('installation_id',$one_installation->id)
                ->orderBy('id','desc')
                ->first();

            if(isset($last_heart_beat->id)){

                DB::table("installations")
                    ->where('id',$one_installation->id)
                    ->update([
                        'last_connected_at' => $last_heart_beat->created_date
                    ]);
            }
        }
    }

    public static function statsFileExtension(){

        $param_array = array();
        $param_array[] = date('Y-m-d 00:00:00');
        $param_array[] = date('Y-m-d 23:59:59');


        $where_clause = 'MF.created_at BETWEEN ? AND ? ';

        $result = DB::select('
              SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    U.id as id,
                    first_temp.date as the_date,
                    first_temp.extension
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count,
                        SUBSTRING_INDEX(MF.file_name,".",-1) as extension
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1 AND '.$where_clause.'
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at),
                        SUBSTRING_INDEX(MF.file_name,".",-1)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY U.id, first_temp.date, first_temp.extension
                ORDER BY the_date
        ',$param_array);


        foreach ($result as $one_result){
            DB::insert('INSERT INTO stats_extension_file_length (extension,user_id, the_date, the_count, the_length) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE the_count=?, the_length=?',[$one_result->extension,$one_result->id, date('Y-m-d',strtotime($param_array[0])),$one_result->the_count,$one_result->the_length,$one_result->the_count,$one_result->the_length]);
        }
    }

    public static function statsFileExtensionNew(){

        //create a temp table
        DB::statement('CREATE TABLE `stats_extension_file_length_temp` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `user_id` int(11) NOT NULL,
             `extension` varchar(255),
             `the_date` date NOT NULL,
             `the_count` bigint(20) NOT NULL,
             `the_length` bigint(20) NOT NULL,
             PRIMARY KEY (`id`),
             UNIQUE KEY `unique_index` (`user_id`,`extension`,`the_date`)
            ) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8
        ');

        //run query
        DB::insert('
            INSERT INTO stats_extension_file_length_temp (the_count, the_length, user_id, the_date, extension)
                SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    U.id as id,
                    first_temp.date as the_date,
                    first_temp.extension
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count,
                        SUBSTRING_INDEX(MF.file_name,".",-1) as extension
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at),
                        SUBSTRING_INDEX(MF.file_name,".",-1)
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY U.id, first_temp.date, first_temp.extension
                ORDER BY the_date
        ');

        //drop original table
        DB::statement('DROP TABLE stats_extension_file_length');


        //rename table name
        DB::statement('RENAME TABLE stats_extension_file_length_temp to stats_extension_file_length');

    }

    public static function statsFileLengthInterval(){



        $param_array = array();
        $param_array[] = date('Y-m-d 00:00:00');
        $param_array[] = date('Y-m-d 23:59:59');


        $where_clause = 'MF.created_at BETWEEN ? AND ? ';

        $result = DB::select('
              SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    U.id as id,
                    first_temp.date as the_date,
                    first_temp.size_interval
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count,
                        (CASE WHEN MF.file_length > 5368709120 THEN 1
                            WHEN MF.file_length > 1073741824 THEN 2
                            WHEN MF.file_length>524288000 THEN 3
                            WHEN MF.file_length>104857600 THEN 4
                            WHEN MF.file_length>10485760 THEN 5
                            WHEN MF.file_length>1048576 THEN 6
                            ELSE 7 END) as size_interval
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1 AND '.$where_clause.'
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at),
                        size_interval
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY U.id, first_temp.date, first_temp.size_interval
                ORDER BY the_date
        ',$param_array);


        foreach ($result as $one_result){
            DB::insert('INSERT INTO stats_file_length_interval (the_interval,user_id, the_date, the_count, the_length) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE the_count=?, the_length=?',[$one_result->size_interval,$one_result->id, date('Y-m-d',strtotime($param_array[0])),$one_result->the_count,$one_result->the_length,$one_result->the_count,$one_result->the_length]);
        }
    }

    public static function statsFileLengthIntervalNew(){

        //create a temp table
        DB::statement('CREATE TABLE `stats_file_length_interval_temp` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `user_id` int(11) NOT NULL,
             `the_interval` SMALLINT(6),
             `the_date` date NOT NULL,
             `the_count` bigint(20) NOT NULL,
             `the_length` bigint(20) NOT NULL,
             PRIMARY KEY (`id`),
             UNIQUE KEY `unique_index` (`user_id`,`the_interval`,`the_date`)
            ) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8
        ');

        //run query
        DB::insert('
            INSERT INTO stats_file_length_interval_temp (the_count, the_length, user_id, the_date, the_interval)
                SELECT 
                    SUM(first_temp.count) as the_count,
                    SUM(first_temp.size) as the_length,
                    U.id as id,
                    first_temp.date as the_date,
                    first_temp.size_interval
                FROM 
                    (
                        SELECT
                        SUM(MF.file_length) as size,
                        MF.folder_id as folder_id,
                        DATE(MF.created_at) as date,
                        COUNT(MF.id) as count,
                        (CASE WHEN MF.file_length > 5368709120 THEN 1
                            WHEN MF.file_length > 1073741824 THEN 2
                            WHEN MF.file_length>524288000 THEN 3
                            WHEN MF.file_length>104857600 THEN 4
                            WHEN MF.file_length>10485760 THEN 5
                            WHEN MF.file_length>1048576 THEN 6
                            ELSE 7 END) as size_interval
                    FROM  
                        monitored_files as MF
                    WHERE 
                        MF.status=1
                    GROUP BY 
                        MF.folder_id, 
                        DATE(MF.created_at),
                        size_interval
                    ) as first_temp,
                    monitored_folders as F,
                    installations as I,
                    users as U,
                    clients as C,
                    distributors as D,
                    super_distributors as SD
                WHERE 
                    first_temp.folder_id=F.id AND
                    F.status = 1 AND
                    I.id=F.installation_id AND
                    I.status=2 AND
                    U.id = I.user_id AND
                    U.status=1 AND U.user_type=5 AND
                    C.id=U.org_id AND C.status=1 AND
                    D.id=C.distributor_id AND D.status=1 AND 
                    SD.id=D.super_distributor_id AND SD.status=1
                GROUP BY U.id, first_temp.date, first_temp.size_interval
                ORDER BY the_date
        ');

        //drop original table
        DB::statement('DROP TABLE stats_file_length_interval');


        //rename table name
        DB::statement('RENAME TABLE stats_file_length_interval_temp to stats_file_length_interval');

    }
}