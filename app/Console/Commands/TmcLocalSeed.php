<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MembershipService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TmcLocalSeed extends Command
{
    protected $signature = 'tmc:local-seed';
    protected $description = 'Seed local admin, approved member, and pending member for development';

    public function handle(): int
    {
        $this->info('Seeding local TMC users...');

        // Admin
        $admin = User::firstOrNew(['email' => 'admin@tmcng.com']);
        $admin->name = 'Admin User';
        $admin->password = Hash::make('Password1');
        $admin->email_verified_at = now();
        $admin->status = 'approved';
        $admin->is_admin = true;
        $admin->remember_token = $admin->remember_token ?: Str::random(10);
        $admin->save();

        // Approved Member
        $member = User::firstOrNew(['email' => 'member@tmcng.com']);
        $member->name = 'Member User';
        $member->password = Hash::make('Password1');
        $member->email_verified_at = now();
        $member->status = 'approved';
        $member->is_admin = false;
        if (! $member->membership_number) {
            $member->membership_number = app(MembershipService::class)->generateMembershipNumber();
        }
        $member->remember_token = $member->remember_token ?: Str::random(10);
        $member->save();

        // Pending Member
        $pending = User::firstOrNew(['email' => 'pending@tmcng.com']);
        $pending->name = 'Pending User';
        $pending->password = Hash::make('Password1');
        $pending->email_verified_at = now();
        $pending->status = 'pending';
        $pending->is_admin = false;
        $pending->remember_token = $pending->remember_token ?: Str::random(10);
        $pending->save();

        $this->table(['Email','Password','is_admin','status'], [
            ['admin@tmcng.com','Password1','true','approved'],
            ['member@tmcng.com','Password1','false','approved'],
            ['pending@tmcng.com','Password1','false','pending'],
        ]);

        $this->info('Local TMC users seeded.');
        return self::SUCCESS;
    }
}
