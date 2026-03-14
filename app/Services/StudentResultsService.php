<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\StudentResults;
use Illuminate\Http\Request;

class StudentResultsService
{

    public function insertBulkResults(Request $request)
    {
        $processed = []; // To store inserted or updated results

        try {
            // 1️⃣ Get input data from request
            $studentsResults = $request->input('results'); // Array of student results
            $examId = $request->input('exam_id');
            $userId = $request->input('user_id');

            // 2️⃣ Loop through each student's result
            foreach ($studentsResults as $data) {
                $studentId = $data['student_id'];
                $marks = $data['marks'];

                // 3️⃣ Calculate grade based on marks
                $grade = $this->calculateGrade($marks);

                // 4️⃣ Generate reason based on grade
                $reason = $this->generateReason($grade);

                // 5️⃣ Check if a record already exists for this student & exam
                $result = StudentResults::where('student_id', $studentId)
                    ->where('exam_id', $examId)
                    ->first();

                if ($result) {
                    // 6️⃣ Update existing record if found
                    $result->update([
                        'marks' => $marks,
                        'reason' => $reason,      // ✅ reason updated automatically
                        'user_id' => $userId,
                        'is_updated' => true,     // mark as updated
                    ]);
                } else {
                    // 7️⃣ Create new result record if not exists
                    $result = StudentResults::create([
                        'student_id' => $studentId,
                        'exam_id' => $examId,
                        'user_id' => $userId,
                        'marks' => $marks,
                        'reason' => $reason,
                        'is_updated' => false,
                    ]);
                }

                // 8️⃣ Add the result to the processed array for response
                $processed[] = $result;
            }

            // 9️⃣ Return success response with all processed records
            return [
                'status' => 'success',
                'message' => 'Student results processed successfully',
                'data' => $processed
            ];
        } catch (\Exception $e) {
            // 10️⃣ Handle any exceptions
            return [
                'status' => 'error',
                'message' => 'Failed to process student results',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Calculate grade from marks
     */
    public function calculateGrade($marks)
    {
        if ($marks >= 90) return 'A';
        if ($marks >= 80) return 'B';
        if ($marks >= 70) return 'C';
        if ($marks >= 60) return 'D';
        return 'F';
    }

    /**
     * Generate reason based on grade
     */
    public function generateReason($grade)
    {
        switch ($grade) {
            case 'A':
                return 'Excellent performance! Keep up the great work. Grade (A) indicates outstanding achievement.';
            case 'B':
                return 'Good work! You have done well. Grade (B) indicates above average performance.';
            case 'C':
                return 'Average performance, room for improvement. Grade (C) indicates satisfactory performance.';
            case 'D':
                return 'Below average, try harder next time. Grade (D) indicates poor performance.';
            case 'F':
                return 'Failed. Need to improve significantly. Grade (F) indicates failure.';
            default:
                return '';
        }
    }

    public function fetchStudentExamChart($classCategoryHasStudentClassId, $studentId)
    {
        try {

            $exams = Exam::with(['studentResults' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
                ->where('class_category_has_student_class_id', $classCategoryHasStudentClassId)
                ->where('is_canceled', false)
                ->orderBy('date', 'asc')
                ->get();

            if ($exams->isEmpty()) {
                return [
                    'status' => 'error',
                    'message' => 'No exams found'
                ];
            }

            $chartData = $exams->map(function ($exam) {

                $result = $exam->studentResults->first();

                return [
                    'exam_id'   => $exam->id,
                    'exam_title' => $exam->title,
                    'date'      => optional($exam->date)->format('Y-m-d'),
                    'is_updated' => $result->is_updated ?? false,
                    'marks'     => $result->marks ?? 0,
                ];
            });

            return [
                'status' => 'success',
                'data'   => $chartData
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch chart data',
                'error'   => $e->getMessage()
            ];
        }
    }
}
