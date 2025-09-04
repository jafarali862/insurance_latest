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
        Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->string('question');
        $table->string('input_type'); // e.g., text, file, select, etc.
        $table->string('data_category'); // e.g., garage_data, driver_data
        $table->string('column_name'); // e.g., question_1
        $table->string('unique_key'); // e.g., question_1
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
