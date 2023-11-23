<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Show extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'TMDB_show_id',
        'current_season',
        'current_episode',
        'show_name',
        'status'
    ];
    protected $hidden = [
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }
}
