<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'total_fee',
        'compulsory_payment',
        'duration_months',
        'lecturer',
        'department',
        'status',
        'max_students',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'total_fee' => 'decimal:2',
        'compulsory_payment' => 'decimal:2',
        'duration_months' => 'integer',
        'max_students' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * One course has many student registrations
     */
    public function registrations()
    {
        return $this->hasMany(StudentRegistration::class, 'course_id');
    }
}