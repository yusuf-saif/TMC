<?php

namespace App\Services;

use App\Mail\RsvpConfirmationMail;
use App\Models\AuditLog;
use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\User;
use App\Jobs\SendEventReminderJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class RsvpService
{
    public function register(Event $event, User $user): EventRsvp
    {
        if ($event->status !== 'published') {
            throw new \RuntimeException('Event not open for RSVP');
        }
        if ($event->isFull()) {
            throw new \RuntimeException('Event capacity reached');
        }

        return DB::transaction(function () use ($event, $user) {
            $existing = EventRsvp::where('event_id', $event->id)->where('user_id', $user->id)->first();
            if ($existing && $existing->status === 'registered') {
                return $existing; // idempotent
            }

            $rsvp = EventRsvp::updateOrCreate(
                ['event_id' => $event->id, 'user_id' => $user->id],
                ['status' => 'registered', 'registered_at' => now(), 'attended_at' => null]
            );

            AuditLog::create([
                'admin_id' => $user->id,
                'user_id' => $user->id,
                'action' => 'event_rsvp_created',
                'metadata' => [ 'event_id' => $event->id, 'title' => $event->title ],
            ]);

            Mail::to($user->email)->queue(new RsvpConfirmationMail($event, $user));

            // Queue a reminder 24h before event start
            $remindAt = Carbon::parse($event->event_date)->copy()->subDay();
            if ($remindAt->isFuture()) {
                SendEventReminderJob::dispatch($event->id)->delay($remindAt);
            }

            return $rsvp;
        });
    }

    public function cancel(Event $event, User $user): void
    {
        DB::transaction(function () use ($event, $user) {
            $rsvp = EventRsvp::where('event_id', $event->id)->where('user_id', $user->id)->first();
            if ($rsvp) {
                $rsvp->status = 'cancelled';
                $rsvp->save();
                AuditLog::create([
                    'admin_id' => $user->id,
                    'user_id' => $user->id,
                    'action' => 'event_rsvp_cancelled',
                    'metadata' => [ 'event_id' => $event->id, 'title' => $event->title ],
                ]);
            }
        });
    }
}
