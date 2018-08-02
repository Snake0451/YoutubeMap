<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Video;
use App\Event;
use App\Emotion;
use App\User;
use \Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\YoutubeController;
use App\Http\Requests\addVideoRequest;

class VideoController extends Controller
{
    public function addVideo (addVideoRequest $request)
    {

        $validated = $request->validated();
        $input = $request->all();
        
        $video = new Video;
        $video->getVideo($input['id']);

        if($input['event_id']==-1)
            $video->event_id = null;
        else
            $video->event_id = $input['event_id'];

            $video->emotion_id = $input['emotion_id'];
        try
        {
            $video->save();
        }
        catch(QueryException $e)
        {
            return redirect()->back()->with('message', $e->getMessage());
        }

        if($video->latitude == null || $video->longitude == null)
            return redirect('admin/video/' . $video->id)->with('message', 'Video doesn\'t has geolocation. Please, specify it, if you want it to be visible on map!');

        return redirect('admin/video/' . $video->id)->with('message', 'Video has been added!');
    }

    public function listVideos ()
    {
        $videos = Video::paginate(10);
        $events = Event::all();
        $emotions = Emotion::all();

        $event_list = array(-1=>'No event');
        $emotion_list = array();
        foreach($events as $event)
        {
            $event_list = array_add($event_list, $event->id, $event->title);
        }

        foreach($emotions as $emotion)
        {
            $emotion_list = array_add($emotion_list, $emotion->id, $emotion->name);
        }
            if (session()->has('message'))
            return view('admin.listVideos')
            ->with('videos', $videos)
            ->with('event_list', $event_list)
            ->with('emotion_list', $emotion_list)
            ->with('message', session('message'));
        return view('admin.listVideos')
            ->with('videos', $videos)
            ->with('event_list', $event_list)
            ->with('emotion_list', $emotion_list);
    }

    public function updateVideo ($id, Request $request) 
    {
        $input = $request->all();
          $video = Video::find($id);

        if(isset($input['title']) && $input['title'] != '')
            $video->title = $input['title'];
            
        if(isset($input['description'])  && $input['description'] != '')
            $video->description = $input['description'];

        if(isset($input['latitude'])  && $input['latitude'] != '')
            $video->latitude = $input['latitude'];

        if(isset($input['event_id'])  && $input['event_id'] != '')
            if($input['event_id'] == 'No event')
                $video->event_id = null;
            else
                $video->event_id = $input['event_id'];

        if(isset($input['emotion_id'])  && $input['emotion_id'] != '')
            $video->emotion_id = $input['emotion_id'];

        if(isset($input['longitude'])  && $input['longitude'] != '')
            $video->longitude = $input['longitude'];

        $video->save();
        return redirect('/admin/video/')->with('message', 'Video has been updated');
    }

    public function showVideo ($id) 
    {
        $video = Video::find($id);
        if(!$video)
            return abort(404);
        $events = Event::all();
        $emotions = Emotion::all();

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
            $event_list = array_add(array(), -1, 'No event');
            foreach($events as $event)
            {
                $event_list = array_add($event_list, $event->id, $event->title);
            }
        }

        $emotions_list = array_add(array(), $video->emotion_id, $emotions->firstWhere('id', $video->emotion_id)->name);
        foreach($emotions->except($video->emotion_id) as $emotion)
        {
            $emotions_list = array_add($emotions_list, $emotion->id, $emotion->name);
        }

        $video = Video::find($id);

        $comments = $video->comments()->get();
            foreach($comments as $comment)
            {
                $comment->author = User::find($comment->user_id)->name;
            }

        if (session()->has('message'))
            return view('admin.showVideo')
                ->with('video', $video)
                ->with('event_list', $event_list)
                ->with('emotion_list', $emotions_list)
                ->with('comments', $comments)
                ->with('message', session('message'));

        return view('admin.showVideo')
            ->with('video', $video)
            ->with('comments', $comments)
            ->with('event_list', $event_list)
            ->with('emotion_list', $emotions_list);
    }

    public function deleteVideo ($id)
    {
        $video = Video::find($id);
        if(!$video)
            return abort(404);
        $video->delete();
        return redirect('/admin/video/')->with('message', 'Video ' . $id . ' has been deleted.');
    }
}
