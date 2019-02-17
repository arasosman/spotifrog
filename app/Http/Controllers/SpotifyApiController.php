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
            'http://spotifrog.hizmet.site/apilogin'
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
            '295a07ba232f46ac8e0b95cc99fb259d',
            '0809b486d7e64231be376d9547d75c2f',
            'http://spotifrog.hizmet.site/apilogin'
        );
        $session->requestCredentialsToken();
        $accessToken = $session->getAccessToken();

        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($accessToken);
        return $api;
    }

    public function search(Request $request){

        if(!$request->has('the_obj'))
            return "null";

        $the_obj = json_decode($request->input('the_obj'));
        $api = $this->prepareApi();

        $limit = 20;
        if(isset($the_obj->limit) && $the_obj->limit > 0)
            $limit= $the_obj->limit;

        $results = "[]";
        $query = "";
        if($the_obj->keyword != ""){

            $query = $the_obj->keyword;
            $search_type_arr = "";
            if(isset($the_obj->track) && $the_obj->track)
                $search_type_arr .= "track,";
            if(isset($the_obj->artist) && $the_obj->artist)
                $search_type_arr .= "artist,";
            if(isset($the_obj->album) && $the_obj->album)
                $search_type_arr .= "album,";
            if(isset($the_obj->playlist) && $the_obj->playlist)
                $search_type_arr .= "playlist,";
            if(isset($the_obj->year) && $the_obj->year != "")
                $query.=" year:".$the_obj->year;

            $search_type_arr = trim($search_type_arr,',');
            $results = $api->search($query , $search_type_arr ,['limit' => $limit]);
            if(isset($the_obj->artist)){
                if((isset($the_obj->min_followers) && $the_obj->min_followers > 0)&&(isset($the_obj->max_followers) && $the_obj->max_followers > 0)){
                    $results->artists->items = $this->followersCountSelect($results,$the_obj->min_followers,$the_obj->max_followers);
                }elseif ((isset($the_obj->min_followers) && $the_obj->min_followers > 0)){
                    $results->artists->items = $this->followersCountSelect($results,$the_obj->min_followers,-1);
                }elseif ((isset($the_obj->max_followers) && $the_obj->max_followers > 0)){
                    $results->artists->items = $this->followersCountSelect($results,-1,$the_obj->max_followers);
                }
            }
            if($the_obj->hide_stat ==0){
                if(isset($the_obj->artist) || isset($the_obj->track)){
                    if($the_obj->popularity >0){
                        if(isset($results->artists)){
                            $results->artists->items = $this->populartySelect($results, $the_obj->popularity*10);
                        }
                        elseif(isset($results->tracks)){
                            $results->tracks->items = $this->populartySelect($results, $the_obj->popularity*10);
                        }
                    }
                }
            }

        }

        return response()->json($results);
    }

    private function populartySelect($result,$min_pop){

        $return_arr = array();
        if(isset($result->artists)){
            foreach ($result->artists->items as $one_artist){
                if($one_artist->popularity > $min_pop){
                    $return_arr[] = $one_artist;
                }
            }
        }elseif (isset($result->tracks)){
            foreach ($result->tracks->items as $one_track){
                if($one_track->popularity > $min_pop){
                    $return_arr[] = $one_track;
                }
            }
        }
        return $return_arr;

    }

    private function followersCountSelect($result,$min,$max){
        $return_arr = array();
        if(isset($result->artists) && is_array($result->artists->items)){
            foreach ($result->artists->items as $one_artist){
                if($max >0 && $min >0){
                    if($one_artist->followers->total > $min && $one_artist->followers->total < $max)
                        $return_arr[] = $one_artist;
                }else{
                    if($min >0 && $one_artist->followers->total > $min){
                        $return_arr[] = $one_artist;
                    }
                    elseif($max >0 && $one_artist->followers->total < $max){
                        $return_arr[] = $one_artist;
                    }
                }
            }

        }
        return $return_arr;
    }

    public function getTrack($id){
        $api = $this->prepareApi();
        return $api->getTrack($id);
    }

    public function getArtist($id){
        $api = $this->prepareApi();
        return $api->getArtist($id);
    }

    public function getArtistAlbums($id){
        $api = $this->prepareApi();
        return $api->getArtistAlbums($id);
    }

    public function getArtistRelatedArtists($id){
        $api = $this->prepareApi();
        return $api->getArtistRelatedArtists($id);
    }

    public function getArtistTopTracks($id,$country = "TR"){
        $api = $this->prepareApi();
        return $api->getArtistTopTracks($id,['country' => $country]);
    }


    public function getAlbum($id){
        $api = $this->prepareApi();
        return $api->getAlbum($id);
    }

    public function getAlbumTracks($id){
        $api = $this->prepareApi();
        return $api->getAlbumTracks($id);
    }

    public function getPlaylist($id){
        $api = $this->prepareApi();
        return $api->getPlaylist($id);
    }

    public function test(){
        $api = $this->prepareApi();
        $result = $api->getPlaylist("08hpqbgt5B7JL6r1ocJqEd");
        return response()->json($result);
    }
}
