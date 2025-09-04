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
        Schema::create('final_reports', function (Blueprint $table) {
            $table->id();  
            $table->unsignedBigInteger('case_id')->nullable(); 
            $table->timestamps();
            $table->foreign('case_id')->references('id')->on('case_assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_reports');
    }
};
