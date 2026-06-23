<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('question_bank', function (Blueprint $table) {
            $table->json('rubric')->nullable()->after('marks'); // Marking scheme / rubric breakdown
            $table->string('part_type', 5)->nullable()->after('type'); // A, B, C
            $table->string('cognitive_level', 5)->nullable()->after('part_type'); // U, R, A
        });
    }

    public function down(): void
    {
        Schema::table('question_bank', function (Blueprint $table) {
            $table->dropColumn(['rubric', 'part_type', 'cognitive_level']);
        });
    }
};
