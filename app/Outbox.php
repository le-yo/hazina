<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outbox extends Model
{
    //
    protected $table = 'outbox';

    protected $fillable = [
        'phone',
        'message',
        'status',
        'reminder_id',
        'content',
    ];
}
