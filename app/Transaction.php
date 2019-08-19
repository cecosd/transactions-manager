<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'amount', 'currency', 'type'
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at',
    ];

    public function transactionAccount()
    {
        return $this->belongsTo(UserTransactionAccount::class, 'account_id', 'id');
    }
}
