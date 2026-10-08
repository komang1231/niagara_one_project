<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('karyawan:terapkan-perubahan')
    ->dailyAt('00:05');

Schedule::command('karyawan:terapkan-perubahan')
    ->dailyAt('23:45');

Schedule::command('saldo-cuti:generate')
    ->yearlyOn(1, 1, '00:10');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');