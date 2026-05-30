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

require __DIR__.'/auth.php';
