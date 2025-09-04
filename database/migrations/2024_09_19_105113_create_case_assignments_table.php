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
        Schema::create('case_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('executive_driver');
            $table->unsignedBigInteger('executive_garage');
            $table->unsignedBigInteger('executive_spot');
            $table->unsignedBigInteger('executive_meeting');
            $table->unsignedBigInteger('executive_accident_person')->nullable();
            $table->string('date');
            $table->string('type');
            $table->text('other')->nullable();
            $table->string('status');
            $table->string('case_status');
            $table->string('create_by');
            $table->string('update_by');
            $table->timestamps();

            $table->foreign('executive_driver')->references('id')->on('users');
            $table->foreign('executive_garage')->references('id')->on('users');
            $table->foreign('executive_spot')->references('id')->on('users');
            $table->foreign('executive_meeting')->references('id')->on('users');
            $table->foreign('executive_accident_person')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('insurance_companies');
            $table->foreign('customer_id')->references('id')->on('insurance_customers');
            $table->foreign('case_id')->references('id')->on('insurance_cases');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_assignments');
    }
};
