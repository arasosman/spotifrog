<?php

namespace App\Http\Controllers\Front;

use App\Helpers\ScheduledTasks;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SpotifyApiController;
use App\Model\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request){

        return view('pages.front.home');
    }

    public function detail(Request $request, $type, $id){

        if($type == "artist"){
            $data = $this->artistDetail($id);
            $data = json_encode($data);
            return view('pages.front.detail_artist')->with('data',$data);
        }
        elseif ($type == "track"){
            $data = $this->trackDetail($id);
            //dd($data);
            $data = json_encode($data);
            return view('pages.front.detail_track')->with('data',$data);
        }else{
            abort(404);
        }

    }

    private function artistDetail($id){
        $data= array();
        $spotify = new SpotifyApiController();
        $data["artist"] = $spotify->getArtist($id);
        $data["albums"] = $spotify->getArtistAlbums($id);
        $data["top_tracks"] = $spotify->getArtistTopTracks($id);
        $data["relative_artists"] = $spotify->getArtistRelatedArtists($id);
        $data["relative_artists"]->artists = array_slice($data["relative_artists"]->artists, 0, 6);
        $data["albums"]->items = array_slice($data["albums"]->items, 0, 5);
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
}
