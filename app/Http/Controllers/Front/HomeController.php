<?php

namespace App\Http\Controllers\Front;

use App\Helpers\ScheduledTasks;
use App\Helpers\SpotifyApi;
use App\Http\Controllers\Controller;
use App\Model\Playlist;
use App\Model\PlaylistItem;
use App\Model\Schedule;
use voku\helper\HtmlDomParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request){

        return view('pages.front.home');
    }

}
