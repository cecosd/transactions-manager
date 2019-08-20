<?php

namespace App\Services;

use App\Transaction;
use App\UserTransactionAccount;

class TransactionsManagerService
{
    public function updateBalance(UserTransactionAccount $transactionAccount, $type, $amount)
    {
        if ($type == 'Debit') {
            $accountBalance = $transactionAccount->balance - $amount;
        } else if($type == 'Credit') {
            $accountBalance = $transactionAccount->balance + $amount;
        }
        
        $transactionAccount->update([
            'balance' => $accountBalance
        ]);
    }

    public function singleTransaction($transaction)
    {
        return [
            "id" => $transaction->id,
            "account_user_name" => $transaction->transactionAccount->user->name,
            "account_user_email" => $transaction->transactionAccount->user->email,
            "account_balance" => $transaction->transactionAccount->balance,
            "amount" => $transaction->amount,
            "type" => $transaction->type,
            "is_debit" => ($transaction->type == 'Debit'),
            "is_credit" => ($transaction->type == 'Credit'),
            "formatted_amount" => (($transaction->type == 'Debit') ? '-' : '+') . $transaction->currency . ' ' . $transaction->amount 
        ];
    }
    
    public function getAllTransactions()
    {
        $transactions = Transaction::with([
            'transactionAccount' => function($q){
                $q->with(['user'])->get();
            }
        ])
        ->get()
        ->map(function($transaction){
            return $this->singleTransaction($transaction);
        });

        return response()->json($transactions);
    }

    public function getAllAccounts()
    {
        $user_transaction_accounts = UserTransactionAccount::with([
            'user'
        ])
        ->get()
        ->map(function($user_transaction_account){
            return [
                "id" => $user_transaction_account->id,
                "email" => $user_transaction_account->user->email,
                "name" => $user_transaction_account->user->name,
                "balance" => $user_transaction_account->balance,
            ];
        });

        return response()->json($user_transaction_accounts);
    }

    public function storeTransaction($request)
    {
        $user = \App\User::with(['transactionAccounts'])->whereEmail($request->user_email)->first();
        
        $transactionAccount = $user->transactionAccounts->first();
        
        $transaction = factory(\App\Transaction::class)->create([
            'account_id' => $transactionAccount->id,
            'amount' => $request->amount,
            'currency' => '$',
            'type' => $request->type,
        ]);

        $this->updateBalance($transactionAccount, $request->type, $request->amount);

        return response()->json($this->singleTransaction($transaction));
    }

    public function updateTransaction($request)
    {
        if(empty($transaction = \App\Transaction::whereId($request->id)->first())){
            return response()->json(['error' => 'Wrong transaction details.']);
        }
        
        $transaction->update($request->all());

        $this->updateBalance($transaction->transactionAccount, $request->type, $request->amount);

        return response()->json($this->singleTransaction($transaction));
    }

    public function deleteTransaction($id)
    {
        if(empty($transaction = \App\Transaction::whereId($id)->first())){
            return response()->json(['error' => 'Wrong transaction action.']);
        }

        $this->updateBalance($transaction->transactionAccount, $transaction->type, $transaction->amount);
        
        $transaction->delete();

        return response()->json(['message', 'Transaction deleted successfully.']);
    }
}