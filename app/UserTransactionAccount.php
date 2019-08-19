<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransactionAccount extends Model
{
    
    protected $table = 'user_transaction_accounts';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'balance', 'currency'
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }
}
