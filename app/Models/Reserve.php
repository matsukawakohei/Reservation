<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    protected $table = 'reserves';

    protected $fillable = [
        'user_id',
        'court_id',
        'start_time',
        'end_time'
    ];

}
