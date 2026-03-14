<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        $pages = [

            // Dashboard
            ['page' => 'Dashboard', 'route_name' => 'dashboard'],
            ['page' => 'Home', 'route_name' => 'home'],
            ['page' => 'Classes', 'route_name' => 'classes'],

            // System Users
            ['page' => 'System Users - List', 'route_name' => 'system-users.index'],
            ['page' => 'System Users - Create', 'route_name' => 'system-users.create'],
            ['page' => 'System Users - View', 'route_name' => 'system-users.showPage'],
            ['page' => 'System Users - Edit', 'route_name' => 'system-users.edit'],

            // User Types
            ['page' => 'User Types - List', 'route_name' => 'user-types.index'],
            ['page' => 'User Types - Create', 'route_name' => 'user-types.create'],
            ['page' => 'User Types - View', 'route_name' => 'user-types.show'],

            // Class Attendance
            ['page' => 'Class Attendance - Index', 'route_name' => 'class-attendance.index'],
            ['page' => 'Class Attendance - Create', 'route_name' => 'class-attendance.create'],

            // Students
            ['page' => 'Students - List', 'route_name' => 'students.index'],
            ['page' => 'Students - Create', 'route_name' => 'students.create'],
            ['page' => 'Students - Student Images', 'route_name' => 'students.studentImages'],
            ['page' => 'Students - All Images', 'route_name' => 'students.images'],
            ['page' => 'Students - Add Student To Class', 'route_name' => 'students.add_student_to_class'],
            ['page' => 'Students - Add Student To Single Class', 'route_name' => 'students.add_student_to_single_class'],
            ['page' => 'Students - Student Analytic', 'route_name' => 'students.student_analytic'],
            ['page' => 'Students - Edit', 'route_name' => 'students.edit'],
            ['page' => 'Students - View', 'route_name' => 'students.show'],

            
            // Class Rooms
            ['page' => 'Class Rooms - List', 'route_name' => 'class_rooms.index'],
            ['page' => 'Class Rooms - Create', 'route_name' => 'class_rooms.create'],
            ['page' => 'Class Rooms - Schedule', 'route_name' => 'class_rooms.schedule'],
            ['page' => 'Class Rooms - Add Class Category', 'route_name' => 'class_rooms.add_class_category'],
            ['page' => 'Class Rooms - Edit', 'route_name' => 'class_rooms.edit'],
            ['page' => 'Class Rooms - View', 'route_name' => 'class_rooms.show'],

            // Teachers
            ['page' => 'Teachers - List', 'route_name' => 'teachers.index'],
            ['page' => 'Teachers - Create', 'route_name' => 'teachers.create'],
            ['page' => 'Teachers - Classes', 'route_name' => 'teachers.classes'],
            ['page' => 'Teachers - View Students', 'route_name' => 'teachers.view_student'],
            ['page' => 'Teachers - Edit', 'route_name' => 'teachers.edit'],
            ['page' => 'Teachers - View', 'route_name' => 'teachers.show'],

            // Admissions
            ['page' => 'Admissions - List', 'route_name' => 'admissions.index'],

            // Student Payment
            ['page' => 'Student Payment - Index', 'route_name' => 'student-payment.index'],
            ['page' => 'Student Payment - Create', 'route_name' => 'student-payment.create'],
            ['page' => 'Student Payment - Details', 'route_name' => 'student-payment.details'],

            // Student Attendance
            ['page' => 'Student Attendance - Index', 'route_name' => 'student_attendance.index'],
            ['page' => 'Student Attendance - Daily', 'route_name' => 'student_attendance.daily'],
            ['page' => 'Student Attendance - Details', 'route_name' => 'student_attendance.details'],

            // Payment Reason
            ['page' => 'Payment Reason - Index', 'route_name' => 'payment_reason.index'],

            // Reports
            ['page' => 'Reports - Index', 'route_name' => 'reports.index'],
            ['page' => 'Reports - Daily PDF', 'route_name' => 'reports.daily.pdf'],

            // Settings
            ['page' => 'Settings - Index', 'route_name' => 'settings.index'],

            // Teacher Payment
            ['page' => 'Teacher Payment - Index', 'route_name' => 'teacher_payment.index'],
            ['page' => 'Teacher Payment - Expenses', 'route_name' => 'teacher_payment.expenses'],
            ['page' => 'Teacher Payment - Salary', 'route_name' => 'teacher_payment.salary'],
            ['page' => 'Teacher Payment - History', 'route_name' => 'teacher_payment.history'],
            ['page' => 'Teacher Payment - View', 'route_name' => 'teacher_payment.view'],
            ['page' => 'Teacher Payment - Salary Slip', 'route_name' => 'teacher_payment.salary-slip-exact'],

            // Institute Payment
            ['page' => 'Institute Payment - Index', 'route_name' => 'institute_payment.index'],
            ['page' => 'Institute Payment - Extra Income', 'route_name' => 'institute_payment.extra'],
            ['page' => 'Institute Payment - Expenses', 'route_name' => 'institute_payment.expenses'],
            ['page' => 'Institute Payment - Ledger', 'route_name' => 'institute_payment.ledger'],

            // Receipt
            ['page' => 'Receipt - View', 'route_name' => 'receipt.view'],
            ['page' => 'Receipt - Download', 'route_name' => 'receipt.download'],
            ['page' => 'Receipt - Thermal Print', 'route_name' => 'receipt.thermal-print'],

            // Teacher Ledger Summary
            ['page' => 'Teacher Ledger Summary - Index', 'route_name' => 'teacher_ledger_summary.index'],
            ['page' => 'Teacher Ledger Summary - Export Excel', 'route_name' => 'teacher_ledger_summary.export.excel'],
            ['page' => 'Teacher Ledger Summary - Export PDF', 'route_name' => 'teacher_ledger_summary.export.pdf'],

            ['page' => 'Permission - View', 'route_name' => 'permission.index'],
            ['page' => 'Exam - View', 'route_name' => 'student_exam.index'],
            ['page' => 'Exam - create', 'route_name' => 'student_exam.create'],
            ['page' => 'Exam - Enter Marks', 'route_name' => 'student_exam.enter-marks'],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->insert([
                'page' => $page['page'],
                'route_name' => $page['route_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}