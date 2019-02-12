<?php

namespace App\Http\Controllers;

use App\Model\Playlist;
use App\Model\PlaylistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SpotifyApiController extends Controller
{
    private $accessToken;
    private $refreshToken;

    public function spotifyLogin(Request $request){
        //this function does all the api work. because I couldn't find another token retrieval method. constantly wants new key.
        $playlist_id ="10g6JTfiQQPQXATVZeEcpt";
        $session = new \SpotifyWebAPI\Session(
            '295a07ba232f46ac8e0b95cc99fb259d',
            '0809b486d7e64231be376d9547d75c2f',
            'http://spotify.hizmet.site/spotify-login'
        );

        if(isset($_GET['code'])){
            $session->requestAccessToken($_GET['code']);
            //get last list from db. The page cannot access sessions. I also read from the database.
            $type = DB::table('api-remember')->orderBy('id','desc')->first();
            $data = json_decode($type->data);

            $accessToken = $session->getAccessToken();
            $refreshToken = $session->getRefreshToken();
            $api = new \SpotifyWebAPI\SpotifyWebAPI();
            $api->setAccessToken($accessToken);
            $api->getPlaylist($playlist_id);

            //clear old list
            if($type->type == "addPlaylistTracks"){
                //300 items delete;
                for($i=0; $i<20;$i++){
                    $remoteList = $api->getPlaylist($playlist_id);
                    if($remoteList->tracks->total < 20)
                        break;
                    $arr = range(0,20);
                    try{
                        $trackOptions = [
                            'positions' =>
                                $arr
                            ,
                        ];
                        $api->deletePlaylistTracks($playlist_id, $trackOptions,$remoteList->snapshot_id);
                    }
                    catch (\Exception $e){echo $e->getMessage();}
                    usleep(50);
                }
                //other delete
                for($i=0; $i<20;$i++){
                    $remoteList = $api->getPlaylist($playlist_id);
                    if($remoteList->tracks->total < 2)
                        break;
                    $arr = range(0,1);
                    try{
                        $trackOptions = [
                            'positions' =>
                                $arr
                            ,
                        ];
                        $api->deletePlaylistTracks($playlist_id, $trackOptions,$remoteList->snapshot_id);
                    }
                    catch (\Exception $e){echo $e->getMessage();}
                    usleep(200);
                }
                $remoteList = $api->getPlaylist($playlist_id);

                foreach ($remoteList->tracks->items as $item) {
                    try{
                        $tracks = [
                            'tracks' => [
                                ['id' => $item->track->id]
                            ],
                        ];

                        $api->deletePlaylistTracks($playlist_id, $tracks, $remoteList->snapshot_id);
                    }
                    catch (\Exception $e){echo $e->getMessage();}
                }
                //add track
                foreach ($data as $item){
                    $result = $api->addPlaylistTracks($playlist_id, [
                        $item
                    ]);
                    usleep(200);
                }
                if($result == 1)
                    return \redirect()->to('/admin');
                else
                    return "Error";
            }
            elseif ($type->type == "createPlaylist"){

                $result = $api->createPlaylist([
                    'name' => $data->title,
                    'description' => $data->description
                ]);
                echo $result;
            }
            elseif ($type->type == "followList"){

                $result = $api->followPlaylistForCurrentUser($playlist_id);
                return redirect()->route('home',['status' => 'close-tab']);
            }
            elseif ($type->type == "playTrack"){
                $result = $api->getMyCurrentPlaybackInfo();
                echo $result;
            }
        }
        else{
            $options = [
                'scope' => [
                    'playlist-read-private',
                    'user-read-private',
                    'playlist-modify-private',
                    'playlist-modify-public',
                ],
            ];

            header('Location: ' . $session->getAuthorizeUrl($options));
            die();
        }

    }

    public function prepareApi(){

        $session = new \SpotifyWebAPI\Session(
            '178913f7690d44f89086f7d2e096107c',
            '5c497e34fd7842a6beb5b87a1ae1307f',
            'http://spotify.hizmet.site/spotify-login'
        );
        $session->requestCredentialsToken();
        $accessToken = $session->getAccessToken();

        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($accessToken);
        return $api;
    }

    public function searchTrack(Request $request){
        if(!$request->has("term"))
            return null;
        // serach params control
        $search_params = $request->input("term");
        if(!isset($search_params["term"]))
            return null;
        $api = $this->prepareApi();

        $results = $api->search($search_params['term'], 'track');
        $trackList= array();
        //dd($results->tracks->items);
        $count=0;
        foreach ($results->tracks->items as $item) {
            $trackList[$count]["artist"]= $item->artists[0]->name;
            $trackList[$count]["track"]= $item->name;
            $trackList[$count]["id"]= $item->id;
            $count++;
        }
        return response()->json($trackList);
    }

    public function getTrack(Request $request){
        if(!$request->has("id"))
            return null;
        // search params control
        $id = $request->input("id");

        $api = $this->prepareApi();
        $result = $api->getTrack($id);
        return response()->json($result);
    }

    public function saveImage(Request $request){
        if(!$request->has("src"))
            return null;
        $src= $request->input("src");

        $filename = basename($src);
        $newline = getcwd().'/img/poster/';
        copy($src, $newline.$filename.'.jpg');
        return 'img/poster/'.$filename.'.jpg';
    }

    public function getTrackByLink(Request $request){
        if(!$request->has("link"))
            return null;
        $link = $request->input("link");

        $api = $this->prepareApi();

        if(preg_match("/track/",$link)){
            $split_arr = preg_split('/track\//', $link);
            $result = $api->getTrack($split_arr[1]);
            return response()->json($result);
        }else if(preg_match("/album/",$link)){
            $split_arr = preg_split('/album\//', $link);
            $result = $api->getAlbumTracks($split_arr[1]);
            return response()->json($result->items[0]);
        }
    }

    public function getPlaylist(){
        $api = $this->prepareApi();
        $result = $api->getPlaylist("08hpqbgt5B7JL6r1ocJqEd");
        return response()->json($result);
    }

    public function createPlaylist(Request $request){
        $data = $request->input('data');
        $data = json_encode($data);
        DB::table('api-remember')->insert([
            "type" => "createPlaylist",
            "data" => $data
        ]);
        return redirect()->route('spotify-login');

    }

    public function addPlaylistTracks(Request $request){
        $list = Playlist::where('date_type',2)->orderBy('id','desc')->first();
        $items = PlaylistItem::where('list_id',$list->id)->get();
        $tracks = array();
        foreach ($items as $item){
            $tracks[]=$item->item_id;
        }
        $data = json_encode($tracks);
        DB::table('api-remember')->insert([
            "type" => "addPlaylistTracks",
            "data" => $data
        ]);

        return redirect()->route('spotify-login');
    }

    public function getPlaylistTrack(){
        $api = $this->prepareApi();
        $playlistTracks = $api->getPlaylistTracks('08hpqbgt5B7JL6r1ocJqEd');
        $return_arr = array();
        foreach ($playlistTracks->items as $track) {
            $return_arr = $track->track;
        }
        print_r($return_arr);
        //return response()->json($return_arr);
    }

    public function followList(){
        DB::table('api-remember')->insert([
            "type" => "followList"
        ]);
        return redirect()->route('spotify-login');
    }

    public function playTrack(Request $request,$track){
        DB::table('api-remember')->insert([
            "type" => "playTrack"
        ]);
        return redirect()->route('spotify-login');
    }
}
