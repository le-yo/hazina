<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreapprovedClients extends Model
{
    //
    protected $fillable = [
      'mobile_number',
      'national_id_number',
      'names',
      'net_salary',
      'loan_limits'
    ];
}
