<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    //

    protected $fillable = [
        'slug',
        'message',
        'type',
        'days_to',
        'days_overdue',
    ];
}
