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
        Schema::table('insurance_customers', function (Blueprint $table) {
            $table->string('policy_no')->nullable()->after('permanent_address'); // Make 'policy_no' nullable
            $table->date('policy_start')->nullable()->after('policy_no'); // Make 'policy_start' nullable
            $table->date('policy_end')->nullable()->after('policy_start'); // Make 'policy_end' nullable
            $table->string('insurance_type')->nullable()->after('policy_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_customers', function (Blueprint $table) {
            $table->dropColumn('policy_no'); // Make 'policy_no' non-nullable again
            $table->dropColumn('policy_start'); // Make 'policy_start' non-nullable again
            $table->dropColumn('policy_end'); // Make 'policy_end' non-nullable again
            $table->dropColumn('insurance_type');
        });
    }
};
