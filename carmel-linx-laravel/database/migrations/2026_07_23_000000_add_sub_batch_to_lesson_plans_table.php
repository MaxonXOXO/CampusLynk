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
        if (Schema::hasTable('lesson_plans') && !Schema::hasColumn('lesson_plans', 'sub_batch')) {
            Schema::table('lesson_plans', function (Blueprint $table) {
                $table->string('sub_batch', 20)->default('Whole')->after('pedagogy');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lesson_plans') && Schema::hasColumn('lesson_plans', 'sub_batch')) {
            Schema::table('lesson_plans', function (Blueprint $table) {
                $table->dropColumn('sub_batch');
            });
        }
    }
};
