<?php

use App\Jobs\MonitoringTon;
use App\Jobs\Response;
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



Schedule::command('monitoring_ton')->everyTwoMinutes();


Artisan::command('tests' , function (){
    $r = \App\Models\Transaction::query()->whereDoesntHave('requests')->get();
   foreach ($r as $item) {
       Response::dispatchSync($item->id);
   }
});

Schedule::command('tests')->everyTwoMinutes();


Artisan::command('test2' , function (){
    $r = \App\Models\Transaction::query()->whereDoesntHave('requests')->count();
    dd($r);
});
