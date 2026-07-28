<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('r26_drawing_course_files')) {
            Schema::table('r26_drawing_course_files', function (Blueprint $table) {
                if (!Schema::hasColumn('r26_drawing_course_files', 'series_test_qps')) {
                    $table->json('series_test_qps')->nullable()->after('self_learning_configs');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('r26_drawing_course_files')) {
            Schema::table('r26_drawing_course_files', function (Blueprint $table) {
                if (Schema::hasColumn('r26_drawing_course_files', 'series_test_qps')) {
                    $table->dropColumn('series_test_qps');
                }
            });
        }
    }
};
