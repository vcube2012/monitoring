<?php

use App\Jobs\MonitoringTon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Concurrency;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('monitoring_ton', function () {
    $project = \App\Models\Project::query()->where('network' , 'ton')->get();
    foreach ($project as $item) {
        MonitoringTon::dispatchSync($item->wallet , $item->id , $item->created_at->unix());
    }
});

Artisan::command('solana', function () {
   $seed = \Attestto\SolanaPhpSdk\Keypair::generate();
   dump($seed->getPublicKey()->toBase58());
});

Artisan::command('delete', function () {
   \App\Models\Transaction::query()->delete();
   \App\Models\Request::query()->delete();
});


Schedule::command('monitoring_ton')->everyTwoMinutes();
