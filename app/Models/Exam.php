<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = [
        'title',
        'date',
        'start_time',
        'end_time',
        'class_category_has_student_class_id',
        'class_hall_id',
        'is_canceled',
    ];

    protected $casts = [
        'class_category_has_student_class_id' => 'integer',
        'class_hall_id' => 'integer',
        'is_canceled' => 'boolean',
        'date' => 'date:Y-m-d',
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = ['duration'];

    public function classCategoryHasStudentClass()
    {
        return $this->belongsTo(ClassCategoryHasStudentClass::class, 'class_category_has_student_class_id');
    }

    public function hall()
    {
        return $this->belongsTo(ClassHalls::class, 'class_hall_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_canceled', false);
    }

    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return round(
                $this->start_time->diffInMinutes($this->end_time) / 60,
                2
            );
        }

        return null;
    }

    public function studentResults()
    {
        return $this->hasMany(StudentResults::class, 'exam_id');
    }
}
