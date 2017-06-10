<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user(){

        return $this->belongsTo('user');

    }
    public function food(){

        return $this->belongsTo('food');

    }
}