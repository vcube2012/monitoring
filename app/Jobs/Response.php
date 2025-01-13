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

    /**
     * Create a new job instance.
     */
    public function __construct(public Transaction $transaction)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            $client = \Illuminate\Support\Facades\Http::acceptJson();

            $response = $client->{$this->transaction->project->method}(query: $this->transaction->body, url: $this->transaction->project->webhook);
            $json = $response->body();
            try{
                Request::query()->create([
                    'project_id' => $this->transaction->project_id,
                    'transaction_id' => $this->transaction->id,
                    'status' => $response->status(),
                    'response' => $json
                ]);
            }catch (\Throwable $e){
                Request::query()->create([
                    'project_id' => $this->transaction->project_id,
                    'transaction_id' => $this->transaction->id,
                    'status' => $e->getCode(),
                    'response' => json_encode(['message' => $e->getMessage()])
                ]);
            }

    }
}
