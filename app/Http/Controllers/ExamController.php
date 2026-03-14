<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected ExamService $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    /**
     * List exams
     */
    public function index()
    {
        return $this->examService->fetchExam();
    }

    public function studentClassMiniDetails($exam_id)
    {
        return $this->examService->studentClassMiniDetails($exam_id);
    }

    /**
     * Store new exam
     */
    public function store(Request $request)
    {
        return $this->examService->createExam($request);
    }

    /**
     * Update exam
     */
    public function update(Request $request, $exam_id)
    {
        return $this->examService->updateExam($request, $exam_id);
    }

    /**
     * Cancel exam
     */
    public function cancel($exam_id)
    {
        return $this->examService->cancelExam($exam_id);
    }

    /**
     * Web: Exam list page
     */
    public function indexPage()
    {
        return view('student_exam.index');
    }

    /**
     * Web: Create exam page
     */
    public function createPage()
    {
        return view('student_exam.create');
    }

    public function enterMarks($exam_id)
    {
        // You can also load the students for this exam if needed
        return view('student_exam.marks', compact('exam_id'));
    }
}
