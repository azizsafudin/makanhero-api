<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Food extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'lat', 'lng', 'body'
    ];
    public function user(){

        return $this->belongsTo('App\user');

    }
    public function comments(){

        return $this->hasMany('App\comment')->withTrashed();

    }
}
