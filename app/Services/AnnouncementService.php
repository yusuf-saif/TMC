<?php

namespace App\Services;

use App\Mail\AnnouncementMail;
use App\Models\Announcement;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AnnouncementService
{
    public function create(array $data, User $admin): Announcement
    {
        return DB::transaction(function () use ($data, $admin) {
            $data['created_by'] = $admin->id;
            $data['updated_by'] = $admin->id;
            $a = Announcement::create($data);
            $this->log($admin, 'created_announcement', $a);
            return $a;
        });
    }

    public function update(Announcement $a, array $data, User $admin): Announcement
    {
        return DB::transaction(function () use ($a, $data, $admin) {
            $data['updated_by'] = $admin->id;
            $a->update($data);
            $this->log($admin, 'updated_announcement', $a);
            return $a;
        });
    }

    public function publishNow(Announcement $a, User $admin): Announcement
    {
        return DB::transaction(function () use ($a, $admin) {
            $a->status = 'published';
            $a->published_at = now();
            $a->save();
            $this->log($admin, 'published_announcement', $a);
            if ($a->send_email) {
                $this->emailToApprovedMembers($a);
            }
            return $a;
        });
    }

    public function archive(Announcement $a, User $admin): Announcement
    {
        return DB::transaction(function () use ($a, $admin) {
            $a->status = 'archived';
            $a->save();
            $this->log($admin, 'archived_announcement', $a);
            return $a;
        });
    }

    public function publishDue(): int
    {
        $due = Announcement::where('status','scheduled')
            ->whereNotNull('publish_at')
            ->where('publish_at','<=', now())
            ->get();
        $count = 0;
        foreach ($due as $a) {
            $a->status = 'published';
            $a->published_at = now();
            $a->save();
            $this->log($a->updater ?? $a->creator ?? User::first(), 'published_announcement', $a);
            if ($a->send_email) {
                $this->emailToApprovedMembers($a);
            }
            $count++;
        }
        return $count;
    }

    protected function emailToApprovedMembers(Announcement $a): void
    {
        User::where('status','approved')->chunkById(500, function($users) use ($a){
            foreach ($users as $u) {
                Mail::to($u->email)->queue(new AnnouncementMail($a));
            }
        });
    }

    protected function log(User $admin = null, string $action, Announcement $a): void
    {
        AuditLog::create([
            'admin_id' => $admin?->id,
            'user_id' => $admin?->id,
            'action' => $action,
            'metadata' => [
                'id' => $a->id,
                'title' => $a->title,
                'status' => $a->status,
            ],
        ]);
    }
}
