<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 20)->unique();
            $table->string('course_name', 100);
            $table->decimal('teacher_percentage', 5, 2)->default(100.00);
            $table->text('description')->nullable();

            $table->decimal('total_fee', 10, 2)->default(0.00);
            $table->decimal('compulsory_payment', 10, 2)->default(0.00);
            $table->unsignedInteger('duration_months');

            $table->foreignId('teacher_id')->constrained('teachers')->restrictOnDelete();

            $table->string('department', 50)->nullable();
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->unsignedInteger('max_students')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps();
        });

        DB::statement("
            ALTER TABLE courses
            ADD COLUMN monthly_payment DECIMAL(10,2)
            GENERATED ALWAYS AS (
                CASE
                    WHEN duration_months > 0
                    THEN ROUND((total_fee - compulsory_payment) / duration_months, 2)
                    ELSE 0
                END
            ) STORED
        ");
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
