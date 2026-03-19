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
        $query = Course::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('course_code', 'like', "%{$search}%")
                    ->orWhere('course_name', 'like', "%{$search}%")
                    ->orWhere('lecturer', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
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
        return Course::findOrFail($id);
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
                return Course::create($data);
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

            if (
                isset($data['course_code']) &&
                $this->courseCodeExists($data['course_code'], $course->id)
            ) {
                throw new InvalidArgumentException('Course code is already assigned to another course.');
            }

            return DB::transaction(function () use ($course, $data) {
                $course->update($data);
                return $course->fresh();
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
                return $course->fresh();
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
        $query = Course::where('course_code', $code);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    private function prepareData(array $data, bool $isCreate = true): array
    {
        $data['course_code'] = $data['course_code'] ?? null;
        $data['status'] = $data['status'] ?? 'active';

        return $data;
    }

    private function validateBusinessRules(array $data): void
    {
        if (empty($data['course_code'])) {
            throw new InvalidArgumentException('Course code is required.');
        }

        if (empty($data['course_name'])) {
            throw new InvalidArgumentException('Course name is required.');
        }

        if ($data['total_fee'] < 0) {
            throw new InvalidArgumentException('Total fee cannot be negative.');
        }

        if ($data['compulsory_payment'] < 0) {
            throw new InvalidArgumentException('Compulsory payment cannot be negative.');
        }

        if ($data['compulsory_payment'] > $data['total_fee']) {
            throw new InvalidArgumentException('Compulsory payment cannot exceed total fee.');
        }

        if ($data['duration_months'] <= 0) {
            throw new InvalidArgumentException('Duration must be greater than zero.');
        }
    }
}
