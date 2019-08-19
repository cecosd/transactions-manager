<?php

namespace App\Services;

use App\Transaction;

class TransactionService
{
    public function getAllTransactions()
    {
        $transactions = Transaction::with([
            'transactionAccount' => function($q){
                $q->with(['user'])->get();
            }
        ])->get();

        return $transactions;
    }
}