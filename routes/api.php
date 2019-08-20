<?php

use App\Http\Requests\NewTransactionRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/transactions-manager/data', function () {
    return response()->json((new \App\Services\TransactionsManagerService())->getAllTransactions());
})->name('transactions-manager-get-transactions');

Route::get('/transactions-manager/accounts', function () {
    return response()->json((new \App\Services\TransactionsManagerService())->getAllAccounts());
})->name('transactions-manager-get-accounts');

Route::post('/transactions-manager/store', function (NewTransactionRequest $request) {
    return response()->json((new \App\Services\TransactionsManagerService())->storeTransaction($request));
})->name('transactions-manager-create-transaction');

Route::put('/transactions-manager/update', function (Request $request) {
    return response()->json((new \App\Services\TransactionsManagerService())->updateTransaction($request));
})->name('transactions-manager-update-transaction');

Route::delete('/transactions-manager/delete/{transaction?}', function ($transaction) {
    return response()->json((new \App\Services\TransactionsManagerService())->deleteTransaction($transaction));
})->name('transactions-manager-delete-transaction');
