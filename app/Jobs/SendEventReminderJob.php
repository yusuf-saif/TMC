<?php

namespace App\Jobs;

use App\Mail\EventReminderMail;
use App\Models\Event;
use App\Models\EventRsvp;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEventReminderJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $eventId;

    public function __construct(int $eventId)
    {
        $this->eventId = $eventId;
    }

    public function handle(): void
    {
        $event = Event::find($this->eventId);
        if (! $event || $event->status !== 'published') return;
        EventRsvp::where('event_id', $event->id)->where('status','registered')
            ->with('user')->chunkById(500, function ($rsvps) use ($event) {
                foreach ($rsvps as $rsvp) {
                    if ($rsvp->user) {
                        Mail::to($rsvp->user->email)->queue(new EventReminderMail($event, $rsvp->user));
                    }
                }
            });
    }
}
