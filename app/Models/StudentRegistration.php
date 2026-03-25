<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;

    protected $table = 'student_registrations';

    protected $fillable = [
        'student_id',
        'course_id',
        'registration_date',
        'course_total_fee',
        'course_compulsory_amount',
        'course_monthly_amount',
        'course_total_months',
        'compulsory_paid',
        'compulsory_paid_date',
        'months_paid',
        'payment_status',
        'registration_status',
        'notes',
        'course_start_date',
        'course_end_date',
        'next_payment_date',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'course_id' => 'integer',
        'registration_date' => 'date',
        'course_total_fee' => 'decimal:2',
        'course_compulsory_amount' => 'decimal:2',
        'course_monthly_amount' => 'decimal:2',
        'course_total_months' => 'integer',
        'compulsory_paid' => 'boolean',
        'compulsory_paid_date' => 'date',
        'months_paid' => 'integer',
        'course_start_date' => 'date',
        'course_end_date' => 'date',
        'next_payment_date' => 'date',
        'months_remaining' => 'integer',
        'remaining_balance' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(StudentCoursePayment::class, 'registration_id');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('registration_status', ['registered', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('registration_status', 'completed');
    }

    public function scopeDropped($query)
    {
        return $query->where('registration_status', 'dropped');
    }

    public function scopePendingPayments($query)
    {
        return $query->whereIn('payment_status', ['pending', 'overdue']);
    }

    public function getIsCompletedAttribute()
    {
        return $this->registration_status === 'completed';
    }

    public function getIsDroppedAttribute()
    {
        return $this->registration_status === 'dropped';
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->registration_status, ['registered', 'in_progress'], true);
    }
}
