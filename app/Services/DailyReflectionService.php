<?php

namespace App\Services;

use App\Mail\DailyReflectionMail;
use App\Models\AuditLog;
use App\Models\DailyReflection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DailyReflectionService
{
    public function create(array $data, User $admin): DailyReflection
    {
        return DB::transaction(function () use ($data, $admin) {
            $data['created_by'] = $admin->id;
            $data['updated_by'] = $admin->id;
            $reflection = DailyReflection::create($data);

            AuditLog::create([
                'admin_id' => $admin->id,
                'user_id' => $admin->id,
                'action' => 'created_daily_reflection',
                'metadata' => $this->meta($reflection),
            ]);

            return $reflection;
        });
    }

    public function update(DailyReflection $reflection, array $data, User $admin): DailyReflection
    {
        return DB::transaction(function () use ($reflection, $data, $admin) {
            $data['updated_by'] = $admin->id;
            $reflection->update($data);

            AuditLog::create([
                'admin_id' => $admin->id,
                'user_id' => $admin->id,
                'action' => 'updated_daily_reflection',
                'metadata' => $this->meta($reflection),
            ]);

            return $reflection;
        });
    }

    public function publish(DailyReflection $reflection, User $admin, bool $emailMembers = false): DailyReflection
    {
        return DB::transaction(function () use ($reflection, $admin, $emailMembers) {
            // Ensure only one published item per publish_date
            DailyReflection::where('publish_date', $reflection->publish_date)
                ->where('id', '!=', $reflection->id)
                ->where('status', 'published')
                ->update(['status' => 'archived']);

            $reflection->status = 'published';
            if (! $reflection->publish_date) {
                $reflection->publish_date = Carbon::today();
            }
            $reflection->updated_by = $admin->id;
            $reflection->save();

            AuditLog::create([
                'admin_id' => $admin->id,
                'user_id' => $admin->id,
                'action' => 'published_daily_reflection',
                'metadata' => $this->meta($reflection),
            ]);

            if ($emailMembers) {
                $this->emailToApprovedMembers($reflection);
            }

            return $reflection;
        });
    }

    public function archive(DailyReflection $reflection, User $admin): DailyReflection
    {
        return DB::transaction(function () use ($reflection, $admin) {
            $reflection->status = 'archived';
            $reflection->updated_by = $admin->id;
            $reflection->save();

            AuditLog::create([
                'admin_id' => $admin->id,
                'user_id' => $admin->id,
                'action' => 'archived_daily_reflection',
                'metadata' => $this->meta($reflection),
            ]);

            return $reflection;
        });
    }

    protected function emailToApprovedMembers(DailyReflection $reflection): void
    {
        User::where('status', 'approved')->chunkById(500, function ($users) use ($reflection) {
            foreach ($users as $user) {
                Mail::to($user->email)->queue(new DailyReflectionMail($reflection));
            }
        });
    }

    protected function meta(DailyReflection $r): array
    {
        return [
            'id' => $r->id,
            'title' => $r->title,
            'type' => $r->type,
            'publish_date' => optional($r->publish_date)->toDateString(),
            'status' => $r->status,
        ];
    }
}
