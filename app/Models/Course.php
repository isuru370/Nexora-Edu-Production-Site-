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
        'teacher_percentage',
        'description',
        'total_fee',
        'compulsory_payment',
        'duration_months',
        'teacher_id',
        'department',
        'status',
        'max_students',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'teacher_percentage' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'compulsory_payment' => 'decimal:2',
        'monthly_payment' => 'decimal:2',
        'duration_months' => 'integer',
        'teacher_id' => 'integer',
        'max_students' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function registrations()
    {
        return $this->hasMany(StudentRegistration::class, 'course_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function getInstitutePercentageAttribute()
    {
        return round(100 - (float) $this->teacher_percentage, 2);
    }
}