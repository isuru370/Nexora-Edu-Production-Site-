<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPortalLogin extends Model
{
    protected $fillable = [
        'student_id',
        'username',
        'password',
        'is_verify',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_verify' => 'boolean',
        'is_active' => 'boolean',
    ];
}
