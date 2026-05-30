<?php

namespace App\Http\Controllers;

use App\Models\EventRsvp;
use Illuminate\Http\Request;

class MyEventsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $upcoming = EventRsvp::with('event')
            ->where('user_id', $user->id)
            ->where('status','registered')
            ->whereHas('event', fn($q)=>$q->where('event_date','>=', now()))
            ->orderByRelation('event.event_date')
            ->get();

        $past = EventRsvp::with('event')
            ->where('user_id', $user->id)
            ->whereHas('event', fn($q)=>$q->where('event_date','<', now()))
            ->orderByDesc('event_id')
            ->limit(20)
            ->get();

        return view('events.mine', compact('upcoming','past'));
    }
}
