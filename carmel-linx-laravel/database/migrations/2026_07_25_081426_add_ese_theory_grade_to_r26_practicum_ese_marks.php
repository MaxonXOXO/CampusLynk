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
        Schema::table('r26_practicum_ese_marks', function (Blueprint $table) {
            $table->string('ese_theory_grade', 10)->nullable()->after('ese_theory_marks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r26_practicum_ese_marks', function (Blueprint $table) {
            $table->dropColumn('ese_theory_grade');
        });
    }
};
