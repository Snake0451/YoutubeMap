<?php

namespace App\Http\Controllers;

use Request;
use App\Video;
use App\Event;
use App\Emotion;
use Carbon\Carbon;
use \Illuminate\Database\QueryException;

class VideoController extends Controller
{
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
            $videosResponse = VideoController::getVideos($_POST['lat'] . ',' . $_POST['lng']);
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
        $video = new Video();
        
        try
        {
            $youtubeVideo = $youtube->videos->listVideos('snippet, recordingDetails', array('id' => $input['id']));
            if(!count($youtubeVideo['items']))
                return redirect()->back()->with('message', 'No video with such ID found on Youtube.');
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('message', 'No video with such ID found on Youtube.');
        }
        
        $video->youtube_id = $input['id'];

        if(isset($input['title']))
        {
            $video->title = $input['title'];
        }
        else
        {
            if(isset($youtubeVideo['items'][0]['snippet']['title']))
                $video->title = $youtubeVideo['items'][0]['snippet']['title'];
            else
                redirect()->back()->with('message', 'No video with such ID found on Youtube.');
        }

        if(isset($input['description']))
        {
            $video->description = $input['description'];
        }
        else
        {
            if(isset($youtubeVideo['items'][0]['snippet']['description']))
                $video->description = $youtubeVideo['items'][0]['snippet']['description'];
            else
                return redirect()->back()->with('message', 'No video with such ID found on Youtube.');
        }

        if(isset($input['event_id']))
        {
            $video->event_id = $input['event_id'];
        }

        if(isset($input['latitude']))
        {
            $video->latitude = $input['latitude'];
        }
        else
        {
            if(isset($youtubeVideo['items'][0]['recordingDetails']['location']['latitude']))
                $video->latitude = $youtubeVideo['items'][0]['recordingDetails']['location']['latitude'];
            else
                return redirect()->back()->with('message', 'This video doesn\'t has geolocation. Please, specify it.');
        }

        if(isset($input['longitude']))
        {
            $video->longitude = $input['longitude'];
        }
        else
        {
            if(isset($youtubeVideo['items'][0]['recordingDetails']['location']['longitude']))
            $video->longitude = $youtubeVideo['items'][0]['recordingDetails']['location']['longitude'];
            else
                return redirect()->back()->with('message', 'This video doesn\'t has geolocation. Please, specify it.');
        }

        if(isset($_POST['emotion_id']))
            $video->emotion_id = $_POST['emotion_id'];
        else
            return redirect('/admin/listVideos')->with('message', 'Please, specify emotion of the video');

            $video->time_published = Carbon::parse($youtubeVideo['items'][0]['snippet']['publishedAt']);
            $video->author = $youtubeVideo['items'][0]['snippet']['channelTitle'];

        try
        {
            $video->save();
        }
        catch(QueryException $e)
        {
            return redirect()->back()->with('message', $e->getMessage());
        }
        
        
        return redirect()->back()->with('message', 'Video has been added!');
    }

    public function listVideos ()
    {
        $videos = Video::paginate(20);
        if (session()->has('message'))
            return view('admin.listVideos')->with('videos', $videos)->with('message', session('message'));
        return view('admin.listVideos')->with('videos', $videos);
    }

    public function updateVideo () 
    {
        // $client = new \Google_Client();
        // $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        // $youtube = new \Google_Service_YouTube($client);
    
        $video = Video::find($_POST['id']);

        if(isset($_POST['title']) && $_POST['title'] != '')
            $video->title = $_POST['title'];
            
        if(isset($_POST['description'])  && $_POST['description'] != '')
            $video->description = $_POST['description'];

        if(isset($_POST['latitude'])  && $_POST['latitude'] != '')
            $video->latitude = $_POST['latitude'];

        if(isset($_POST['event_id'])  && $_POST['event_id'] != '')
            if($_POST['event_id'] == 'No event')
                $video->event_id = null;
            else
                $video->event_id = $_POST['event_id'];
        
        if(isset($_POST['longitude'])  && $_POST['longitude'] != '')
            $video->longitude = $_POST['longitude'];

        $video->save();
        return redirect('/admin/listVideos');
    }

    public function updateVideoForm () 
    {
        $video = Video::find($_GET['id']);
        $events = Event::all();
        if(isset($video->event_id))
        {
            $event_list = array_add(array(), $video->event_id, $events->firstWhere('id', $video->event_id)->title);
            $event_list = array_add($event_list, 'No event', 'No event');
            foreach($events->except($video->event_id) as $event)
            {
                $event_list = array_add($event_list, $event->id, $event->title);
            }
        }
        else
        {
            $event_list = array_add(array(), 'No event', 'No event');
            foreach($events as $event)
            {
                $event_list = array_add($event_list, $event->id, $event->title);
            }
        }
        if (session()->has('message'))
            return view('admin.updateVideo')->with('video', Video::find($_GET['id']))->with('event_list', $event_list)->with('message', session('message'));
        return view('admin.updateVideo')->with('video', Video::find($_GET['id']))->with('event_list', $event_list);
    }

    public function deleteVideo ()
    {
        $video = Video::find($_POST['id']);
        $video->delete();
        return redirect('/admin/listVideos/');
    }

    public function addVideoForm () 
    {
        $events = Event::all();
        $emotions = Emotion::all();

        $event_list = array(null=>'No event');
        $emotions_list = array();
        foreach($events as $event)
        {
            $event_list = array_add($event_list, $event->id, $event->title);
        }

        foreach($emotions as $emotion)
        {
            $emotions_list = array_add($emotions_list, $emotion->id, $emotion->name);
        }

        if (session()->has('message'))
            return view('admin.addVideo')
                ->with('event_list', $event_list)
                ->with('emotions_list', $emotions_list)
                ->with('message', session('message'));
        return view('admin.addVideo')
            ->with('event_list', $event_list)
            ->with('emotions_list', $emotions_list);
    }
}
