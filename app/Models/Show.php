<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Show extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [ // These are mass assignable
        'TMDB_show_id',
        'current_season',
        'current_episode',
        'show_name',
        'status'
    ];
    protected $hidden = [
    ];

    /**
    *  Setting the relationships between diferent models
    */

    // $show->user gives back the user who is associated with the show
    public function user() {
        return $this->belongsTo(User::class);
    }
    // $show->ratings gives back the ratings of this show
    public function ratings() {
        return $this->hasMany(Rating::class);
    }
}
