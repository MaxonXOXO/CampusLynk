<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sbte_audit_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('academic_year', 20);
            $table->string('document_name', 150);
            $table->string('status', 30)->default('Pending'); // Pending, Uploaded, Verified
            $table->string('file_path', 255)->nullable();
            $table->string('uploaded_by', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('nba_criteria_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('criteria_no'); // 1 to 10
            $table->string('document_name', 150);
            $table->string('academic_year', 20);
            $table->string('status', 30)->default('Pending'); // Pending, Uploaded, Verified
            $table->string('file_path', 255)->nullable();
            $table->string('uploaded_by', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sbte_audit_documents');
        Schema::dropIfExists('nba_criteria_documents');
    }
};
