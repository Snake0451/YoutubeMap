<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\YoutubeController;
use Mapper;

class mapController extends Controller
{
    public function renderMap()
    {
        $videosResponse = YoutubeController::getVideos('40.7127753,-74.0059728');
        $videos = array();
        foreach ($videosResponse['items'] as $videoResult)
        {
            $videos = array_merge($videos, array([
                                                'lat' => $videoResult['recordingDetails']['location']['latitude'], 
                                                'long' => $videoResult['recordingDetails']['location']['longitude']], 
                                                'title' => $videoResult['snippet']['title'], 
                                                'url'=>'https://www.youtube.com/watch?v='.$videoResult['url']
                                            ));
        }
        return view('videos')->with('videos', $videos);
    }
}
