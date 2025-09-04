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
        Schema::create('insurance_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('insurance_type');
            $table->text('case_details');
            $table->string('status');
            $table->string('assigned_status')->nullable();
            $table->string('case_status');
            $table->string('create_by');
            $table->string('update_by');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('insurance_companies');
            $table->foreign('customer_id')->references('id')->on('insurance_customers');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_cases');
    }
};
