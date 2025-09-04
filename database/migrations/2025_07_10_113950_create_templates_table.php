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
    Schema::create('templates', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('company_id');
    $table->string('table_name');
    $table->json('fields'); // Save fields info as JSON for easy editing
    $table->timestamps();
    });
    
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
