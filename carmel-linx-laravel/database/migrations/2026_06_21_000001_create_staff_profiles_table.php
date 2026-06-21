<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('mobile_no', 15)->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('branch', 50);
            $table->string('designation', 100);
            $table->string('password');
            $table->text('photo_url')->nullable();
            $table->string('account_status', 50)->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
