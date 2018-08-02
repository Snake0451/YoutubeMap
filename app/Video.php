<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Video extends Model
{
    protected $guarded = [];

    public function event() {
        return $this->belongsTo('App\Event');
    }

    public function emotion() {
        return $this->belongsTo('App\Emotion');
    }

    public function comments() {
        return $this->hasMany('App\Comment');
    }

    public function reactions() {
        return $this->hasMany('App\Reaction');
    }

    public function getVideo ($youtubeId)
    {
        try
        {
            $client = new \Google_Client();
            $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
            $youtube = new \Google_Service_YouTube($client);
            $youtubeVideo = $youtube->videos->listVideos('snippet, recordingDetails', array('id' => $youtubeId));

            if(!count($youtubeVideo['items']))
                return 0;

            $this->youtube_id = $youtubeId;
            $this->title = $youtubeVideo['items'][0]['snippet']['title'];
            $this->description = $youtubeVideo['items'][0]['snippet']['description'];
            
            $this->latitude = $youtubeVideo['items'][0]['recordingDetails']['location']['latitude'];
            $this->longitude = $youtubeVideo['items'][0]['recordingDetails']['location']['longitude'];

            $this->author = $youtubeVideo['items'][0]['snippet']['channelTitle'];

            $this->time_published = Carbon::parse($youtubeVideo['items'][0]['snippet']['publishedAt']);

            return $this;
        }
        catch(Exception $e)
        {
            return $e->getCode();
        }
    }
}
