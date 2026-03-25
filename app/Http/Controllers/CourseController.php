<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Throwable;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function indexPage(Request $request)
    {
        try {
            $filters = $request->only([
                'search',
                'status',
                'department',
            ]);

            $courses = $this->courseService->getAll($filters, 15);

            return view('courses.index', compact('courses'));
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to load courses.');
        }
    }

    public function createPage()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        try {
            $this->courseService->create($request->all());

            return redirect()
                ->route('courses.index')
                ->with('success', 'Course created successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create course.']);
        }
    }

    public function show(int $id)
{
    try {
        $course = $this->courseService->getById($id);

        return view('courses.show', compact('course'));
    } catch (ModelNotFoundException $e) {
        return redirect()
            ->route('courses.index')
            ->with('error', 'Course not found.');
    } catch (Throwable $e) {
        return redirect()
            ->route('courses.index')
            ->with('error', 'Something went wrong while loading the course details.');
    }
}

    public function editPage(int $id)
    {
        try {
            $course = $this->courseService->getById($id);

            return view('courses.edit', compact('course'));
        } catch (Throwable $e) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Course not found.');
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $this->courseService->update($id, $request->all());

            return redirect()
                ->route('courses.index')
                ->with('success', 'Course updated successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update course.']);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->courseService->delete($id);

            return redirect()
                ->route('courses.index')
                ->with('success', 'Course deleted successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->route('courses.index')
                ->with('error', $e->getMessage());
        } catch (Throwable $e) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Failed to delete course.');
        }
    }

    public function changeStatus(Request $request, int $id)
    {
        try {
            $status = $request->input('status');

            $this->courseService->changeStatus($id, $status);

            return redirect()
                ->route('courses.index')
                ->with('success', 'Course status updated successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->route('courses.index')
                ->with('error', $e->getMessage());
        } catch (Throwable $e) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Failed to change course status.');
        }
    }
}