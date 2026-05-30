<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TmcCreateAdmin extends Command
{
    protected $signature = 'tmc:create-admin';
    protected $description = 'Create or update a TMC administrator user';

    public function handle(): int
    {
        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email.');
            return self::FAILURE;
        }

        if (! $password) {
            $this->error('Password cannot be empty.');
            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $name ?: ($user->name ?: 'Admin');
        $user->password = Hash::make($password);
        $user->email_verified_at = now();
        $user->status = 'approved';
        $user->is_admin = true;
        $user->remember_token = $user->remember_token ?: Str::random(10);
        $user->save();

        $this->info("Admin user ready: {$user->email}");

        return self::SUCCESS;
    }
}
