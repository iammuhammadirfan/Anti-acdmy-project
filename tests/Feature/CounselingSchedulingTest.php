<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CounselingSchedulingTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected string $futureDate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('email', 'admin@antiacademy.edu')->first() ?? User::first();
        $this->futureDate = Carbon::tomorrow()->toDateString();
    }

    public function test_admin_can_access_counseling_schedule_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/scheduling/counseling');
        $response->assertStatus(200);
        $response->assertSee('Campus Counseling');
        $response->assertSee('Create Counseling Slot');
        $response->assertSee('Batch Generator');
    }

    public function test_admin_can_create_custom_counseling_slot_with_10_minutes_duration_and_custom_seats(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/scheduling/slots', [
            'type' => 'counseling',
            'slot_date' => $this->futureDate,
            'start_time' => '10:00 AM',
            'end_time' => '10:10 AM',
            'duration_minutes' => 10,
            'capacity' => 5,
            'notes' => 'Room 101, Career guidance',
        ]);

        $response->assertSessionHas('success');

        $slot = AppointmentSlot::where('type', 'counseling')
            ->where('slot_date', $this->futureDate)
            ->where('start_time', '10:00 AM')
            ->first();

        $this->assertNotNull($slot);
        $this->assertEquals(5, $slot->capacity);
        $this->assertEquals(10, $slot->duration_minutes);
        $this->assertEquals('10:10 AM', $slot->end_time);
        $this->assertEquals('10 Mins', $slot->formatted_duration);
    }

    public function test_admin_cannot_create_overlapping_counseling_slots(): void
    {
        // Create initial slot: 11:00 AM - 11:30 AM
        AppointmentSlot::create([
            'type' => 'counseling',
            'slot_date' => $this->futureDate,
            'start_time' => '11:00 AM',
            'end_time' => '11:30 AM',
            'duration_minutes' => 30,
            'capacity' => 2,
            'is_active' => true,
        ]);

        // Try to create overlapping slot: 11:15 AM - 11:45 AM
        $response = $this->actingAs($this->admin)->post('/admin/scheduling/slots', [
            'type' => 'counseling',
            'slot_date' => $this->futureDate,
            'start_time' => '11:15 AM',
            'end_time' => '11:45 AM',
            'duration_minutes' => 30,
            'capacity' => 1,
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Overlap Conflict', session('error'));
    }

    public function test_admin_can_update_counseling_slot_capacity_and_duration(): void
    {
        $slot = AppointmentSlot::create([
            'type' => 'counseling',
            'slot_date' => $this->futureDate,
            'start_time' => '02:00 PM',
            'end_time' => '02:30 PM',
            'duration_minutes' => 30,
            'capacity' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/scheduling/slots/{$slot->id}", [
            'capacity' => 8,
            'start_time' => '02:00 PM',
            'end_time' => '02:15 PM',
            'duration_minutes' => 15,
            'is_active' => 1,
            'notes' => 'Updated group counseling',
        ]);

        $response->assertSessionHas('success');
        $slot->refresh();
        $this->assertEquals(8, $slot->capacity);
        $this->assertEquals(15, $slot->duration_minutes);
        $this->assertEquals('15 Mins', $slot->formatted_duration);
    }

    public function test_slots_api_returns_custom_counseling_slots_with_exact_seats_and_duration(): void
    {
        AppointmentSlot::create([
            'type' => 'counseling',
            'slot_date' => $this->futureDate,
            'start_time' => '03:00 PM',
            'end_time' => '03:10 PM',
            'duration_minutes' => 10,
            'capacity' => 4,
            'is_active' => true,
        ]);

        $response = $this->getJson("/appointments/slots?date={$this->futureDate}&type=counseling");
        $response->assertStatus(200);

        $slots = $response->json('slots');
        $this->assertIsArray($slots);

        $slot = collect($slots)->firstWhere('time', '03:00 PM');
        $this->assertNotNull($slot);
        $this->assertEquals(4, $slot['capacity']);
        $this->assertEquals(4, $slot['remaining_seats']);
        $this->assertEquals(10, $slot['duration_minutes']);
        $this->assertEquals('10 Mins', $slot['formatted_duration']);
        $this->assertEquals('03:10 PM', $slot['end_time']);
    }

    public function test_admin_can_batch_generate_counseling_slots(): void
    {
        $batchDate = Carbon::parse($this->futureDate)->addDays(2)->toDateString();

        $response = $this->actingAs($this->admin)->post('/admin/scheduling/slots/batch', [
            'type' => 'counseling',
            'slot_date' => $batchDate,
            'start_time' => '09:00',
            'end_time' => '10:00',
            'duration_minutes' => 15,
            'capacity' => 3,
        ]);

        $response->assertSessionHas('success');

        $slots = AppointmentSlot::where('type', 'counseling')
            ->where('slot_date', $batchDate)
            ->get();

        // 09:00 - 10:00 with 15 mins each = 4 slots (09:00, 09:15, 09:30, 09:45)
        $this->assertCount(4, $slots);
        foreach ($slots as $s) {
            $this->assertEquals(3, $s->capacity);
            $this->assertEquals(15, $s->duration_minutes);
        }
    }

    public function test_student_can_book_custom_counseling_slot_and_seats_decrease(): void
    {
        $slot = AppointmentSlot::create([
            'type' => 'counseling',
            'slot_date' => $this->futureDate,
            'start_time' => '04:00 PM',
            'end_time' => '04:10 PM',
            'duration_minutes' => 10,
            'capacity' => 2,
            'is_active' => true,
        ]);

        $postData = [
            'appointment_type' => 'counseling',
            'appointment_date' => $this->futureDate,
            'time_slot' => '04:00 PM',
            'slot_id' => $slot->id,
            'name' => 'John Doe',
            'email' => 'counseling_student_' . time() . '@example.com',
            'phone' => '+923001234567',
            'purpose' => 'Undergraduate Admissions',
        ];

        $response = $this->post('/appointments/book', $postData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $slot->refresh();
        $this->assertEquals(1, $slot->booked_count);
        $this->assertEquals(1, $slot->remaining_seats);
        $this->assertFalse($slot->is_full);
    }
}
