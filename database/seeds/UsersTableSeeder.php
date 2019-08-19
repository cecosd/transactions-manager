<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
            ])->each(function ($transactionAccount){
                
                $transactionTypes = ['Debit', 'Credit'];
                
                factory(App\Transaction::class)->create([
                    'account_id' => $transactionAccount->id,
                    'type' => $transactionTypes[array_rand($transactionTypes, 1)],
                ]);

            });

        });
    }
}
