<?php


namespace App\Services;

use App\Models\AdmissionPayments;
use App\Models\ExtraIncomes;
use App\Models\InstitutePayment;
use App\Models\Payments;
use App\Models\TeacherPayment;

class ReportService
{
    public function generateUsersReport()
    {
        // Logic to generate user report
        return "Users report generated.";
    }

    public function generateStudentsReport()
    {
        // Logic to generate payment report
        return "Students report generated.";
    }

    public function generateTeachersReport()
    {
        // Logic to generate attendance report
        return "Teachers report generated.";
    }

    public function generateClassesReport()
    {
        // Logic to generate exam report
        return "Classes report generated.";
    }

    /*
    // Add payment report generation methods as needed
    */

    public function generateYearlyPaymentsReport($year)
    {
        // Logic to generate exam report
        return "Exams report generated.";
    }

    public function generateMonthlyPaymentsReport($month)
    {
        // Logic to generate exam report
        return "Exams report generated.";
    }

    public function generateDayllyPaymentsReport($day)
    {
        // Logic to generate exam report
        return "Exams report generated.";
    }



    public function generateAllDailyPaymentsReport($day)
    {
        // ---------- RECEIPTS (RECORDS) ----------
        $studentPaymentRecords = Payments::where('status', 1)
            ->whereDate('created_at', $day)
            ->orderBy('created_at', 'asc')
            ->get();

        $admissionPaymentRecords = AdmissionPayments::whereDate('created_at', $day)
            ->orderBy('created_at', 'asc')
            ->get();

        $extraIncomeRecords = ExtraIncomes::whereDate('created_at', $day)
            ->orderBy('created_at', 'asc')
            ->get();

        // ---------- RECEIPTS (TOTALS) ----------
        $studentPayments = $studentPaymentRecords->sum('amount');
        $admissionPayments = $admissionPaymentRecords->sum('amount');
        $extraIncomes = $extraIncomeRecords->sum('amount');

        $totalReceipts = $studentPayments + $admissionPayments + $extraIncomes;

        // ---------- PAYMENTS (RECORDS) ----------
        $teacherPaymentRecords = TeacherPayment::where('status', 1)
            ->whereDate('created_at', $day)
            ->orderBy('created_at', 'asc')
            ->get();

        $institutePaymentRecords = InstitutePayment::where('status', 1)
            ->whereDate('created_at', $day)
            ->orderBy('created_at', 'asc')
            ->get();

        // ---------- PAYMENTS (TOTALS) ----------
        $teacherPayments = $teacherPaymentRecords->sum('payment');
        $institutePayments = $institutePaymentRecords->sum('payment');

        $totalPayments = $teacherPayments + $institutePayments;

        // ---------- BALANCE ----------
        $balance = $totalReceipts - $totalPayments;

        // ---------- RETURN FULL REPORT ----------
        return [
            'date' => $day,

            // RECEIPTS
            'student_payment_records' => $studentPaymentRecords,
            'admission_payment_records' => $admissionPaymentRecords,
            'extra_income_records' => $extraIncomeRecords,

            'student_payments_total' => $studentPayments,
            'admission_payments_total' => $admissionPayments,
            'extra_incomes_total' => $extraIncomes,
            'total_receipts' => $totalReceipts,

            // PAYMENTS
            'teacher_payment_records' => $teacherPaymentRecords,
            'institute_payment_records' => $institutePaymentRecords,

            'teacher_payments_total' => $teacherPayments,
            'institute_payments_total' => $institutePayments,
            'total_payments' => $totalPayments,

            // BALANCE
            'balance' => $balance
        ];
    }
}
