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
        ])
        ->get()
        ->map(function($transaction){
            return [
                "id" => $transaction->id,
                "account_user_name" => $transaction->transactionAccount->user->name,
                "account_user_email" => $transaction->transactionAccount->user->email,
                "account_balance" => $transaction->transactionAccount->balance,
                "amount" => $transaction->amount,
                "type" => $transaction->type,
                "is_debit" => ($transaction->type == 'Debit'),
                "is_credit" => ($transaction->type == 'Credit'),
                "formatted_amount" => (($transaction->type == 'Debit') ? '-' : '') . $transaction->currency . ' ' . $transaction->amount 
            ];
        });

        return $transactions;
    }

    public function storeTransaction($request)
    {

    }
}