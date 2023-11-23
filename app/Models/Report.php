<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'message',
        'type',
        'lastModifier',
        'status',
        'read'
    ];
    protected $hidden = [
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }


}
