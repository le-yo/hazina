<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class menu_items extends Model {

    protected $fillable = ['menu_id','description','type','next_menu_id','step','confirmation_phrase'];


}
