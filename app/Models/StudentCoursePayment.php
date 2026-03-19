<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentCoursePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_course_payments';

    protected $fillable = [
        'registration_id',
        'payment_type',
        'expected_amount',
        'paid_amount',
        'balance_before',
        'balance_after',
        'payment_date',
        'due_date',
        'month_year',
        'month_number',
        'payment_method',
        'transaction_id',
        'receipt_number',
        'description',
        'notes',
        'status',
        'collected_by',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'registration_id' => 'integer',
        'expected_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'payment_date' => 'date',
        'due_date' => 'date',
        'month_number' => 'integer',
        'verified_at' => 'datetime',
    ];

    /**
     * Payment belongs to one registration
     */
    public function registration()
    {
        return $this->belongsTo(StudentRegistration::class, 'registration_id');
    }

    /**
     * Payment -> student access through registration
     */
    public function student()
    {
        return $this->hasOneThrough(
            Student::class,
            StudentRegistration::class,
            'id',          // Foreign key on student_registrations table...
            'id',          // Foreign key on students table...
            'registration_id', // Local key on student_course_payments table...
            'student_id'   // Local key on student_registrations table...
        );
    }

    /**
     * Payment -> course access through registration
     */
    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            StudentRegistration::class,
            'id',         // Foreign key on student_registrations table...
            'id',         // Foreign key on courses table...
            'registration_id', // Local key on student_course_payments table...
            'course_id'   // Local key on student_registrations table...
        );
    }
}