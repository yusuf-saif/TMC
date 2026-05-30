<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'landing');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'approved'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Events - member features
Route::middleware(['auth','verified','approved'])->group(function(){
    Route::get('/events', [\App\Http\Controllers\EventsController::class, 'index'])->name('events.index');
    Route::get('/events/{slug}', [\App\Http\Controllers\EventsController::class, 'show'])->name('events.show');
    Route::post('/events/{slug}/rsvp', [\App\Http\Controllers\RsvpController::class, 'store'])->name('events.rsvp');
    Route::post('/events/{slug}/cancel', [\App\Http\Controllers\RsvpController::class, 'cancel'])->name('events.rsvp.cancel');
    Route::get('/my-events', [\App\Http\Controllers\MyEventsController::class, 'index'])->name('events.mine');
});

Route::view('/pending-approval', 'auth.pending-approval')->name('pending-approval');

Route::view('/member-card', 'members.card')
    ->middleware(['auth','verified','approved'])
    ->name('member-card');

require __DIR__.'/auth.php';
