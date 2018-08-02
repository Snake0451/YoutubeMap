<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\addEventRequest;
use App\Event;
use App\Video;
use \Illuminate\Database\QueryException;


class EventController extends Controller
{
    public function listEvents ()
    {
        $events = Event::all();
        foreach($events as $event)
        {
            $event->count = $event->videos()->count();
        }
        if(isset($message))
            return view('admin.listEvents')->with(['events' => $events, 'message' => $message]);
        return view('admin.listEvents')->with('events', $events);
    }

    public function showEvent ($id)
    {
        $event = Event::find($id);
        $videos = $event->videos()->get();

        if(isset($message))
            return view('admin.showEvent')->with(['event' => $event, 'message' => $message, 'videos' => $videos]);
        return view('admin.showEvent')->with(['event' => $event, 'videos' => $videos]);
    }

    public function addEvent (addEventRequest $request)
    {
        $validated = $request->validated();
        $input = $request->all();

        if(!isset($input['title']))
            return view('admin.addEvent')->with('message', 'Please, specify title of event.');
        try
        {
            Event::create(array(
                'title' => $input['title'],
            ));
        }
        catch(QueryException $e)
        {
            if($e->getCode() == 23000)
                return view('admin.addEvent')->with('message', 'Event with such ID already in database');
            else
                return view('admin.addEvent')->with('message', $e->getMessage());
        }
        return redirect()->route('listEvents')->with('message', 'Event has been added');
    }

    public function deleteEvent ($id) 
    {
        $event = Event::find($id);
        $event->delete();
        return redirect()->route('listEvents')->with('message', 'Event has been added');
    }
}
