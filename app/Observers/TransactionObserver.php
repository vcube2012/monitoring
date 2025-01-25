<?php

namespace App\Observers;

use App\Jobs\Response;
use App\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        Response::dispatch($transaction->id);
    }
}
