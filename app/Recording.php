<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    protected $table = 'recordings';

    public function notes(){
        return $this->hasMany('App\Note');
    }
}
