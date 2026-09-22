<?php

namespace Tests\Feature;

use App\Models\StaffBirthdayWish;
use App\Models\StaffProfile;
use App\Services\StaffBirthdayService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StaffBirthdayTest extends TestCase
{
    use RefreshDatabase;

    private function createStaff(array $overrides = []): StaffProfile
    {
        return StaffProfile::create(array_merge([
            'mobile_no' => '98' . rand(10000000, 99999999),
            'name' => 'Prof. Alan Turing',
            'email' => 'turing_' . uniqid() . '@example.com',
            'password' => 'secret',
            'designation' => 'Assistant_Professor',
            'branch' => 'Computer Engineering',
            'account_status' => 'Approved',
            'dob' => Carbon::today()->format('1985-m-d'),
        ], $overrides));
    }

    /**
     * Test get today birthdays when no staff members celebrate birthday today.
     */
    public function test_get_today_birthdays_when_no_birthdays(): void
    {
        // Staff born on a different month/day
        $this->createStaff([
            'dob' => Carbon::today()->addMonths(3)->format('1990-m-d'),
        ]);

        $response = $this->getJson('/api/staff/birthdays/today');
        
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'has_birthdays' => false,
                'celebrants' => [],
            ]);
    }

    /**
     * Test get today birthdays returns eligible active celebrants.
     */
    public function test_get_today_birthdays_returns_active_celebrants(): void
    {
        $celebrant = $this->createStaff([
            'name' => 'Dr. Grace Hopper',
            'mobile_no' => '9123456780',
            'dob' => Carbon::today()->format('1980-m-d'),
        ]);

        // Inactive staff member with birthday today should be excluded
        $this->createStaff([
            'name' => 'Inactive Staff',
            'mobile_no' => '9123456789',
            'account_status' => 'Pending',
            'dob' => Carbon::today()->format('1982-m-d'),
        ]);

        $response = $this->getJson('/api/staff/birthdays/today');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'has_birthdays' => true,
            ]);

        $celebrants = $response->json('celebrants');
        $this->assertCount(1, $celebrants);
        $this->assertEquals('9123456780', $celebrants[0]['mobile_no']);
        $this->assertEquals('Dr. Grace Hopper', $celebrants[0]['name']);
    }

    /**
     * Test unauthenticated requests cannot send birthday wishes.
     */
    public function test_unauthenticated_user_cannot_send_wish(): void
    {
        $response = $this->postJson('/api/staff/birthdays/wish', [
            'celebrant_mobile_no' => '9123456780',
            'emoji' => '🎂',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'ERROR',
                'message' => 'Active staff session required to send wishes.'
            ]);
    }

    /**
     * Test authenticated staff can send birthday wish idempotently.
     */
    public function test_authenticated_staff_can_send_birthday_wish_idempotently(): void
    {
        $celebrant = $this->createStaff([
            'mobile_no' => '9123456780',
            'dob' => Carbon::today()->format('1980-m-d'),
        ]);

        $sender = $this->createStaff([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Sender',
            'dob' => Carbon::today()->addMonths(2)->format('1985-m-d'),
        ]);

        // First wish
        $response1 = $this->withSession([
            'userId' => $sender->mobile_no,
            'userName' => $sender->name,
            'userRole' => 'Lecturer',
        ])->postJson('/api/staff/birthdays/wish', [
            'celebrant_mobile_no' => $celebrant->mobile_no,
            'emoji' => '🎂',
            'message' => 'Happy Birthday!',
        ]);

        $response1->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'has_birthdays' => true,
            ]);

        $this->assertDatabaseHas('staff_birthday_wishes', [
            'celebrant_mobile_no' => $celebrant->mobile_no,
            'sender_mobile_no' => $sender->mobile_no,
            'emoji' => '🎂',
            'message' => 'Happy Birthday!',
        ]);

        // Duplicate wish on same day - should remain idempotent (single record)
        $response2 = $this->withSession([
            'userId' => $sender->mobile_no,
            'userName' => $sender->name,
            'userRole' => 'Lecturer',
        ])->postJson('/api/staff/birthdays/wish', [
            'celebrant_mobile_no' => $celebrant->mobile_no,
            'emoji' => '🎉',
            'message' => 'Another wish!',
        ]);

        $response2->assertStatus(200);

        $wishesCount = StaffBirthdayWish::where('celebrant_mobile_no', $celebrant->mobile_no)
            ->where('sender_mobile_no', $sender->mobile_no)
            ->where('wish_date', Carbon::today()->toDateString())
            ->count();

        $this->assertEquals(1, $wishesCount);
    }

    /**
     * Test sending wish to ineligible staff member (whose birthday is NOT today) is rejected.
     */
    public function test_send_wish_to_ineligible_staff_rejected(): void
    {
        $nonCelebrant = $this->createStaff([
            'mobile_no' => '9999999999',
            'dob' => Carbon::today()->addDays(5)->format('1980-m-d'),
        ]);

        $sender = $this->createStaff([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Sender',
        ]);

        $response = $this->withSession([
            'userId' => $sender->mobile_no,
            'userName' => $sender->name,
        ])->postJson('/api/staff/birthdays/wish', [
            'celebrant_mobile_no' => $nonCelebrant->mobile_no,
            'emoji' => '🎂',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'ERROR',
            ]);
    }

    /**
     * Test validation fails when both emoji and message are empty.
     */
    public function test_empty_emoji_and_message_rejected(): void
    {
        $celebrant = $this->createStaff([
            'mobile_no' => '9123456780',
            'dob' => Carbon::today()->format('1980-m-d'),
        ]);

        $sender = $this->createStaff([
            'mobile_no' => '9876543210',
        ]);

        $response = $this->withSession([
            'userId' => $sender->mobile_no,
            'userName' => $sender->name,
        ])->postJson('/api/staff/birthdays/wish', [
            'celebrant_mobile_no' => $celebrant->mobile_no,
            'emoji' => '',
            'message' => '',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'ERROR',
                'message' => 'Please select an emoji or write a wish message.'
            ]);
    }

    /**
     * Test staff can update their own date of birth.
     */
    public function test_staff_can_update_own_dob(): void
    {
        $staff = $this->createStaff([
            'mobile_no' => '9876543210',
            'dob' => '1990-01-01',
        ]);

        $response = $this->withSession([
            'userId' => $staff->mobile_no,
        ])->postJson('/api/staff/profile/update-dob', [
            'dob' => '1992-06-15',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'dob' => '1992-06-15',
            ]);

        $this->assertDatabaseHas('staff_profiles', [
            'mobile_no' => $staff->mobile_no,
            'dob' => '1992-06-15',
        ]);
    }

    /**
     * Test unique constraint on staff_birthday_wishes is enforced at database level.
     */
    public function test_birthday_wishes_unique_constraint_enforced_at_db_level(): void
    {
        $today = Carbon::today()->toDateString();

        DB::table('staff_birthday_wishes')->insert([
            'wish_date' => $today,
            'celebrant_mobile_no' => 'CEL-001',
            'sender_mobile_no' => 'SND-001',
            'sender_name' => 'Sender',
            'emoji' => '🎂',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->expectException(QueryException::class);

        // Second raw insert with identical (wish_date, celebrant_mobile_no, sender_mobile_no)
        DB::table('staff_birthday_wishes')->insert([
            'wish_date' => $today,
            'celebrant_mobile_no' => 'CEL-001',
            'sender_mobile_no' => 'SND-001',
            'sender_name' => 'Sender Duplicate',
            'emoji' => '🎉',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
