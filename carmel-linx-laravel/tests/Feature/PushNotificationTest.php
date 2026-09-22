<?php

namespace Tests\Feature;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class PushNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test VAPID public key endpoint returns 503 when not configured.
     */
    public function test_vapid_endpoint_returns_error_when_unconfigured(): void
    {
        Config::set('services.vapid.public_key', null);

        $response = $this->getJson('/api/notifications/vapid-key');

        $response->assertStatus(503)
            ->assertJson([
                'status' => 'ERROR',
                'message' => 'VAPID public key is not configured.'
            ]);
    }

    /**
     * Test VAPID public key endpoint returns public key and never exposes private key.
     */
    public function test_vapid_endpoint_returns_only_public_key_and_never_private_key(): void
    {
        $testPublicKey = 'BNc8v...testPublicKey...';
        $testPrivateKey = 'SECRET_NEVER_EXPOSE_PRIVATE_KEY';

        Config::set('services.vapid.public_key', $testPublicKey);
        Config::set('services.vapid.private_key', $testPrivateKey);

        $response = $this->getJson('/api/notifications/vapid-key');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'publicKey' => $testPublicKey,
            ]);

        $this->assertStringNotContainsString($testPrivateKey, $response->getContent());
    }

    /**
     * Test unauthenticated requests cannot subscribe to push notifications.
     */
    public function test_unauthenticated_user_cannot_subscribe(): void
    {
        $response = $this->postJson('/api/notifications/subscribe', [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/test-endpoint-unauth',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'ERROR',
                'message' => 'Unauthenticated session.'
            ]);
    }

    /**
     * Test authenticated user can subscribe and duplicate subscription is idempotent.
     */
    public function test_authenticated_user_can_subscribe_with_idempotent_upsert(): void
    {
        $endpoint = 'https://fcm.googleapis.com/fcm/send/test-endpoint-idempotent-' . uniqid();

        // First subscription
        $response1 = $this->withSession([
            'userId' => 'STF-TEST-001',
            'userRole' => 'Lecturer',
        ])->postJson('/api/notifications/subscribe', [
            'endpoint' => $endpoint,
            'p256dh_key' => 'initial-p256dh-key',
            'auth_key' => 'initial-auth-key',
            'device_type' => 'desktop',
        ]);

        $response1->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'message' => 'Push notification subscription registered successfully.'
            ]);

        $this->assertDatabaseHas('push_subscriptions', [
            'user_id' => 'STF-TEST-001',
            'role' => 'staff',
            'endpoint' => $endpoint,
            'auth_key' => 'initial-auth-key',
            'device_type' => 'desktop',
        ]);

        // Re-subscribe with updated keys on the same endpoint
        $response2 = $this->withSession([
            'userId' => 'STF-TEST-001',
            'userRole' => 'Lecturer',
        ])->postJson('/api/notifications/subscribe', [
            'endpoint' => $endpoint,
            'p256dh_key' => 'updated-p256dh-key',
            'auth_key' => 'updated-auth-key',
            'device_type' => 'mobile',
        ]);

        $response2->assertStatus(200);

        // Verify that the record was updated idempotently and no duplicate row was created
        $records = PushSubscription::where('endpoint', $endpoint)->get();
        $this->assertCount(1, $records);
        $this->assertEquals('updated-auth-key', $records->first()->auth_key);
        $this->assertEquals('mobile', $records->first()->device_type);

        // Clean up test record
        PushSubscription::where('endpoint', $endpoint)->delete();
    }

    /**
     * Test client cannot spoof role during subscription.
     */
    public function test_client_cannot_spoof_role_during_subscription(): void
    {
        $endpoint = 'https://fcm.googleapis.com/fcm/send/test-endpoint-spoof-' . uniqid();

        // Student session attempting to send 'staff' in payload
        $response = $this->withSession([
            'userId' => '2401001',
            'userRole' => 'Student',
        ])->postJson('/api/notifications/subscribe', [
            'endpoint' => $endpoint,
            'role' => 'staff', // Spoofed client input
            'p256dh_key' => 'test-key',
            'auth_key' => 'test-auth',
        ]);

        $response->assertStatus(200);

        // Verify the database recorded 'student', completely ignoring the client's 'staff' input
        $this->assertDatabaseHas('push_subscriptions', [
            'user_id' => '2401001',
            'role' => 'student',
            'endpoint' => $endpoint,
        ]);

        PushSubscription::where('endpoint', $endpoint)->delete();
    }

    /**
     * Test unauthorized user cannot broadcast push notifications.
     */
    public function test_unauthorized_user_cannot_broadcast(): void
    {
        // Student attempting broadcast
        $response = $this->withSession([
            'userId' => '2401001',
            'userRole' => 'Student',
        ])->postJson('/api/notifications/broadcast', [
            'title' => 'Unauthorized Alert',
            'body' => 'This should be blocked',
            'target' => 'all',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'ERROR',
                'message' => 'Unauthorized push sender.'
            ]);
    }

    /**
     * Test authorized staff role can broadcast.
     */
    public function test_authorized_staff_can_broadcast(): void
    {
        // Admin broadcast
        $response = $this->withSession([
            'userId' => 'ADMIN-001',
            'userRole' => 'Admin',
        ])->postJson('/api/notifications/broadcast', [
            'title' => 'College Notice',
            'body' => 'Classes suspended tomorrow.',
            'target' => 'all',
            'url' => '/circulars',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
            ]);
    }

    /**
     * Test endpoint uniqueness is enforced at database level.
     */
    public function test_endpoint_uniqueness_enforced_at_database_level(): void
    {
        $uniqueEndpoint = 'https://fcm.googleapis.com/fcm/send/unique-db-test-' . uniqid();

        PushSubscription::create([
            'user_id' => 'USER-1',
            'role' => 'staff',
            'endpoint' => $uniqueEndpoint,
        ]);

        $this->expectException(QueryException::class);

        // Direct raw insert to bypass Eloquent updateOrCreate and test DB unique constraint
        DB::table('push_subscriptions')->insert([
            'user_id' => 'USER-2',
            'role' => 'student',
            'endpoint' => $uniqueEndpoint,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Test PushSubscription query scopes for user and role.
     */
    public function test_push_subscription_scopes(): void
    {
        PushSubscription::create([
            'user_id' => 'STF-001',
            'role' => 'staff',
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/sub-staff-' . uniqid(),
        ]);

        PushSubscription::create([
            'user_id' => 'STU-001',
            'role' => 'student',
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/sub-student-' . uniqid(),
        ]);

        $this->assertCount(1, PushSubscription::forUser('STF-001')->get());
        $this->assertCount(1, PushSubscription::forRole('staff')->get());
        $this->assertCount(1, PushSubscription::forRole('student')->get());
    }

    /**
     * Test PushNotificationService handles unconfigured VAPID keys gracefully.
     */
    public function test_push_service_handles_unconfigured_vapid_gracefully(): void
    {
        Config::set('services.vapid.public_key', null);
        Config::set('services.vapid.private_key', null);

        $sent = PushNotificationService::notifyAll('Title', 'Body');
        $this->assertEquals(0, $sent);
    }
}
