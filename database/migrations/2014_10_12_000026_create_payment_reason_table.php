<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreatePaymentReasonTable extends Migration
{
    public function up()
    {
        Schema::create('payment_reason', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reason_code')->unique(); // reason_code unique කරන්න
            $table->string('reason');
            $table->timestamps();
        });

        DB::table('payment_reason')->insert([
            [
                'reason_code' => 'salary',
                'reason' => 'Teacher Monthly Payment',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_reason');
    }
}
