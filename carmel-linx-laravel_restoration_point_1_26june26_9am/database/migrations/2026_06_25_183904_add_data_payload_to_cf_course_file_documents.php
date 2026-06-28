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
        Schema::table('cf_course_file_documents', function (Blueprint $table) {
            $table->json('data_payload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cf_course_file_documents', function (Blueprint $table) {
            $table->dropColumn('data_payload');
        });
    }
};
