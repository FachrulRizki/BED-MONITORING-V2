<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$interval = (int) config('aplicare.sync_interval', 5);

Schedule::command('bed:sync-mjkn')
    ->cron("*/{$interval} * * * *")
    ->when(fn () => (bool) config('aplicare.enabled'));
