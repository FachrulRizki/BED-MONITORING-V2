<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

if (config('aplicare.enabled')) {
    Schedule::command('bed:sync-mjkn')
        ->everyMinutes(config('aplicare.sync_interval', 5));
}
