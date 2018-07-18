<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    public function getVideos()
    {
        if (isset($_GET['maxResults'])) {
  /*
   * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
  * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
  * Please ensure that you have enabled the YouTube Data API for your project.
  */
  $DEVELOPER_KEY = env('YOUTUBE_API_KEY');

  $client = new \Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // Define an object that will be used to make all API requests.
  $youtube = new \Google_Service_YouTube($client);

  try {
    // Call the search.list method to retrieve results matching the specified
    // query term.
    $searhArgs = array(
        'type' => 'video',
        'location' =>  '40.7127753,-74.0059728', 
        'locationRadius' => '1000km',
        'maxResults' => $_GET['maxResults'],
    );
    if(isset($_GET['q']))
        $searhArgs=array_merge($searhArgs, array('q' => $_GET['q']));
    if(isset($_GET['locationRadius']))
        $searhArgs=array_merge($searhArgs, array('locationRadius' => $_GET['locationRadius']));
    else
        $searhArgs=array_merge($searhArgs, array('locationRadius' => '20km'));
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
        $videos = array();
     //Display the list of matching videos.
            foreach ($videosResponse['items'] as $videoResult) {
                $video .= $videoResult['snippet']['title'];
                $video .= $videoResult['recordingDetails']['location']['latitude'];
                $video .= $videoResult['recordingDetails']['location']['longitude'];
                array_push($videos, $video);
            }
        }  
        catch (Google_Service_Exception $e) {
            $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
      } catch (Google_Exception $e) {
            $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          }
    }
    }
}
