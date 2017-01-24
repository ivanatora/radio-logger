<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;

class FixesController extends Controller
{

    public function test1()
    {
        $aChannels = Channel::all();
        print_r($aChannels);
    }

    public function codes()
    {
        $aCodes = [];

        for ($i = 0; $i < 4000000; $i++){
            $aCodes[] = $this->generateRandomString(6);
        }

        foreach ($aCodes as $sCode){
            file_put_contents('/home/ivanatora/codes.txt', $sCode.'\r\n');
        }
    }

    function generateRandomString($length = 10)
    {
        $characters       = '2345679ABCDEFHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}