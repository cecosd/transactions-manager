<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\UserTransactionAccount;
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

$factory->define(UserTransactionAccount::class, function (Faker $faker){
    return [
        'user_id' => NULL,
        'balance' => $faker->numberBetween(10000, 100000),
        'currency' => '$',
    ];
});
