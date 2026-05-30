<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler: publish due announcements every minute
Schedule::call(function(){
    app(\App\Services\AnnouncementService::class)->publishDue();
})->everyMinute();

