<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = ['request_url', 'request_type', 'request_body', 'response_body'];
}
