<?php

namespace App\Http\Controllers;

use App\Services\TeacherPaymentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TeacherPaymentsController extends Controller
{
    protected $teacherPaymentsService;
    public function __construct(TeacherPaymentsService $teacherPaymentsService)
    {
        $this->teacherPaymentsService = $teacherPaymentsService;
    }

    public function getMonthlyPayments($yearMonth)
    {
        return $this->teacherPaymentsService->fetchTeacherPaymentsByMonth($yearMonth);
    }

    public function fetchTeacherPaymentsCurrentMonth()
    {
        return $this->teacherPaymentsService->fetchTeacherPaymentsCurrentMonth();
    }
    public function fetchTeacherClassPayments($teacherId, $yearMonth)
    {
        return $this->teacherPaymentsService->fetchTeacherPaymentsByTeacher($teacherId, $yearMonth);
    }
    public function getTeacherClassWiseStudentPaymentStatus($teacherId, $yearMonth, Request $request)
    {
        return $this->teacherPaymentsService->getTeacherClassWiseStudentPaymentStatus($teacherId, $yearMonth, $request);
    }
    public function fetchSalarySlipDataTest($teacherId, $yearMonth)
    {
        return $this->teacherPaymentsService->fetchSalarySlipDataTest($teacherId, $yearMonth);
    }

    public function studentPaymentMonthCheck($teacherId, $yearMonth)
    {
        return $this->teacherPaymentsService->studentPaymentMonthCheck($teacherId, $yearMonth);
    }
    public function showSalarySlip($teacherId, $yearMonth)
    {
        try {
            // Get data FROM SERVICE (returns ARRAY)
            $data = $this->teacherPaymentsService->fetchSalarySlipData($teacherId, $yearMonth);

            // Pass $data to Blade
            return view('teacher_payment.salary-slip-exact', ['data' => $data]);
        } catch (\Exception $e) {
            return view('teacher_payment.salary-slip-exact', [
                'data' => [
                    'status' => 'error',
                    'message' => 'Unexpected error occurred.',
                    'teacher_id' => $teacherId,
                    'month_year' => $yearMonth
                ]
            ]);
        }
    }




    public function teachersExpenses($yearMonth)
    {
        return $this->teacherPaymentsService->teachersExpenses($yearMonth);
    }

    public function togglePaymentStatus(Request $request, $id)
    {
        return $this->teacherPaymentsService->togglePaymentStatus($request, $id);
    }
    public function storeTeacherPayments(Request $request)
    {
        return $this->teacherPaymentsService->storeTeacherPayments($request);
    }



    // web page routes

    public function indexPage()
    {
        return view('teacher_payment.index');
    }
    public function paymentPage($teacherId)
    {
        return view('teacher_payment.salary', ['teacherId' => $teacherId]);
    }
    public function historyPage($teacherId)
    {
        return view('teacher_payment.history', ['teacherId' => $teacherId]);
    }
    public function viewPage($teacherId)
    {
        return view('teacher_payment.view', ['teacherId' => $teacherId]);
    }
    public function expensesPage()
    {
        return view('teacher_payment.expenses');
    }
}
