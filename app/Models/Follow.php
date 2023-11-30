<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follow extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [ // These are mass assignable
        'user_id',
        'follow_id'
    ];
    protected $hidden = [
    ];

    /**
    *  Setting the relationships between diferent models
    */

    // $follow->user gives back the user who this follow record belongs to
    public function user() {
        return $this->belongsTo(User::class);
    }
}
