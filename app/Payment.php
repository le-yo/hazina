<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'client_name',
        'phone',
        'amount',
        'status',
        'transaction_id',
        'account_no',
        'transaction_time',
        'paybill',
        'comments'
    ];

    /**
     * @param $transaction_time
     */
    public function setTransactionTimeAttribute($transaction_time)
    {
        $this->attributes['transaction_time'] = Carbon::parse($transaction_time)->toDateTimeString();
    }
}
