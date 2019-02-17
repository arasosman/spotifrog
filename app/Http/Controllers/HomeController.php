<?php

namespace App\Http\Controllers;

use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function test(){
        try{ $url = "https://open.spotify.com/artist/2yMN0IP20GOaN6q0p0zL5k";
            $content = file_get_contents($url);
            $dom = HtmlDomParser::str_get_html($content);
            $follow_arr = array();
            if($dom->find('section[class=bio]')){
                $bio[] = $dom->find('section[class=bio] div div span',0)->innertext();
                $bio[] = $dom->find('section[class=bio] div div span',1)->innertext();
            }


            print_r($bio);}
        catch (\Exception $e){echo $e->getMessage();}

    }
}
