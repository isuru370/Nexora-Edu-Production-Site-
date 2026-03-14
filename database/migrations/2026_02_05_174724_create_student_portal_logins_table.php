<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentPortalLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_portal_logins', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');

            $table->string('username')->unique();
            $table->string('password');
            $table->boolean('is_verify')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('otp', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();

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
        Schema::dropIfExists('student_portal_logins');
    }
}
