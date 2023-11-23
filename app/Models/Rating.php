<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'season_number',
        'episode_number',
        'episode_name',
        'episode_desc',
        'episode_still_path',
        'user_rating',
        'show_name',
        'TMDB_show_id'
    ];
    protected $hidden = [
    ];

    public function show() {
        return $this->belongsTo(Show::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

}
