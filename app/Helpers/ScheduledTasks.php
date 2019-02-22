<?php

namespace App\Helpers;

use App\CrawlingList;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpotifyApiController;
use App\Model\Playlist;
use App\Model\PlaylistItem;
use App\Model\Schedule;
use App\UnclaimedArtist;
use Carbon\Carbon;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class ScheduledTasks
{

    /*
     * This function is called periodically which is determined in /app/console/kernel.php, to summarize the data based on their daily calculations
     *
     */

    public static function addRelatedArtist(){
        $crawl_list = CrawlingList::where('relative_scan',false)->limit(100)->get();

        $arr = array();
        foreach ($crawl_list as $item) {
            $item->relative_scan = true;
            $item->save();
            self::addListRelatedArtist($item);
        }

    }

    private static function addListRelatedArtist($artist){
        $spotify = new SpotifyApiController();
        $relatedArtists = $spotify->getArtistRelatedArtists($artist->uniq_id);
        foreach ($relatedArtists->artists as $item){
            $is_it_there = CrawlingList::isthere($item->id)->first();
            if(!isset($is_it_there->id)) {
                $crawler = new CrawlingList();
                $crawler->type = "artist";
                $crawler->uniq_id = $item->id;
                $crawler->name = $item->name;
                $crawler->save();
            }
        }
    }

    public static function checkArtistListIfClaimed(){

        $crawl_list = CrawlingList::where('status',1)->limit(100)->get();

        foreach ($crawl_list as $artist){

            $isClaimed = self::checkArtistIfClaimed($artist);
            if($isClaimed)
                $artist->status = 2;
            else{

                $artist->status = 3;
                self::saveUnclaimedArtist($artist);
            }
            $artist->save();
        }
    }

    private static function checkArtistIfClaimed($artist){

        try{
            $client_id = '6491562e26a74a4dae998b7dbaf6983f';
            $artist_id = $artist->uniq_id;

            $url = "https://generic.wg.spotify.com/creator-auth-proxy/api/token";
            $data = ['client_id'=>$client_id, 'grant_type'=>'client_credentials'];

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            $result = json_decode($result);

            $url = "https://generic.wg.spotify.com/s4a-onboarding/v0/access/artist/".$artist_id."/claimed";
            $token = $result->token_type." ".$result->access_token;
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/json\r\nauthorization: ".$token."\r\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\r\nAccept: application/json\r\nAccept-Language: en-US,en;q=0.5\r\nReferer: https://artists.spotify.com/c/access/artist/".$artist_id."\r\ncontent-type: application/json\r\nspotify-app-version: 1.0.0.c4ef884\r\napp-platform: Browser\r\n",
                    'method'  => 'GET'
                )
            );
            $context  = stream_context_create($options);
            $final_result = file_get_contents($url, false, $context);
            $final_result = json_decode($final_result);

            return $final_result->isClaimed;
        }
        catch(\Exception $exception){
            return false;
        }
    }

    private static function saveUnclaimedArtist($artist){

        $unclaimed_artist = new UnclaimedArtist();
        $unclaimed_artist->uniq_id = $artist->uniq_id;
        $unclaimed_artist->name = $artist->name;
        $unclaimed_artist->save();
    }

    public static function fetchDetailInfoForUnclaimedArtist(){

        $unclaimed_artists = UnclaimedArtist::where('follower_count',null)->limit(100)->get();

        foreach ($unclaimed_artists as $one_artist){

            try{

                $home = new HomeController();
                $artist_detail = $home->artistDetail($one_artist->uniq_id);

                $one_artist->follower_count = $artist_detail["artist"]->followers->total;
                $one_artist->view_count = str_replace(',','',$artist_detail["html"]["monthly_listeners"]);
                $one_artist->view_locations = $artist_detail["html"]["view_location"];
                $one_artist->genres = json_encode($artist_detail["artist"]->genres);
                $one_artist->image = isset($artist_detail["artist"]->images[0]->url)?$artist_detail["artist"]->images[0]->url:"";
                $one_artist->popularity = $artist_detail["artist"]->popularity;
                $one_artist->updated_at = Carbon::now();
                $one_artist->save();
            }
            catch(\Exception $exception){
                echo $exception->getMessage();
            }
        }
    }



}
