<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Campus Geofence Settings
        if (!Schema::hasTable('sf_campus_geofence_settings')) {
            Schema::create('sf_campus_geofence_settings', function (Blueprint $table) {
                $table->id();
                $table->string('campus_name', 100)->default('Carmel Main Campus');
                $table->decimal('centroid_lat', 10, 8)->default(10.23120000);
                $table->decimal('centroid_lng', 11, 8)->default(76.20450000);
                $table->integer('radius_meters')->default(150);
                $table->integer('max_accuracy_meters')->default(30);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Insert initial default campus record
            DB::table('sf_campus_geofence_settings')->insert([
                'campus_name' => 'Carmel College Campus',
                'centroid_lat' => 10.23120000,
                'centroid_lng' => 76.20450000,
                'radius_meters' => 150,
                'max_accuracy_meters' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. SF Staff Face Registrations
        if (!Schema::hasTable('sf_staff_face_registrations')) {
            Schema::create('sf_staff_face_registrations', function (Blueprint $table) {
                $table->id();
                $table->string('staff_id', 50)->unique();
                $table->string('mobile_no', 20)->nullable();
                $table->string('staff_name', 150)->nullable();
                $table->longText('face_descriptor'); // JSON 128-float embedding
                $table->string('photo_url', 255)->nullable();
                $table->timestamps();
            });
        }

        // 3. SF Staff Time Punches
        if (!Schema::hasTable('sf_staff_time_punches')) {
            Schema::create('sf_staff_time_punches', function (Blueprint $table) {
                $table->id();
                $table->string('staff_id', 50);
                $table->string('staff_name', 150)->nullable();
                $table->date('punch_date');
                $table->time('in_time')->nullable();
                $table->time('out_time')->nullable();
                $table->decimal('in_gps_lat', 10, 8)->nullable();
                $table->decimal('in_gps_lng', 11, 8)->nullable();
                $table->integer('in_gps_distance_meters')->nullable();
                $table->enum('in_premises_status', ['INSIDE_PREMISES', 'OUTSIDE_PREMISES'])->default('INSIDE_PREMISES');
                $table->decimal('out_gps_lat', 10, 8)->nullable();
                $table->decimal('out_gps_lng', 11, 8)->nullable();
                $table->integer('out_gps_distance_meters')->nullable();
                $table->enum('out_premises_status', ['INSIDE_PREMISES', 'OUTSIDE_PREMISES'])->default('INSIDE_PREMISES');
                $table->string('liveness_type', 20)->default('SMILE');
                $table->decimal('liveness_score', 5, 2)->default(0.85);
                $table->decimal('biometric_confidence', 5, 2)->default(95.00);
                $table->string('punch_status', 100)->default('PRESENT');
                $table->string('in_snapshot_url', 255)->nullable();
                $table->string('out_snapshot_url', 255)->nullable();
                $table->string('remarks', 255)->nullable();
                $table->timestamps();

                $table->unique(['staff_id', 'punch_date'], 'sf_staff_daily_punch_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sf_staff_time_punches');
        Schema::dropIfExists('sf_staff_face_registrations');
        Schema::dropIfExists('sf_campus_geofence_settings');
    }
};
