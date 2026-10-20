<?php

namespace Tests\Feature;

use App\Models\AppointmentSlot;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SchedulingMasterToggleTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected string $futureDate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('email', 'admin@antiacademy.edu')->first() ?? User::first();
        $this->futureDate = Carbon::tomorrow()->toDateString();
        Setting::set('scheduling_system_enabled', '1', 'scheduling');
    }

    protected function tearDown(): void
    {
        Setting::set('scheduling_system_enabled', '1', 'scheduling');
        parent::tearDown();
    }

    public function test_admin_can_view_master_toggle_in_settings(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/scheduling/settings');
        $response->assertStatus(200);
        $response->assertSee('Service:');
        $response->assertSee('settings/toggle');
    }

    public function test_admin_can_toggle_scheduling_system_on_and_off(): void
    {
        // Ensure enabled first
        Setting::set('scheduling_system_enabled', '1', 'scheduling');

        // Toggle to OFF
        $response = $this->actingAs($this->admin)->post('/admin/scheduling/settings/toggle');
        $response->assertSessionHas('success');
        $this->assertEquals('0', (string) Setting::get('scheduling_system_enabled'));

        // Toggle back to ON
        $response = $this->actingAs($this->admin)->post('/admin/scheduling/settings/toggle');
        $response->assertSessionHas('success');
        $this->assertEquals('1', (string) Setting::get('scheduling_system_enabled'));
    }

    public function test_when_disabled_frontend_booking_aborts_with_requested_404_error(): void
    {
        Setting::set('scheduling_system_enabled', '0', 'scheduling');

        // Booking page 404
        $pageResponse = $this->get('/appointments');
        $pageResponse->assertStatus(404);
        $pageResponse->assertSee('404 cant creat slot plz conct adminstration have isue your webste');

        // Slot fetch API 404
        $apiResponse = $this->getJson('/appointments/slots?date=' . $this->futureDate . '&type=counseling');
        $apiResponse->assertStatus(404);
        $apiResponse->assertJsonFragment([
            'message' => '404 cant creat slot plz conct adminstration have isue your webste',
        ]);

        // Booking POST 404
        $bookResponse = $this->post('/appointments/book', [
            'appointment_type' => 'counseling',
            'appointment_date' => $this->futureDate,
            'time_slot' => '10:00 AM - 10:30 AM',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '03001234567',
        ]);
        $bookResponse->assertStatus(404);
    }

    public function test_when_disabled_admin_slot_creation_is_blocked_with_error(): void
    {
        Setting::set('scheduling_system_enabled', '0', 'scheduling');

        $response = $this->actingAs($this->admin)->post('/admin/scheduling/slots', [
            'type' => 'counseling',
            'slot_date' => $this->futureDate,
            'start_time' => '10:00 AM',
            'end_time' => '10:30 AM',
            'capacity' => 1,
            'duration_minutes' => 30,
        ]);

        $response->assertSessionHas('error', '404 cant creat slot plz conct adminstration have isue your webste');

        $this->assertDatabaseMissing('appointment_slots', [
            'slot_date' => $this->futureDate,
            'start_time' => '10:00 AM',
        ]);
    }

    public function test_when_enabled_scheduling_works_normally(): void
    {
        Setting::set('scheduling_system_enabled', '1', 'scheduling');

        $pageResponse = $this->get('/appointments');
        $pageResponse->assertStatus(200);

        $createResponse = $this->actingAs($this->admin)->post('/admin/scheduling/slots', [
            'type' => 'counseling',
            'slot_date' => $this->futureDate,
            'start_time' => '11:00 AM',
            'end_time' => '11:30 AM',
            'capacity' => 1,
            'duration_minutes' => 30,
        ]);

        $createResponse->assertSessionHas('success');
        $this->assertDatabaseHas('appointment_slots', [
            'slot_date' => $this->futureDate,
            'start_time' => '11:00 AM',
        ]);
    }
}
