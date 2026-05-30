<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function create(array $data, User $admin): Event
    {
        return DB::transaction(function () use ($data, $admin) {
            $data['created_by'] = $admin->id;
            $data['updated_by'] = $admin->id;
            $event = Event::create($data);
            $this->log($admin, 'created_event', $event);
            return $event;
        });
    }

    public function update(Event $event, array $data, User $admin): Event
    {
        return DB::transaction(function () use ($event, $data, $admin) {
            $data['updated_by'] = $admin->id;
            $event->update($data);
            $this->log($admin, 'updated_event', $event);
            return $event;
        });
    }

    public function publish(Event $event, User $admin): Event
    {
        return DB::transaction(function () use ($event, $admin) {
            $event->status = 'published';
            $event->updated_by = $admin->id;
            $event->save();
            $this->log($admin, 'published_event', $event);
            return $event;
        });
    }

    public function cancel(Event $event, User $admin): Event
    {
        return DB::transaction(function () use ($event, $admin) {
            $event->status = 'cancelled';
            $event->updated_by = $admin->id;
            $event->save();
            $this->log($admin, 'cancelled_event', $event);
            return $event;
        });
    }

    public function complete(Event $event, User $admin): Event
    {
        return DB::transaction(function () use ($event, $admin) {
            $event->status = 'completed';
            $event->updated_by = $admin->id;
            $event->save();
            $this->log($admin, 'completed_event', $event);
            return $event;
        });
    }

    protected function log(User $admin, string $action, Event $event): void
    {
        AuditLog::create([
            'admin_id' => $admin->id,
            'user_id' => $admin->id,
            'action' => $action,
            'metadata' => [
                'event_id' => $event->id,
                'title' => $event->title,
                'status' => $event->status,
            ],
        ]);
    }
}
