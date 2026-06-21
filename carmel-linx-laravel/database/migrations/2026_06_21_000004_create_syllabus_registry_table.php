<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabus_registry', function (Blueprint $table) {
            $table->string('subject_code', 50)->primary();
            $table->integer('revision_year');
            $table->string('subject_name');
            $table->integer('co_count')->default(6);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabus_registry');
    }
};
