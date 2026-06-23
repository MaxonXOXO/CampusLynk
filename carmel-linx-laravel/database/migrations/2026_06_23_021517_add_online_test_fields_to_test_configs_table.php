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
        Schema::table('test_configs', function (Blueprint $table) {
            $table->integer('max_attempts')->default(1)->after('pass_threshold');
            $table->boolean('is_auto_scheduled')->default(false)->after('max_attempts');
            $table->json('questions_payload')->nullable()->after('is_auto_scheduled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_configs', function (Blueprint $table) {
            $table->dropColumn(['max_attempts', 'is_auto_scheduled', 'questions_payload']);
        });
    }
};
