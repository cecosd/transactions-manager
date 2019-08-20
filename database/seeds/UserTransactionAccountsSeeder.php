<?php

use Illuminate\Database\Seeder;

class UserTransactionAccountsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 100)->create()->each(function ($user) {
            
            factory(App\UserTransactionAccount::class)->create([
                'user_id' => $user->id
            ]);

        });
    }
}
