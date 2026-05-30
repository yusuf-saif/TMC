<?php

namespace App\Services;

use App\Mail\MemberApprovedMail;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MembershipService
{
    public function approve(User $user, User $admin, ?string $reason = null): User
    {
        return DB::transaction(function () use ($user, $admin, $reason) {
            if (! $user->membership_number) {
                $user->membership_number = $this->generateMembershipNumber();
            }

            $user->status = 'approved';
            $user->approved_at = Carbon::now();
            $user->rejected_at = null;
            $user->rejection_reason = null;
            $user->save();

            AuditLog::create([
                'admin_id' => $admin->id,
                'user_id' => $user->id,
                'action' => 'approved',
                'reason' => $reason,
            ]);

            Mail::to($user->email)->send(new MemberApprovedMail($user));

            return $user;
        });
    }

    public function reject(User $user, User $admin, string $reason): User
    {
        return DB::transaction(function () use ($user, $admin, $reason) {
            $user->status = 'rejected';
            $user->rejected_at = Carbon::now();
            $user->rejection_reason = $reason;
            $user->save();

            AuditLog::create([
                'admin_id' => $admin->id,
                'user_id' => $user->id,
                'action' => 'rejected',
                'reason' => $reason,
            ]);

            return $user;
        });
    }

    public function suspend(User $user, User $admin, ?string $reason = null): User
    {
        return DB::transaction(function () use ($user, $admin, $reason) {
            $user->status = 'suspended';
            $user->save();

            AuditLog::create([
                'admin_id' => $admin->id,
                'user_id' => $user->id,
                'action' => 'suspended',
                'reason' => $reason,
            ]);

            return $user;
        });
    }

    public function reactivate(User $user, User $admin, ?string $reason = null): User
    {
        return DB::transaction(function () use ($user, $admin, $reason) {
            if (! $user->membership_number) {
                $user->membership_number = $this->generateMembershipNumber();
            }

            $user->status = 'approved';
            if (! $user->approved_at) {
                $user->approved_at = Carbon::now();
            }
            $user->save();

            AuditLog::create([
                'admin_id' => $admin->id,
                'user_id' => $user->id,
                'action' => 'reactivated',
                'reason' => $reason,
            ]);

            return $user;
        });
    }

    public function generateMembershipNumber(): string
    {
        $year = Carbon::now()->format('Y');
        $prefix = "TMC-{$year}-";

        $last = User::whereNotNull('membership_number')
            ->where('membership_number', 'like', $prefix.'%')
            ->orderByDesc('membership_number')
            ->value('membership_number');

        $seq = 0;
        if ($last) {
            $parts = explode('-', $last);
            $seq = (int)($parts[2] ?? 0);
        }
        $next = $seq + 1;

        return sprintf('%s%04d', $prefix, $next);
    }
}
