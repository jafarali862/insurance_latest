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
        Schema::create('insurance_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('father_name');
            $table->string('phone');
            $table->string('emergency_contact_number');
            $table->string('email');
            $table->text('present_address');
            $table->text('permanent_address');
            $table->string('status');
            $table->string('create_by');
            $table->string('update_by');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('insurance_companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_customers');
    }
};
