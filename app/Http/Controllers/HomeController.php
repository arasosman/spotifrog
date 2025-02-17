<?php

namespace App\Http\Controllers;

use App\CrawlingList;
use App\Helpers\DataTable;
use App\Helpers\ScheduledTasks;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SpotifyApiController;
use App\User;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return view('pages.home');
    }

    public function detail(Request $request, $type, $id){

        if($type == "artist"){
            $data = $this->artistDetail($id);
            $data = json_encode($data);
            return view('pages.detail_artist')->with('data',$data);
        }
        elseif ($type == "track"){
            $data = $this->trackDetail($id);
            $data = json_encode($data);
            return view('pages.detail_track')->with('data',$data);
        }
        elseif ($type == "album"){
            $data = $this->albumDetail($id);
            $data = json_encode($data);
            return view('pages.detail_album')->with('data',$data);
        }
        elseif ($type == "playlist"){
            $data = $this->playlistDetail($id);
            $data = json_encode($data);
            return view('pages.detail_playlist')->with('data',$data);
        }
        else{
            abort(404);
        }
    }

    public function artistDetail($id){

        $data= array();
        $spotify = new SpotifyApiController();
        $data["artist"] = $spotify->getArtist($id);
        $data["albums"] = $spotify->getArtistAlbums($id);
        $data["top_tracks"] = $spotify->getArtistTopTracks($id);
        $data["relative_artists"] = $spotify->getArtistRelatedArtists($id);
        $data["html"] = $this->artistDetailUrl($id);
        $data["relative_artists"]->artists = array_slice($data["relative_artists"]->artists, 0, 6);
        $data["albums"]->items = array_slice($data["albums"]->items, 0, 8);

        //add the crawling list
        $is_it_there = CrawlingList::isthere($data["artist"]->id)->first();
        if(!isset($is_it_there->id)){
            $crawler = new CrawlingList();
            $crawler->type = "artist";
            $crawler->uniq_id = $data["artist"]->id;
            $crawler->name = $data["artist"]->name;

            $crawler->save();
        }

        return $data;
    }

    private function trackDetail($id){
        $data = array();
        $spotify = new SpotifyApiController();
        $data['track']= $spotify->getTrack($id);
        $data['other_tracks'] = $spotify->getArtistTopTracks($data['track']->album->artists[0]->id);
        $data['artist'] = $spotify->getArtist($data['track']->album->artists[0]->id);
        $data['other_tracks']->tracks = array_slice($data['other_tracks']->tracks, 0, 5);
        return $data;
    }

    private function albumDetail($id){
        $data = array();
        $spotify = new SpotifyApiController();
        $data['album'] = $spotify->getAlbum($id);
        $data{'tracks'} = $spotify->getAlbumTracks($id);
        return $data;
    }

    private function playlistDetail($id){
        $data = array();
        $spotify = new SpotifyApiController();
        $data['playlist'] = $spotify->getPlaylist($id);
        return $data;
    }

    private function artistDetailUrl($id){
        $follow_arr = array();
        try{
            $url = "https://open.spotify.com/artist/".$id;
            $content = file_get_contents($url);
            $dom = HtmlDomParser::str_get_html($content);

            foreach ($dom->find('section[class=more-by] h3') as $item){
                $follow_arr[] = $item->innertext();
            }

            $bio = array();
            if($dom->find('section[class=bio]')){
                $bio[] = $dom->find('section[class=bio] div div span',0)->innertext();
                $bio[] = $dom->find('section[class=bio] div div span',1)->innertext();
            }

            $data = array();
            $data["monthly_listeners"] = $follow_arr[0];
            $data["followers"] = $follow_arr[1];
            $data['bio1'] = $bio[0] ?? "";
            $data['bio2'] = $bio[1] ?? "";


            //get viewer locations
            $location_element = $dom->find('section[class=view more-by horizontal-list] ul',0);
            $child_locations = $location_element->find(".horizontal-list__item");

            $location_str = [];
            foreach ($child_locations as $one_locations){

                $loc = $one_locations->find(".horizontal-list__item__title",0)->plaintext;
                $view_count = $one_locations->find(".horizontal-list__item__subtitle",0)->plaintext;
                $view_count = explode(' ',$view_count);
                $view_count = str_replace(',','',$view_count[0]);

                $location_str[] = ["loc"=>$loc, "view_count"=>$view_count];
            }

            $data["view_location"] = json_encode($location_str);


        }
        catch (\Exception $e){

        }


        return $data;
    }

    public function test(){

        ScheduledTasks::fetchDetailInfoForUnclaimedArtist();
    }
}
