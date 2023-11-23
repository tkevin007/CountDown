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

    protected $fillable = [
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


    public function follows() {
        return $this->hasMany(Follow::class);
    }

    public function shows() {
        return $this->hasMany(Show::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    public function reports() {
        return $this->hasMany(Report::class);
    }
}

