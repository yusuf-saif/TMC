<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        $upcoming = Event::published()->upcoming()->get();
        $past = Event::published()->past()->limit(10)->get();
        return view('events.index', compact('upcoming','past'));
    }

    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        if ($event->status !== 'published') abort(404);
        $user = auth()->user();
        $userRsvp = $event->rsvps()->where('user_id', $user->id)->first();
        return view('events.show', compact('event','userRsvp'));
    }
}
