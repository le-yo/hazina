<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class menu extends Model {

    protected $fillable = ['title','type','is_parent','confirmation_message'];


}
