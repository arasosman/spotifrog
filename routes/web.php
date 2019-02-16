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


Route::resource('/register', 'Admin\HomeController@index');

Route::group(['middleware' => ['throttle:60,1']], function () {

    Route::get('/', 'Front\HomeController@index')->name('home');
    Route::get('/detail/{type}/{id}', 'Front\HomeController@detail')->name('detail');
    Route::post('/search_data', 'SpotifyApiController@search')->name('search');

    Route::get('/test','SpotifyApiController@test');

});


Route::group(['middleware' => ['throttle:60,1'], 'prefix' => 'admin'], function () {

    Route::get('/', 'Admin\HomeController@index');
    Route::get('/my_profile', 'Admin\ProfileController@getProfile');
    Route::post('/my_profile', 'Admin\ProfileController@setProfile');

});


