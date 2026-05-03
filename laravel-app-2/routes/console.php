<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan cronjob tiap malam untuk mengubah status event yang sudah lewat menjadi selesai
Schedule::command('events:auto-complete')->daily();

