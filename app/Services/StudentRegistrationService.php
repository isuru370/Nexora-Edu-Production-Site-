<?php

namespace App\Services;

use App\Models\StudentRegistration;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class StudentRegistrationService
{
    public function create(array $data): StudentRegistration
    {
        try {
            $studentCode = trim($data['student_code'] ?? '');
            $courseId = (int) ($data['course_id'] ?? 0);
            $now = Carbon::now();

            if ($studentCode === '') {
                throw new InvalidArgumentException('Student code is required.');
            }

            if ($courseId <= 0) {
                throw new InvalidArgumentException('Course is required.');
            }

            if (str_starts_with($studentCode, 'TMP')) {
                $student = Student::where('temporary_qr_code', $studentCode)
                    ->where('student_disable', false)
                    ->first();

                if (!$student) {
                    throw new InvalidArgumentException('Temporary QR code invalid.');
                }

                if (
                    !empty($student->temporary_qr_code_expire_date) &&
                    $now->gt(Carbon::parse($student->temporary_qr_code_expire_date))
                ) {
                    throw new InvalidArgumentException('Temporary QR code has expired.');
                }
            } else {
                $student = Student::where('custom_id', $studentCode)
                    ->where('student_disable', false)
                    ->first();

                if (!$student) {
                    throw new InvalidArgumentException('Student code invalid.');
                }

                if ((bool) ($student->permanent_qr_active ?? false) === false) {
                    throw new InvalidArgumentException('Permanent QR code is inactive.');
                }
            }

            $course = Course::find($courseId);
            if (!$course) {
                throw new InvalidArgumentException('Course not found.');
            }

            if ($course->status !== 'active') {
                throw new InvalidArgumentException('This course is not active.');
            }

            $exists = StudentRegistration::where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->exists();

            if ($exists) {
                throw new InvalidArgumentException('Student is already registered for this course.');
            }

            $months = (int) $course->duration_months;
            $totalFee = (float) $course->total_fee;
            $compulsory = (float) $course->compulsory_payment;
            $remaining = max($totalFee - $compulsory, 0);
            $monthly = $months > 0 ? ($remaining / $months) : 0;

            $compulsoryPaid = !empty($data['compulsory_paid']);

            return DB::transaction(function () use ($data, $student, $course, $months, $totalFee, $compulsory, $monthly, $compulsoryPaid) {
                return StudentRegistration::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'registration_date' => !empty($data['registration_date'])
                        ? $data['registration_date']
                        : now()->toDateString(),

                    'course_total_fee' => $totalFee,
                    'course_compulsory_amount' => $compulsory,
                    'course_monthly_amount' => round($monthly, 2),
                    'course_total_months' => $months,

                    'compulsory_paid' => $compulsoryPaid,
                    'compulsory_paid_date' => $compulsoryPaid
                        ? ($data['compulsory_paid_date'] ?? now()->toDateString())
                        : null,

                    'months_paid' => 0,
                    'payment_status' => 'pending',
                    'registration_status' => 'registered',

                    'course_start_date' => $course->start_date,
                    'course_end_date' => $course->end_date,
                    'next_payment_date' => $data['next_payment_date'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ])->fresh(['student', 'course']);
            });
        } catch (Throwable $e) {
            Log::error('Student registration failed', [
                'input' => $data,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function bulkCreate(array $data): array
    {
        try {
            $courseId = (int) ($data['course_id'] ?? 0);
            $studentIds = $data['student_ids'] ?? [];

            if ($courseId <= 0) {
                throw new InvalidArgumentException('Course is required.');
            }

            if (empty($studentIds) || !is_array($studentIds)) {
                throw new InvalidArgumentException('No students selected.');
            }

            $course = Course::find($courseId);
            if (!$course) {
                throw new InvalidArgumentException('Course not found.');
            }

            if ($course->status !== 'active') {
                throw new InvalidArgumentException('Course is not active.');
            }

            $months = (int) $course->duration_months;
            $totalFee = (float) $course->total_fee;
            $compulsory = (float) $course->compulsory_payment;
            $remaining = max($totalFee - $compulsory, 0);
            $monthly = $months > 0 ? ($remaining / $months) : 0;

            return DB::transaction(function () use ($studentIds, $course, $data, $months, $totalFee, $compulsory, $monthly) {
                $created = [];

                foreach ($studentIds as $studentId) {
                    $studentId = (int) $studentId;

                    $student = Student::where('id', $studentId)
                        ->where('student_disable', false)
                        ->first();

                    if (!$student) {
                        continue;
                    }

                    $exists = StudentRegistration::where('student_id', $studentId)
                        ->where('course_id', $course->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $created[] = StudentRegistration::create([
                        'student_id' => $studentId,
                        'course_id' => $course->id,
                        'registration_date' => !empty($data['registration_date'])
                            ? $data['registration_date']
                            : now()->toDateString(),

                        'course_total_fee' => $totalFee,
                        'course_compulsory_amount' => $compulsory,
                        'course_monthly_amount' => round($monthly, 2),
                        'course_total_months' => $months,

                        'compulsory_paid' => false,
                        'compulsory_paid_date' => null,
                        'months_paid' => 0,
                        'payment_status' => 'pending',
                        'registration_status' => 'registered',

                        'course_start_date' => $course->start_date,
                        'course_end_date' => $course->end_date,
                        'next_payment_date' => $data['next_payment_date'] ?? null,
                        'notes' => $data['notes'] ?? null,
                    ]);
                }

                return $created;
            });
        } catch (Throwable $e) {
            Log::error('Bulk registration failed', [
                'input' => $data,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
