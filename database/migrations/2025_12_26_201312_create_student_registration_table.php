<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateStudentRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->date('registration_date');

            // Snapshot values at registration time
            $table->decimal('course_total_fee', 10, 2)->default(0.00);
            $table->decimal('course_compulsory_amount', 10, 2)->default(0.00);
            $table->decimal('course_monthly_amount', 10, 2)->default(0.00);
            $table->unsignedInteger('course_total_months');

            $table->boolean('compulsory_paid')->default(false);
            $table->date('compulsory_paid_date')->nullable();

            $table->unsignedInteger('months_paid')->default(0);

            $table->enum('payment_status', [
                'pending',
                'active',
                'completed',
                'overdue',
                'cancelled'
            ])->default('pending');

            $table->enum('registration_status', [
                'registered',
                'in_progress',
                'completed',
                'dropped'
            ])->default('registered');

            $table->text('notes')->nullable();

            $table->date('course_start_date')->nullable();
            $table->date('course_end_date')->nullable();
            $table->date('next_payment_date')->nullable();

            $table->timestamps();

            $table->unique(['student_id', 'course_id'], 'unique_student_course');
            $table->index(['student_id', 'course_id']);
            $table->index('payment_status');
            $table->index('registration_status');
        });

        // generated columns
        DB::statement("
            ALTER TABLE student_registrations
            ADD COLUMN months_remaining INT
            GENERATED ALWAYS AS (
                CASE
                    WHEN course_total_months - months_paid >= 0
                    THEN course_total_months - months_paid
                    ELSE 0
                END
            ) VIRTUAL
        ");

        DB::statement("
            ALTER TABLE student_registrations
            ADD COLUMN remaining_balance DECIMAL(10,2)
            GENERATED ALWAYS AS (
                CASE
                    WHEN compulsory_paid = 1
                    THEN GREATEST(course_total_fee - course_compulsory_amount - (months_paid * course_monthly_amount), 0)
                    ELSE GREATEST(course_total_fee - (months_paid * course_monthly_amount), 0)
                END
            ) STORED
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_registrations');
    }
}