<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Services\StudentRegistrationService;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Throwable;

class StudentRegistrationController extends Controller
{
    protected $studentRegistrationService;

    public function __construct(StudentRegistrationService $studentRegistrationService)
    {
        $this->studentRegistrationService = $studentRegistrationService;
    }

    public function createPage()
    {
        $courses = Course::where('status', 'active')
            ->orderBy('course_name')
            ->get();

        return view('student_registrations.create', compact('courses'));
    }

    public function store(Request $request)
    {
        try {
            $this->studentRegistrationService->create($request->all());

            return redirect()
                ->route('student-registrations.create')
                ->with('success', 'Student registered successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to register student.']);
        }
    }

    public function bulkCreatePage()
    {
        $courses = Course::where('status', 'active')
            ->orderBy('course_name')
            ->get();

        $students = Student::where('student_disable', false)
            ->orderBy('initial_name')
            ->get();

        return view('student_registrations.bulk-create', compact('courses', 'students'));
    }

    public function bulkStore(Request $request)
    {
        try {
            $created = $this->studentRegistrationService->bulkCreate($request->all());

            return redirect()
                ->route('student-registrations.bulk-create')
                ->with('success', count($created) . ' student(s) registered successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to register students in bulk.']);
        }
    }
}
