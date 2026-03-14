<?php

namespace App\Http\Controllers;

use App\Services\StudentResultsService;
use Illuminate\Http\Request;

class StudentResultController extends Controller
{
    protected $studentResultsService;
    public function __construct(StudentResultsService $studentResultsService)
    {
        $this->studentResultsService = $studentResultsService;
    }

    public function fetchStudentExamChart($classCategoryHasStudentClassId, $studentId)
    {
        return $this->studentResultsService->fetchStudentExamChart($classCategoryHasStudentClassId, $studentId);
    }



    public function store(Request $request)
    {
        return $this->studentResultsService->insertBulkResults($request);
    }
}
