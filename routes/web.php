<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//auth default routings
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');


Route::resource('/register', 'HomeController@index');

Route::group(['middleware' => ['auth','throttle:60,1']], function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/detail/{type}/{id}', 'HomeController@detail')->name('detail');
    Route::post('/search_data', 'SpotifyApiController@search')->name('search');

    Route::get('/showToBeCrawl','CrawlerDataController@showCrawl');
    Route::get('/getCrawlData','CrawlerDataController@getData');
    Route::get('/crawler/cd','CrawlerDataController@getCrawlerData');

    Route::get('/unclaimedArtists','UnclaimedArtistsController@showPage');
    Route::get('/unclaimedArtists/ua','UnclaimedArtistsController@getData');

});
Route::get('/test','HomeController@test');



