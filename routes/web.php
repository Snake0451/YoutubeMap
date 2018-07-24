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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin', function(){
    return view('admin/dashboard');
});
Route::get('/admin/listVideos', 'YoutubeController@listVideos');
Route::get('/admin/addVideo', function () {
    return view('admin/addVideo');
});
Route::get('/admin/deleteVideo', 'YoutubeController@deleteVideo');
Route::get('/videos', function () {
    return view('videos');
});
Route::get('/grabVideos', 'YoutubeController@retrieveDataFromVideos');
Route::get('/admin/updateVideo', 'YoutubeController@updateVideoForm');
Route::post('/admin/addVideo','YoutubeController@addVideo');
Route::post('/admin/updateVideo', 'YoutubeController@updateVideo');