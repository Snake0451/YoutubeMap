<?php

namespace App\Http\Controllers;

use Request;
use App\Video;
use Carbon\Carbon;
use \Illuminate\Database\QueryException;

class YoutubeController extends Controller
{
    public function index()
    {

    }

    static public function fetchVideos($locations)
    {
        $client = new \Google_Client();
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));

        // Define an object that will be used to make all API requests.
        $youtube = new \Google_Service_YouTube($client);
        try {
            // Call the search.list method to retrieve results matching the specified
            // query term.
            $searhArgs = array(
                'type' => 'video',
                'location' =>  $location, 
                'locationRadius' => '50km',
            );

            //search query for videos
            if(isset($_POST['q']))
                $searhArgs=array_merge($searhArgs, array('q' => $_POST['q']));

            if(isset($_POST['locationRadius']))
                $searhArgs=array_merge($searhArgs, array('locationRadius' => $_POST['locationRadius']));
            else
                $searhArgs=array_merge($searhArgs, array('locationRadius' => '20km'));

            if(isset($_POST['maxResults']))
                $searhArgs=array_merge($searhArgs, array('maxResults' => $_POST['maxResults']));
            else
                $searhArgs=array_merge($searhArgs, array('maxResults' => 50));

            $searchResponse = $youtube->search->listSearch('id, snippet', $searhArgs);
            $videoResults = array();
            # Merge video ids
            foreach ($searchResponse['items'] as $searchResult) {
                array_push($videoResults, $searchResult['id']['videoId']);
            }
            $videoIds = join(',', $videoResults);

            # Call the videos.list method to retrieve location details for each video.
            $videosResponse = $youtube->videos->listVideos('snippet, recordingDetails', array(
                'id' => $videoIds,
            ));
            }  
            catch (Google_Service_Exception $e) {
                return 0;
            } 
            catch (Google_Exception $e) {
                return 0;
            }
            
            return $videosResponse;
    }

    public function fetchVideoData($location)
    {
            $videosResponse = YoutubeController::getVideos($_POST['lat'] . ',' . $_POST['lng']);
            $videos = array();
            foreach ($videosResponse['items'] as $videoResult)
            {
                $videos = array_merge($videos, array([
                                                    'lat' => $videoResult['recordingDetails']['location']['latitude'], 
                                                    'lng' => $videoResult['recordingDetails']['location']['longitude'], 
                                                    'title' => $videoResult['snippet']['title'], 
                                                    'url'=>'https://www.youtube.com/watch?v='.$videoResult['id']],
                                                    'desc' => $videoResult['snippet']['title']
                                                ));
            }
            return view()->with('videos', $videos);
    }

    public function addVideo ()
    {
        $client = new \Google_Client();
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $youtube = new \Google_Service_YouTube($client);
        $input = Request::all();
        
        try
        {
            $youtubeVideo = $youtube->videos->listVideos('snippet, recordingDetails', array('id' => $input['id']));
        }
        catch(Exception $e)
        {
            return view('admin.addVideo')->with('message', 'No video with such ID found on Youtube.');
        }
        
        $args = array('youtube_id' => $input['id']);

        if(isset($input['title']))
        {
            $args = array_merge($args,array('title' => $input['title']));
        }
        else
        {
            if(isset($youtubeVideo['items'][0]['snippet']['title']))
                $args = array_merge($args,array('title' => $youtubeVideo['items'][0]['snippet']['title']));
            else
                return view('admin.addVideo')->with('message', 'Title of this video not found on Youtube');
        }

        if(isset($input['description']))
        {
            $args = array_merge($args,array('description' => $input['description']));
        }
        else
        {
            if(isset($youtubeVideo['items'][0]['snippet']['description']))
                $args = array_merge($args,array('description' => $youtubeVideo['items'][0]['snippet']['description']));
            else
                return view('admin.addVideo')->with('message', 'No description of this video found on Youtube');
        }

        if(isset($input['latitude']))
        {
            $args = array_merge($args,array('latitude' => $input['latitude']));
        }
        else{
            if(isset($youtubeVideo['items'][0]['recordingDetails']['location']['latitude']))
                $args = array_merge($args,array('latitude' => $youtubeVideo['items'][0]['recordingDetails']['location']['latitude']));
            else
                return view('admin.addVideo')->with('message', 'This video doesn\'t has geolocation. Please, specify it.');
        }

        if(isset($input['longitude']))
        {
            $args = array_merge($args, array('longitude' => $input['longitude']));
        }
        else
        {
            if(isset($youtubeVideo['items'][0]['recordingDetails']['location']['longitude']))
                $args = array_merge($args, array('longitude' => $youtubeVideo['items'][0]['recordingDetails']['location']['longitude']));
            else
                return view('admin.addVideo')->with('message', 'This video doesn\'t has geolocation. Please, specify it.');
        }

            $args = array_merge($args,array('time_published' => Carbon::parse($youtubeVideo['items'][0]['snippet']['publishedAt'])));
            $args = array_merge($args,array('author' => $youtubeVideo['items'][0]['snippet']['channelTitle']));

        try
        {
            Video::create($args);
        }
        catch(QueryException $e)
        {
            if($e->getCode() == 23000)
                return view('admin.addVideo')->with('message', 'Video with such ID already in database');
            else
                return view('admin.addVideo')->with('message', $e->getMessage());
        }
        
        return view('admin.addVideo')->with('message', 'Video has been added!');
    }

    public function listVideos ()
    {
        $videos = Video::paginate(20);
        return view('admin.listVideos')->with('videos', $videos);
    }

    public function updateVideo () 
    {
        // $client = new \Google_Client();
        // $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        // $youtube = new \Google_Service_YouTube($client);
    
        $video = Video::find($_POST['id']);

        if(isset($_POST['title']))
            $video->title = $_POST['title'];
        if(isset($_POST['description']))
            $video->description = $_POST['description'];

        if(isset($_POST['latitude']))
            $video->latitude = $_POST['latitude'];
        
        if(isset($_POST['longitude']))
            $video->latitude = $_POST['longitude'];

        $video->save();
        return redirect('/admin/listVideos');
    }

    public function updateVideoForm () 
    {
        $video = Video::find($_GET['id']);
        return view('admin.updateVideo')->with('video', Video::find($_GET['id']));
    }

    public function deleteVideo ()
    {
        
    }
}
