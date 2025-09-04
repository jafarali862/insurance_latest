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
        Schema::create('assign_work_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->string('garage_reassign_status')->nullable();
            $table->string('garage_re_assign_count')->nullable();
            $table->string('driver_reassign_status')->nullable();
            $table->string('driver_re_assign_count')->nullable();
            $table->string('spot_reassign_status')->nullable();
            $table->string('spot_re_assign_count')->nullable();
            $table->string('owner_reassign_status')->nullable();
            $table->string('owner_re_assign_count')->nullable();
            $table->string('accident_person_reassign_status')->nullable();
            $table->string('accident_person_re_assign_count')->nullable();
            $table->timestamps();

            $table->foreign('case_id')->references('id')->on('case_assignments');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_work_data');
    }
};
