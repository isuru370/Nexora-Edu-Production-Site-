<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToStudentsPaymentsAndAttendancesTables extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->index('mobile');
            $table->index('guardian_mobile');
            $table->index('grade_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('payment_date');
            $table->index(['student_id', 'payment_date']);
            $table->index(['student_student_student_classes_id', 'payment_date']);
        });

        Schema::table('student_attendances', function (Blueprint $table) {
            $table->index(['student_id', 'at_date']);
            $table->index(['student_student_student_classes_id', 'at_date']);

            // prevent duplicate attendance per session
            $table->unique(['student_id', 'attendance_id']);
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['mobile']);
            $table->dropIndex(['guardian_mobile']);
            $table->dropIndex(['grade_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['payment_date']);
            $table->dropIndex(['student_id', 'payment_date']);
            $table->dropIndex(['student_student_student_classes_id', 'payment_date']);
        });

        Schema::table('student_attendances', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'at_date']);
            $table->dropIndex(['student_student_student_classes_id', 'at_date']);
            $table->dropUnique(['student_id', 'attendance_id']);
        });
    }
}