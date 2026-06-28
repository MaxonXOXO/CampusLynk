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
        Schema::table('batch_subjects', function (Blueprint $table) {
            $table->string('syllabus_revision_code', 20)->nullable()->default('REV2021')->after('subject_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_subjects', function (Blueprint $table) {
            $table->dropColumn('syllabus_revision_code');
        });
    }
};
