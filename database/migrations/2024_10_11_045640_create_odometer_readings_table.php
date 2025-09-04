<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('odometer_readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('check_in_km')->nullable();
            $table->string('check_in_image')->nullable();
            $table->string('check_in_time')->nullable();
            $table->string('check_in_date')->nullable();
            $table->string('check_in_latitude_and_longitude')->nullable();
            $table->string('check_out_km')->nullable();
            $table->string('check_out_image')->nullable();
            $table->string('check_out_time')->nullable();
            $table->string('check_out_date')->nullable();
            $table->string('check_out_latitude_and_longitude')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odometer_readings');
    }
};
