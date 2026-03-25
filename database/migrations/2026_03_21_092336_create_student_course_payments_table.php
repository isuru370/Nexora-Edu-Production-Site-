<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentCoursePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_course_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registration_id')
                ->constrained('student_registrations')
                ->cascadeOnDelete();

            $table->enum('payment_type', [
                'compulsory',
                'monthly',
                'full',
                'partial',
                'late_fee',
                'other'
            ])->default('monthly');

            $table->decimal('expected_amount', 10, 2)->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0.00);

            $table->decimal('balance_before', 10, 2)->default(0.00);
            $table->decimal('balance_after', 10, 2)->default(0.00);

            $table->date('payment_date');
            $table->date('due_date')->nullable();

            $table->string('month_year', 7)->nullable(); // example: 2026-03
            $table->unsignedInteger('month_number')->nullable();

            $table->enum('payment_method', [
                'cash',
                'card',
                'bank_transfer',
                'online',
                'cheque',
                'other'
            ])->default('cash');

            $table->string('transaction_id', 100)->nullable();
            $table->string('receipt_number', 50)->nullable();

            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            $table->enum('status', [
                'pending',
                'completed',
                'failed',
                'refunded'
            ])->default('completed');

            $table->string('collected_by', 100)->nullable();
            $table->string('verified_by', 100)->nullable();
            $table->dateTime('verified_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique('transaction_id');
            $table->unique('receipt_number');

            $table->index('registration_id');
            $table->index(['payment_date', 'status']);
            $table->index('month_year');
            $table->index('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_course_payments');
    }
}