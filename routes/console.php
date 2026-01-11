<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Envoyer un rappel chaque lundi matin à 8h
Schedule::call(function () {
    // Logique pour envoyer un mail ou une notification interne
})->weeklyOn(1, '08:00');