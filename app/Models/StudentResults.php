<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResults extends Model
{
    use HasFactory;

    // Table name (optional since Laravel will pluralize automatically)
    protected $table = 'student_results';

    // Columns that can be mass-assigned
    protected $fillable = [
        'marks',
        'reason',
        'is_updated',
        'student_id',
        'exam_id',
        'user_id',
    ];

    // Cast columns to proper types
    protected $casts = [
        'is_updated' => 'boolean',
    ];

    // Relationships

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }   
}