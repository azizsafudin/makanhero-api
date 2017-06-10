<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    public function user(){

        return $this->belongsTo('user');

    }
    public function comments(){

        return $this->hasMany('comment');

    }
}
