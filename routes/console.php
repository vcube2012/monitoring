<?php

use App\Jobs\MonitoringTon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('monitoring_ton', function () {
    $project = \App\Models\Project::query()->where('network' , 'ton')->get();
    foreach ($project as $item) {
        MonitoringTon::dispatch($item->wallet , $item->id , $item->created_at->unix());
    }
});

Schedule::command('monitoring_ton')->everyMinute();
