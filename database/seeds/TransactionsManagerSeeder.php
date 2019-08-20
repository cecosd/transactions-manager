<?php

use Illuminate\Database\Seeder;

class TransactionsManagerSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
 
        factory(App\User::class, 10)->create()->each(function ($user) {
            
            factory(App\UserTransactionAccount::class)->create([
                'user_id' => $user->id
            ]);

        });

        foreach(App\UserTransactionAccount::all() as $transactionAccount){
            factory(App\Transaction::class)->create([
                'account_id' => $transactionAccount->id,
            ]);
        }
    }
}
