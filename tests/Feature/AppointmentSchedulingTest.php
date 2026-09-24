<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\EmailTemplate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AppointmentSchedulingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_appointment_page_loads_with_counseling_and_iets_test(): void
    {
        $response = $this->get('/appointments');
        $response->assertStatus(200);
        $response->assertSee('Campus Counseling');
        $response->assertSee('IETS Test');
        $response->assertSee('Appointment &amp; Test Booking Portal', false);
    }

    public function test_slots_api_returns_proper_capacity_and_fully_booked_status(): void
    {
        $response = $this->getJson('/appointments/slots?date=2026-09-25&type=iets_test');
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'date' => '2026-09-25',
            'type' => 'iets_test',
        ]);

        $slots = $response->json('slots');
        $this->assertIsArray($slots);
        $this->assertNotEmpty($slots);

        // Find 10:00 AM slot which was seeded with 15 bookings (fully booked)
        $slot1000 = collect($slots)->firstWhere('time', '10:00 AM');
        $this->assertNotNull($slot1000);
        $this->assertEquals(15, $slot1000['capacity']);
        $this->assertEquals(15, $slot1000['booked_count']);
        $this->assertEquals(0, $slot1000['remaining_seats']);
        $this->assertTrue($slot1000['is_full']);

        // Find 09:00 AM slot which was seeded with 8 bookings (7 remaining)
        $slot0900 = collect($slots)->firstWhere('time', '09:00 AM');
        $this->assertNotNull($slot0900);
        $this->assertEquals(15, $slot0900['capacity']);
        $this->assertEquals(8, $slot0900['booked_count']);
        $this->assertEquals(7, $slot0900['remaining_seats']);
        $this->assertFalse($slot0900['is_full']);
    }

    public function test_counseling_slots_automatically_generate_from_9am_to_6pm(): void
    {
        $response = $this->getJson('/appointments/slots?date=2026-09-25&type=counseling');
        $response->assertStatus(200);

        $slots = $response->json('slots');
        $this->assertIsArray($slots);
        $this->assertNotEmpty($slots);

        $times = collect($slots)->pluck('time')->toArray();
        $this->assertContains('09:00 AM', $times);
        $this->assertContains('09:30 AM', $times);
        $this->assertContains('10:00 AM', $times);
        $this->assertContains('05:30 PM', $times);

        // Check each counseling slot capacity is 1
        $firstSlot = $slots[0];
        $this->assertEquals(1, $firstSlot['capacity']);
    }

    public function test_iets_and_counseling_do_not_block_each_other_at_same_time(): void
    {
        // 09:00 AM has an IETS test slot
        $ietsResponse = $this->getJson('/appointments/slots?date=2026-09-25&type=iets_test');
        $ietsSlots = $ietsResponse->json('slots');
        $iets0900 = collect($ietsSlots)->firstWhere('time', '09:00 AM');
        $this->assertNotNull($iets0900);

        // 09:00 AM also has a counseling slot available and is not blocked by IETS
        $counselingResponse = $this->getJson('/appointments/slots?date=2026-09-25&type=counseling');
        $counselingSlots = $counselingResponse->json('slots');
        $counseling0900 = collect($counselingSlots)->firstWhere('time', '09:00 AM');
        $this->assertNotNull($counseling0900);
        $this->assertTrue($counseling0900['is_available']);
    }

    public function test_admin_cannot_create_overlapping_iets_test_slots(): void
    {
        $admin = User::where('email', 'admin@antiacademy.edu')->first();
        $this->assertNotNull($admin);

        // Attempt to create an overlapping slot on 2026-09-25: 09:30 AM to 10:30 AM
        // Existing slots are 09:00 AM - 10:00 AM and 10:00 AM - 11:00 AM
        $response = $this->actingAs($admin)->post('/admin/scheduling/slots', [
            'type' => 'iets_test',
            'slot_date' => '2026-09-25',
            'start_time' => '09:30 AM',
            'end_time' => '10:30 AM',
            'capacity' => 15,
            'duration_minutes' => 60,
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Overlap Conflict', session('error'));

        // Attempt another overlap with different spacing (e.g. 09:00AM)
        $response2 = $this->actingAs($admin)->post('/admin/scheduling/slots', [
            'type' => 'iets_test',
            'slot_date' => '2026-09-25',
            'start_time' => '09:00AM',
            'end_time' => '10:00AM',
            'capacity' => 15,
            'duration_minutes' => 60,
        ]);

        $response2->assertSessionHas('error');
    }

    public function test_exact_user_specification_intervals_and_non_blocking_counseling(): void
    {
        $admin = User::where('email', 'admin@antiacademy.edu')->first();
        $this->assertNotNull($admin);

        $testDate = '2026-10-15';
        // Clear any prior test artifacts on this specific test date
        AppointmentSlot::where('slot_date', $testDate)->delete();

        // 1. IETS: 15 Oct, 9:00 AM – 10:00 AM -> Allowed
        $r1 = $this->actingAs($admin)->post('/admin/scheduling/slots', [
            'type' => 'iets_test',
            'slot_date' => $testDate,
            'start_time' => '09:00 AM',
            'end_time' => '10:00 AM',
            'capacity' => 15,
            'duration_minutes' => 60,
        ]);
        $r1->assertSessionHas('success');
        $this->assertDatabaseHas('appointment_slots', [
            'slot_date' => $testDate,
            'start_time' => '09:00 AM',
            'end_time' => '10:00 AM',
            'type' => 'iets_test',
        ]);

        // 2. IETS: 15 Oct, 9:30 AM – 10:30 AM -> NOT Allowed because it overlaps
        $r2 = $this->actingAs($admin)->post('/admin/scheduling/slots', [
            'type' => 'iets_test',
            'slot_date' => $testDate,
            'start_time' => '09:30 AM',
            'end_time' => '10:30 AM',
            'capacity' => 15,
            'duration_minutes' => 60,
        ]);
        $r2->assertSessionHas('error');
        $this->assertDatabaseMissing('appointment_slots', [
            'slot_date' => $testDate,
            'start_time' => '09:30 AM',
            'type' => 'iets_test',
        ]);

        // 3. IETS: 15 Oct, 10:00 AM – 11:00 AM -> Allowed because it starts after the previous slot ends
        $r3 = $this->actingAs($admin)->post('/admin/scheduling/slots', [
            'type' => 'iets_test',
            'slot_date' => $testDate,
            'start_time' => '10:00 AM',
            'end_time' => '11:00 AM',
            'capacity' => 15,
            'duration_minutes' => 60,
        ]);
        $r3->assertSessionHas('success');
        $this->assertDatabaseHas('appointment_slots', [
            'slot_date' => $testDate,
            'start_time' => '10:00 AM',
            'end_time' => '11:00 AM',
            'type' => 'iets_test',
        ]);

        // 4. Counseling: 15 Oct, 9:00 AM -> Also Allowed! (Separated system, does not conflict with 9:00-10:00 IETS)
        $counselingSlots = app(\App\Services\AppointmentSlotService::class)->getSlotsForType($testDate, 'counseling');
        $counseling0900 = collect($counselingSlots)->firstWhere('time', '09:00 AM');
        $this->assertNotNull($counseling0900);
        $this->assertTrue($counseling0900['is_available']);
    }

    public function test_student_can_book_available_iets_slot_and_receives_unique_registration_number(): void
    {
        $slot = AppointmentSlot::where('slot_date', '2026-09-25')
            ->where('start_time', '11:30 AM')
            ->where('type', 'iets_test')
            ->first();

        $this->assertNotNull($slot);
        $initialBooked = $slot->booked_count;

        $email = 'newcandidate_' . time() . '@example.com';
        $postData = [
            'appointment_type' => 'iets_test',
            'test_type' => 'IELTS Academic Test',
            'slot_id' => $slot->id,
            'appointment_date' => '2026-09-25',
            'time_slot' => '11:30 AM',
            'name' => 'John Candidate',
            'email' => $email,
            'phone' => '+1 555 444 3322',
            'whatsapp' => '+1 555 444 3322',
            'cnic_passport' => '42201-9988776-5',
            'program' => 'Master of Science',
        ];

        $response = $this->post('/appointments/book', $postData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // Check database
        $booking = Appointment::where('email', $email)->first();
        $this->assertNotNull($booking);
        $this->assertEquals('iets_test', $booking->type);
        $this->assertEquals('IELTS Academic Test', $booking->test_type);
        $this->assertStringStartsWith('IETS-' . date('Y') . '-', $booking->registration_number);
        $this->assertEquals($booking->registration_number, $booking->booking_code);

        // Check seat capacity decreased by 1
        $this->assertEquals($initialBooked + 1, $slot->fresh()->booked_count);
    }

    public function test_cannot_book_fully_booked_slot_over_15_students(): void
    {
        $slot = AppointmentSlot::where('slot_date', '2026-09-25')
            ->where('start_time', '10:00 AM')
            ->where('type', 'iets_test')
            ->first();

        $this->assertNotNull($slot);
        $this->assertTrue($slot->is_full);

        $postData = [
            'appointment_type' => 'iets_test',
            'test_type' => 'IELTS Academic Test',
            'slot_id' => $slot->id,
            'appointment_date' => '2026-09-25',
            'time_slot' => '10:00 AM',
            'name' => 'Overcapacity Student',
            'email' => 'overcap@example.com',
            'phone' => '+1 555 999 8888',
        ];

        $response = $this->post('/appointments/book', $postData);
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('appointments', ['email' => 'overcap@example.com']);
    }

    public function test_duplicate_registration_protection_same_email_same_slot(): void
    {
        $slot = AppointmentSlot::where('slot_date', '2026-09-25')
            ->where('start_time', '09:00 AM')
            ->where('type', 'iets_test')
            ->first();

        $existingStudent = $slot->appointments()->first();
        $this->assertNotNull($existingStudent);

        $postData = [
            'appointment_type' => 'iets_test',
            'test_type' => 'IELTS Academic Test',
            'slot_id' => $slot->id,
            'appointment_date' => '2026-09-25',
            'time_slot' => '09:00 AM',
            'name' => 'Duplicate Attempt',
            'email' => $existingStudent->email, // Same email!
            'phone' => '+1 555 000 1111',
        ];

        $response = $this->post('/appointments/book', $postData);
        $response->assertSessionHas('error');
    }

    public function test_admin_can_cancel_booking_and_seat_is_immediately_released(): void
    {
        $admin = User::where('email', 'admin@antiacademy.edu')->first();
        $this->assertNotNull($admin);

        $slot = AppointmentSlot::where('slot_date', '2026-09-25')
            ->where('start_time', '09:00 AM')
            ->where('type', 'iets_test')
            ->first();

        $booking = $slot->appointments()->whereNotIn('status', ['cancelled'])->first();
        $this->assertNotNull($booking);

        $initialBooked = $slot->booked_count;

        $response = $this->actingAs($admin)->post("/admin/scheduling/bookings/{$booking->id}/status", [
            'status' => 'cancelled',
            'admin_notes' => 'Candidate requested test cancellation',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('cancelled', $booking->fresh()->status);

        // Seat is released: booked count is 1 less
        $this->assertEquals($initialBooked - 1, $slot->fresh()->booked_count);
    }

    public function test_admin_can_access_all_scheduling_sections(): void
    {
        $admin = User::where('email', 'admin@antiacademy.edu')->first();
        $this->assertNotNull($admin);

        $sections = [
            '/admin/scheduling',
            '/admin/scheduling/iets',
            '/admin/scheduling/counseling',
            '/admin/scheduling/calendar',
            '/admin/scheduling/bookings',
            '/admin/scheduling/students',
            '/admin/scheduling/emails',
            '/admin/scheduling/settings',
        ];

        foreach ($sections as $uri) {
            $resp = $this->actingAs($admin)->get($uri);
            $resp->assertStatus(200);
        }
    }
}
