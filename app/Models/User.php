<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    protected $fillable = [ //These values are mass assignable
        'username',
        'email',
        'password',
        'role',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    /**
    *  Setting the relationships between diferent models
    */

    // $user->follows gives back all users who this user is following
    public function follows() {
        return $this->hasMany(Follow::class);
    }

    // $user->shows gives back the favorited shows of the user
    public function shows() {
        return $this->hasMany(Show::class);
    }

    // $user->ratings gives back all the ratings of this user
    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    // $user->reports gives back all the messages sent by this user
    public function reports() {
        return $this->hasMany(Report::class);
    }
}

