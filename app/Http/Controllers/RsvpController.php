<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\RsvpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        try {
            app(RsvpService::class)->register($event, $request->user());
            return back()->with('status','RSVP confirmed.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(Request $request, string $slug): RedirectResponse
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        app(RsvpService::class)->cancel($event, $request->user());
        return back()->with('status','RSVP cancelled.');
    }
}
