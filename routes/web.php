<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'landing');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'approved'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('/pending-approval', 'auth.pending-approval')->name('pending-approval');

Route::view('/member-card', 'members.card')
    ->middleware(['auth','verified','approved'])
    ->name('member-card');

require __DIR__.'/auth.php';
