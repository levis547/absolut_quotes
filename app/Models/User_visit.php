<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_visit extends Model
{
    protected $fillable = [
        'ip_address',
        'browser',
        'address',
        'count',
    ];
}
