<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class MonitoringTon implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $wallet, public $project_id , public $created_at = 0)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $d = new \App\Services\Deposit($this->wallet);
        $d->all(function ($data) {
            if(data_get($data , 'created_at' ,1) < $this->created_at){
                return false;
            }
            try {
                Transaction::query()->create([
                    'project_id' => $this->project_id,
                    'signature' => $data['signature'],
                    'body' => $data,
                ]);
            } catch (UniqueConstraintViolationException) {

            exit(1);

            } catch (Throwable $e) {
                dump($e->getMessage());
            }
        });
    }
}
