<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [ // These are mass assignable
        'user_id',
        'message',
        'type',
        'lastModifier',
        'status',
        'read'
    ];
    protected $hidden = [
    ];


    /**
    *  Setting the relationships between diferent models
    */

    // $report->user gives back the user who wrote the message
    public function user() {
        return $this->belongsTo(User::class);
    }


}
