<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Setting the schedule
$scheduleMethod = config('prezet.index_schedule', 'everyThirtyMinutes');
$command = Schedule::command('prezet:index');

if (method_exists($command, $scheduleMethod)) {
    $command->{$scheduleMethod}();
} else {
    // fallback
    $command->everyThirtyMinutes();
}
