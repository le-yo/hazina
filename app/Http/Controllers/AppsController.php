<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppsController extends Controller
{
    //
    public function index(){
        return view('apps.index');
    }
}
