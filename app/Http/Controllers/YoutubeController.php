<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    static public function getVideos($location)
    {
        if (isset($_GET['maxResults'])) {

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
    }

    public function retrieveDataFromVideos()
    {
        $videosResponse = YoutubeController::getVideos('40.7127753,-74.0059728');
        $videos = array();
        foreach ($videosResponse['items'] as $videoResult)
        {
            $videos = array_merge($videos, array([
                                                'lat' => $videoResult['recordingDetails']['location']['latitude'], 
                                                'long' => $videoResult['recordingDetails']['location']['longitude'], 
                                                'title' => $videoResult['snippet']['title'], 
                                                'url'=>'https://www.youtube.com/watch?v='.$videoResult['id']]
                                            ));
        }
        return view('videos')->with('videos', $videos);
    }
}
