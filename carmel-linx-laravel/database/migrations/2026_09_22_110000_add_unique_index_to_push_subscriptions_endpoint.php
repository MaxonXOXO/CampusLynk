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
        if (!Schema::hasTable('push_subscriptions')) {
            Schema::create('push_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->string('user_id', 50)->index();
                $table->string('role', 20)->index();
                $table->string('endpoint', 500)->unique();
                $table->text('p256dh_key')->nullable();
                $table->text('auth_key')->nullable();
                $table->string('device_type', 30)->default('mobile');
                $table->timestamps();
            });
        } else {
            Schema::table('push_subscriptions', function (Blueprint $table) {
                $table->string('endpoint', 500)->change();
                $table->unique('endpoint', 'push_subscriptions_endpoint_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};
