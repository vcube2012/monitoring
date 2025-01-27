<?php

namespace App\Jobs;

use App\Models\Request;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use League\Uri\Http;

class Response implements ShouldQueue
{
    use Queueable ,InteractsWithQueue;

    protected int $transaction_id;

    /**
     * Create a new job instance.
     */
    public function __construct( int $transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $transaction = Transaction::query()->where('id', $this->transaction_id)->first();
        try {
            $client = \Illuminate\Support\Facades\Http::acceptJson();
            $response = $client->{$transaction->project->method}(query: $transaction->body, url: $transaction->project->webhook);
            $json = $response->body();
            Request::query()->create([
                'project_id' => $transaction->project_id,
                'transaction_id' => $transaction->id,
                'status' => $response->status(),
                'response' => $json
            ]);
        } catch (\Throwable $e) {
            Request::query()->create([
                'project_id' => $transaction->project_id,
                'transaction_id' => $transaction->id,
                'status' => 12,
                'response' => json_encode(['message' => $e->getMessage()])
            ]);
        }

    }
}
