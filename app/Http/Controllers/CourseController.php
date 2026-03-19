<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Throwable;

class CourseController extends Controller
{
    public function __construct(
        private CourseService $courseService
    ) {
    }

    /**
     * Display a listing of courses.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'search',
                'status',
                'department',
            ]);

            $perPage = $request->integer('per_page', 15);

            $courses = $this->courseService->getAll($filters, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Courses retrieved successfully.',
                'data' => $courses,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve courses.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created course.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $course = $this->courseService->create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully.',
                'data' => $course,
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create course.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified course.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $course = $this->courseService->getById($id);

            return response()->json([
                'success' => true,
                'message' => 'Course retrieved successfully.',
                'data' => $course,
            ]);
        } catch (Throwable $e) {
            $statusCode = str_contains($e->getMessage(), 'No query results') ? 404 : 500;

            return response()->json([
                'success' => false,
                'message' => $statusCode === 404
                    ? 'Course not found.'
                    : 'Failed to retrieve course.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Update the specified course.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $course = $this->courseService->update($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully.',
                'data' => $course,
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            $statusCode = str_contains($e->getMessage(), 'No query results') ? 404 : 500;

            return response()->json([
                'success' => false,
                'message' => $statusCode === 404
                    ? 'Course not found.'
                    : 'Failed to update course.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Remove the specified course.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->courseService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully.',
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            $statusCode = str_contains($e->getMessage(), 'No query results') ? 404 : 500;

            return response()->json([
                'success' => false,
                'message' => $statusCode === 404
                    ? 'Course not found.'
                    : 'Failed to delete course.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Change course status.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        try {
            $status = $request->input('status');

            $course = $this->courseService->changeStatus($id, $status);

            return response()->json([
                'success' => true,
                'message' => 'Course status updated successfully.',
                'data' => $course,
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            $statusCode = str_contains($e->getMessage(), 'No query results') ? 404 : 500;

            return response()->json([
                'success' => false,
                'message' => $statusCode === 404
                    ? 'Course not found.'
                    : 'Failed to change course status.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }
}