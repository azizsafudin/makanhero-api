<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
      'body', 'status', 'food_id', 'user_id',
    ];
    public function user(){

        return $this->belongsTo('App\user');

    }
    public function food(){

        return $this->belongsTo('App\food');

    }
}
