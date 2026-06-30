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
        Schema::table('question_bank', function (Blueprint $table) {
            if (!Schema::hasColumn('question_bank', 'batch_subject_id')) {
                $table->unsignedInteger('batch_subject_id')->nullable()->after('subject_code');
                $table->index('batch_subject_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_bank', function (Blueprint $table) {
            if (Schema::hasColumn('question_bank', 'batch_subject_id')) {
                $table->dropIndex(['batch_subject_id']);
                $table->dropColumn(['batch_subject_id']);
            }
        });
    }
};
