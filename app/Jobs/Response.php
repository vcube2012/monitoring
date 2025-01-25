<?php

namespace App\Jobs;

use App\Models\Request;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use League\Uri\Http;

class Response implements ShouldQueue
{
    use Queueable;

    private ?Transaction $transaction;

    /**
     * Create a new job instance.
     */
    public function __construct(public id $transaction_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->transaction = Transaction::query()->where('id', $this->transaction_id)->first();
        $client = \Illuminate\Support\Facades\Http::acceptJson();

        $response = $client->{$this->transaction->project->method}(query: $this->transaction->body, url: $this->transaction->project->webhook);
        $json = $response->body();
        try {
            Request::query()->create([
                'project_id' => $this->transaction->project_id,
                'transaction_id' => $this->transaction->id,
                'status' => $response->status(),
                'response' => $json
            ]);
        } catch (\Throwable $e) {
            Request::query()->create([
                'project_id' => $this->transaction->project_id,
                'transaction_id' => $this->transaction->id,
                'status' => 12,
                'response' => json_encode(['message' => $e->getMessage()])
            ]);
        }

    }
}
