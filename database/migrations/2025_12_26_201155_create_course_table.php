<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 20)->unique();
            $table->string('course_name', 100);
            $table->text('description')->nullable();
            $table->decimal('total_fee', 10, 2)->default(0.00);
            $table->decimal('compulsory_payment', 10, 2)->default(0.00);
            $table->integer('duration_months');
            $table->decimal('monthly_payment', 10, 2)->storedAs('(total_fee - compulsory_payment) / duration_months');
            $table->string('lecturer', 100)->nullable();
            $table->string('department', 50)->nullable();
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->integer('max_students')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course');
    }
}