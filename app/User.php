<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Alsofronie\Uuid\Uuid32ModelTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Uuid32ModelTrait;

    private static $uuidOptimization = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'dp', 'gender', 'points'
    ];
    /**
     * Set default values for these fields
     *
     * @var array
     */
    protected $attributes = [
        'points' => 0,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'comments', 'foods',
    ];

    public function foods(){
        return $this->hasMany('App\food');
    }
    public function comments(){
        return $this->hasMany('App\comment');
    }
}
