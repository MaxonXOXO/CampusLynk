<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batch_subjects', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
        });

        Schema::table('test_configs', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
        });

        Schema::table('mentoring_batches', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
        });
    }

    public function down(): void
    {
        Schema::table('batch_subjects', function (Blueprint $table) {
            $table->foreign('classroom_id')->references('classroom_id')->on('class_management')->onDelete('cascade');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('classroom_id')->references('classroom_id')->on('class_management')->onDelete('set null');
        });

        Schema::table('test_configs', function (Blueprint $table) {
            $table->foreign('classroom_id')->references('classroom_id')->on('class_management')->onDelete('cascade');
        });

        Schema::table('mentoring_batches', function (Blueprint $table) {
            $table->foreign('classroom_id')->references('classroom_id')->on('class_management')->onDelete('cascade');
        });
    }
};
