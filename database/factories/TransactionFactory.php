<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transaction;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Transaction::class, function (Faker $faker){
    $transactionTypes = ['Debit', 'Credit'];        
    $type = $transactionTypes[array_rand($transactionTypes, 1)];

    $amount = $faker->numberBetween(100, 800);

    return [
        'account_id' => NULL,
        'amount' => ($type == 'Debit' ? -$amount : $amount),
        'currency' => '$',
        'type' => $type,
    ];
});
