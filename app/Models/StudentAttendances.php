<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendances extends Model
{
    use HasFactory;

    protected $table = "student_attendances";

    protected $fillable = [
        'at_date',
        'student_student_student_classes_id',
        'student_id',
        'attendance_id',
    ];

    // Type casting for JSON responses
    protected $casts = [
        'student_student_student_classes' => 'integer',
        'student_id'                       => 'integer',
        'attendance_id'                    => 'integer',   // cast status as integer
        'at_date'                          => 'datetime',
        'created_at'                       => 'datetime',
        'updated_at'                       => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function attendance()
    {
        return $this->belongsTo(ClassAttendance::class, 'attendance_id');
    }

    // Relationship
    public function studentStudentClass()
    {
        return $this->belongsTo(
            StudentStudentStudentClass::class,
            'student_student_student_classes'
        );
    }
}
