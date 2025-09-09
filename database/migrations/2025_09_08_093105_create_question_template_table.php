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
        Schema::create('question_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                  ->constrained('questions')
                  ->onDelete('cascade');

            $table->foreignId('template_id')
                  ->constrained('templates')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_template');
    }
};
