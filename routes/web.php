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
    Route::group(['prefix'=>'/video'], function ()
    {
        Route::get('/', 'Admin\VideoController@listVideos')->name('listVideos');
        Route::get('/{id}', 'Admin\VideoController@showVideo')->name('showVideo');
        Route::post('/', 'Admin\VideoController@addVideo')->name('addVideo');
        Route::put('/{id}', 'Admin\VideoController@updateVideo')->name('showVideo');
        Route::delete('/{id}', 'Admin\VideoController@deleteVideo')->name('deleteVideo');

        Route::group(['prefix' => '/{video_id}/comment'], function () {
            Route::get('/', 'Admin\CommentController@listComments')->name('listComments');
            Route::get('/{id}', 'Admin\CommentController@showComment')->name('showComment');
            Route::post('/', 'Admin\CommentController@addComment')->name('addComment');
            Route::put('/{id}', 'Admin\CommentController@updateComment')->name('updateComment');
            Route::delete('/{id}', 'Admin\CommentController@deleteComment')->name('deleteComment');
        });
    });

    Route::group(['prefix' => '/event'], function () 
        {
            Route::get('/', 'Admin\EventController@listEvents')->name('listEvents');
            Route::get('/{id}', 'Admin\EventController@showEvent')->name('showEvent');
            Route::post('/', 'Admin\EventController@addEvent')->name('addEvent');
            Route::put('/{id}', 'Admin\EventController@updateEvent')->name('updateEvent');
            Route::delete('/{id}', 'Admin\EventController@deleteEvent')->name('deleteEvent');
        });
});
Route::get('/videoComments/{id}', 'CommentController@videoComments');
Route::post('/addComment', 'CommentController@handleAdd');
Route::post('/updateComment/{id}', 'CommentController@updateComment');
Route::get('/v/{id}', 'Admin\YoutubeController@getVideoData');