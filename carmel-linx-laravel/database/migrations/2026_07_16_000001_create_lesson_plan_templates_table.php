<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_plan_templates', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code', 50)->index();
            $table->integer('day_no');
            $table->string('co_id', 20)->nullable();
            $table->text('topic_content');
            $table->string('pedagogy', 100)->default('Lecture');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_plan_templates');
    }
};
