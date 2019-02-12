<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Model\Playlist;
use App\Model\PlaylistItem;
use App\User;
use voku\helper\HtmlDomParser;
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

        return view('pages.admin.home');
    }


}
