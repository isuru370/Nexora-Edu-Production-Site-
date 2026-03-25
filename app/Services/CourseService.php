<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class CourseService
{
    private const ALLOWED_STATUSES = [
        'active',
        'inactive',
        'archived',
    ];

    public function getAll(array $filters = [], ?int $perPage = 15)
    {
        $query = Course::query()->with('teacher');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('course_code', 'like', "%{$search}%")
                    ->orWhere('course_name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($teacherQuery) use ($search) {
                        $teacherQuery->where('fname', 'like', "%{$search}%")
                            ->orWhere('lname', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        $query->orderBy('created_at', 'desc');

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function getById(int $id): Course
    {
        return Course::with('teacher')->findOrFail($id);
    }

    public function create(array $data): Course
    {
        try {
            $data = $this->prepareData($data, true);

            if (empty($data['course_code'])) {
                $data['course_code'] = $this->generateCourseCode();
            }

            $this->validateBusinessRules($data);

            if ($this->courseCodeExists($data['course_code'])) {
                throw new InvalidArgumentException('Course code already exists.');
            }

            return DB::transaction(function () use ($data) {
                return Course::create($data)->fresh('teacher');
            });
        } catch (Throwable $e) {
            Log::error('Course creation failed.', [
                'input' => $data ?? [],
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function update(int $id, array $data): Course
    {
        try {
            $course = $this->getById($id);

            $data = $this->prepareData($data, false);
            $merged = array_merge($course->toArray(), $data);

            $this->validateBusinessRules($merged);


            return DB::transaction(function () use ($course, $data) {
                $course->update($data);
                return $course->fresh('teacher');
            });
        } catch (Throwable $e) {
            Log::error('Course update failed.', [
                'course_id' => $id,
                'input' => $data ?? [],
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $course = $this->getById($id);

            if ($course->registrations()->exists()) {
                throw new InvalidArgumentException(
                    'Cannot delete this course because it has student registrations. Please archive it instead.'
                );
            }

            return DB::transaction(function () use ($course) {
                return (bool) $course->delete();
            });
        } catch (Throwable $e) {
            Log::error('Course deletion failed.', [
                'course_id' => $id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function changeStatus(int $id, string $status): Course
    {
        try {
            if (!in_array($status, self::ALLOWED_STATUSES, true)) {
                throw new InvalidArgumentException('Invalid course status.');
            }

            $course = $this->getById($id);

            return DB::transaction(function () use ($course, $status) {
                $course->update(['status' => $status]);
                return $course->fresh('teacher');
            });
        } catch (Throwable $e) {
            Log::error('Course status change failed.', [
                'course_id' => $id,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function generateCourseCode(): string
    {
        do {
            $code = 'CRS-' . date('Ymd') . '-' . rand(100, 999);
        } while ($this->courseCodeExists($code));

        return $code;
    }

    public function courseCodeExists(string $code, ?int $ignoreId = null): bool
    {
        $query = Course::where('course_code', trim($code));

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    private function prepareData(array $data, bool $isCreate = true): array
    {
        $prepared = [];

        $fields = [
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

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $prepared[$field] = $data[$field];
            }
        }

        if ($isCreate) {
            if (!array_key_exists('course_code', $prepared) || $prepared['course_code'] === '') {
                $prepared['course_code'] = null;
            }

            if (!array_key_exists('status', $prepared) || $prepared['status'] === null || $prepared['status'] === '') {
                $prepared['status'] = 'active';
            }

            if (!array_key_exists('teacher_percentage', $prepared)) {
                $prepared['teacher_percentage'] = 100;
            }
        }

        if (isset($prepared['course_code']) && $prepared['course_code'] !== null) {
            $prepared['course_code'] = trim($prepared['course_code']);
        }

        if (isset($prepared['course_name']) && $prepared['course_name'] !== null) {
            $prepared['course_name'] = trim($prepared['course_name']);
        }

        if (isset($prepared['description']) && $prepared['description'] !== null) {
            $prepared['description'] = trim($prepared['description']);
        }

        if (isset($prepared['department']) && $prepared['department'] !== null) {
            $prepared['department'] = trim($prepared['department']);
        }

        if (isset($prepared['status']) && $prepared['status'] !== null) {
            $prepared['status'] = strtolower(trim($prepared['status']));
        }

        if (array_key_exists('teacher_id', $prepared)) {
            $prepared['teacher_id'] = $prepared['teacher_id'] !== null && $prepared['teacher_id'] !== ''
                ? (int) $prepared['teacher_id']
                : null;
        }

        if (array_key_exists('teacher_percentage', $prepared)) {
            $prepared['teacher_percentage'] = $prepared['teacher_percentage'] !== null && $prepared['teacher_percentage'] !== ''
                ? (float) $prepared['teacher_percentage']
                : 100.00;
        }

        if (array_key_exists('total_fee', $prepared)) {
            $prepared['total_fee'] = $prepared['total_fee'] !== null && $prepared['total_fee'] !== ''
                ? (float) $prepared['total_fee']
                : 0;
        }

        if (array_key_exists('compulsory_payment', $prepared)) {
            $prepared['compulsory_payment'] = $prepared['compulsory_payment'] !== null && $prepared['compulsory_payment'] !== ''
                ? (float) $prepared['compulsory_payment']
                : 0;
        }

        if (array_key_exists('duration_months', $prepared)) {
            $prepared['duration_months'] = $prepared['duration_months'] !== null && $prepared['duration_months'] !== ''
                ? (int) $prepared['duration_months']
                : null;
        }

        if (array_key_exists('max_students', $prepared)) {
            $prepared['max_students'] = $prepared['max_students'] !== null && $prepared['max_students'] !== ''
                ? (int) $prepared['max_students']
                : null;
        }

        return $prepared;
    }

    
    private function validateBusinessRules(array $data): void
    {
        if (empty($data['course_code'])) {
            throw new InvalidArgumentException('Course code is required.');
        }

        if (empty($data['course_name'])) {
            throw new InvalidArgumentException('Course name is required.');
        }

        if (isset($data['status']) && !in_array($data['status'], self::ALLOWED_STATUSES, true)) {
            throw new InvalidArgumentException('Invalid course status.');
        }

        if (!isset($data['teacher_percentage'])) {
            throw new InvalidArgumentException('Teacher percentage is required.');
        }

        if ($data['teacher_percentage'] < 0) {
            throw new InvalidArgumentException('Teacher percentage cannot be negative.');
        }

        if ($data['teacher_percentage'] > 100) {
            throw new InvalidArgumentException('Teacher percentage cannot exceed 100.');
        }

        if (!isset($data['total_fee']) || $data['total_fee'] < 0) {
            throw new InvalidArgumentException('Total fee cannot be negative.');
        }

        if (!isset($data['compulsory_payment']) || $data['compulsory_payment'] < 0) {
            throw new InvalidArgumentException('Compulsory payment cannot be negative.');
        }

        if ($data['compulsory_payment'] > $data['total_fee']) {
            throw new InvalidArgumentException('Compulsory payment cannot exceed total fee.');
        }

        if (!isset($data['duration_months']) || $data['duration_months'] <= 0) {
            throw new InvalidArgumentException('Duration must be greater than zero.');
        }

        if (
            array_key_exists('teacher_id', $data) &&
            $data['teacher_id'] !== null &&
            !DB::table('teachers')->where('id', $data['teacher_id'])->exists()
        ) {
            throw new InvalidArgumentException('Selected teacher does not exist.');
        }

        if (
            !empty($data['start_date']) &&
            !empty($data['end_date']) &&
            strtotime($data['end_date']) < strtotime($data['start_date'])
        ) {
            throw new InvalidArgumentException('End date cannot be earlier than start date.');
        }

        if (
            isset($data['max_students']) &&
            $data['max_students'] !== null &&
            $data['max_students'] <= 0
        ) {
            throw new InvalidArgumentException('Max students must be greater than zero.');
        }
    }
}
