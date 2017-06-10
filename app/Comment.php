<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
      'body', 'status', 'food_id', 'user_id',
    ];
    public function user(){

        return $this->belongsTo('user');

    }
    public function food(){

        return $this->belongsTo('food');

    }
}
