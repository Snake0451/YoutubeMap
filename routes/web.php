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

Route::get('/videos', function () {
    return view('videos');
});
Route::get('/grabVideos', 'VideoController@retrieveDataFromVideos');

Route::middleware('admin')->prefix('/admin')->group(function () {
    Route::get('/', function(){
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/listVideos', 'VideoController@listVideos')->name('listVideos');
    Route::get('/addVideo', 'VideoController@addVideoForm');
    Route::get('/updateVideo', 'VideoController@updateVideoForm')->name('updateVideoFrom');
    Route::post('/addVideo','VideoController@addVideo')->name('addVideo');
    Route::post('/updateVideo', 'VideoController@updateVideo')->name('updateVideo');
    Route::post('/deleteVideo', 'VideoController@deleteVideo')->name('deleteVideo');
    Route::get('/listEvents', 'EventController@index')->name('listEvents');
    Route::get('/addEvent', function () {
        return view('admin.addEvent');
    });
    Route::post('/addEvent', 'EventController@addEvent');
    Route::post('/deleteEvent', 'EventController@deleteEvent')->name('deleteEvent');
    Route::get('/addComment/{id}', 'CommentController@addCommentForm');
    Route::get('/listComments/{id}', 'CommentController@listVideoComments');
    Route::post('/deleteComment', 'CommentController@handleDelete');
});
Route::get('/videoComments/{id}', 'CommentController@videoComments');
Route::post('/addComment', 'CommentController@handleAdd');
Route::post('/updateComment/{id}', 'CommentController@updateComment');