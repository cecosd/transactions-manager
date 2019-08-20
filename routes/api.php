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
    return response()->json((new \App\Services\TransactionService())->getAllTransactions());
})->name('transactions-manager-get-data');

Route::post('/transactions-manager/store', function (NewTransactionRequest $request) {
    return response()->json((new \App\Services\TransactionService())->storeTransaction($request));
})->name('transactions-manager-create-transaction');

Route::post('/transactions-manager/update', function (Request $request) {
    return response()->json((new \App\Services\TransactionService())->updateTransaction($request));
})->name('transactions-manager-update-transaction');
