<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loans';

    protected $fillable = [
        'loan_id',
        'loan_product_id',
        'client_name',
        'phone',
        'loan_amount',
        'disbursed'
    ];
}
