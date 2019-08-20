# transactions-manager

Basic transactions manager made with Laravel 5.8 and Vue.JS

# Migrations and DB

php artisan migrate

# Seeding the DB

1. To seed all tables use: 

php artisan db:seed --class=TransactionsManagerSeeder

2. To seed only users and user_transaction_accounts tables use:

php artisan db:seed --class=UserTransactionAccountsSeeder

OR 

php artisan db:seed
