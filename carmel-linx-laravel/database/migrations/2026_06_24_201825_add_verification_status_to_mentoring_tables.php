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
        Schema::table('students', function (Blueprint $table) {
            $table->timestamp('profile_verified_at')->nullable();
            $table->string('profile_verified_by', 15)->nullable();
            $table->foreign('profile_verified_by')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['profile_verified_by']);
            $table->dropColumn(['profile_verified_at', 'profile_verified_by']);
        });
    }
};
