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
        Schema::table('syllabus_registry', function (Blueprint $table) {
            $table->json('co_po_mapping')->nullable()->after('cis_pdf_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syllabus_registry', function (Blueprint $table) {
            $table->dropColumn('co_po_mapping');
        });
    }
};
