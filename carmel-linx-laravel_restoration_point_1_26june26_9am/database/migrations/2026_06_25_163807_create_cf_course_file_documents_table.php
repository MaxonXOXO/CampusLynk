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
        Schema::create('cf_course_file_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_file_id')->constrained('cf_course_files')->onDelete('cascade');
            $table->integer('document_number');
            $table->string('document_name');
            $table->boolean('is_checked')->default(false);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cf_course_file_documents');
    }
};
