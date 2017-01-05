<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;

class FixesController extends Controller
{
    public function test1(){
        $aChannels = Channel::all();
        print_r($aChannels);
    }
    
}
