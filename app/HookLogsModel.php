<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class HookLogsModel extends Model
{
    protected $table = "hook_logs";

    protected $fillable = ['details'];

}
