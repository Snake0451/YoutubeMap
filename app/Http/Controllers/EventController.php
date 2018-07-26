<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Video;
use \Illuminate\Database\QueryException;


class EventController extends Controller
{
    public function index ()
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

    public function addEvent ()
    {
        if(!isset($_POST['title']))
            return view('admin.addEvent')->with('message', 'Please, specify title of event.');
        try
        {
            Event::create(array(
                'title' => $_POST['title'],
            ));
        }
        catch(QueryException $e)
        {
            if($e->getCode() == 23000)
                return view('admin.addEvent')->with('message', 'Event with such ID already in database');
            else
                return view('admin.addEvent')->with('message', $e->getMessage());
        }
        return redirect()->route('listEvents');
    }

    public function deleteEvent () 
    {
        $event = Event::find($_POST['id']);
        $event->delete();
        return redirect()->route('listEvents');
    }
}
