<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class AdminBookingDeleteTest extends TestCase
{
    protected function getAdminUser(): User
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create(['is_admin' => true]);
        }
        return $user;
    }

    public function test_admin_can_delete_single_booking(): void
    {
        $admin = $this->getAdminUser();

        $booking = Appointment::create([
            'booking_code' => 'DEL-' . rand(1000, 9999),
            'registration_number' => 'IETS-DEL-' . rand(1000, 9999),
            'name' => 'Delete Test Candidate',
            'email' => 'delete_single@test.com',
            'phone' => '+15551239999',
            'type' => 'iets_test',
            'appointment_date' => Carbon::tomorrow()->toDateString(),
            'time_slot' => '10:00 AM',
            'purpose' => 'IETS Consultation',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('appointments', ['id' => $booking->id]);

        $response = $this->actingAs($admin)->delete(route('admin.scheduling.booking.destroy', $booking));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('appointments', ['id' => $booking->id]);
    }

    public function test_admin_can_bulk_delete_selected_bookings(): void
    {
        $admin = $this->getAdminUser();

        $booking1 = Appointment::create([
            'booking_code' => 'BULK1-' . rand(1000, 9999),
            'name' => 'Bulk Student One',
            'email' => 'bulk1@test.com',
            'phone' => '+15550001111',
            'type' => 'counseling',
            'appointment_date' => Carbon::tomorrow()->toDateString(),
            'time_slot' => '11:00 AM',
            'purpose' => 'Academic Counseling',
            'status' => 'pending',
        ]);

        $booking2 = Appointment::create([
            'booking_code' => 'BULK2-' . rand(1000, 9999),
            'name' => 'Bulk Student Two',
            'email' => 'bulk2@test.com',
            'phone' => '+15550002222',
            'type' => 'counseling',
            'appointment_date' => Carbon::tomorrow()->toDateString(),
            'time_slot' => '11:30 AM',
            'purpose' => 'Academic Counseling',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.scheduling.bookings.bulk-destroy'), [
            'booking_ids' => [$booking1->id, $booking2->id]
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('appointments', ['id' => $booking1->id]);
        $this->assertDatabaseMissing('appointments', ['id' => $booking2->id]);
    }

    public function test_admin_can_bulk_delete_all_bookings(): void
    {
        $admin = $this->getAdminUser();

        Appointment::create([
            'booking_code' => 'ALL1-' . rand(1000, 9999),
            'name' => 'Purge Candidate',
            'email' => 'purge@test.com',
            'phone' => '+15550003333',
            'type' => 'counseling',
            'appointment_date' => Carbon::tomorrow()->toDateString(),
            'time_slot' => '02:00 PM',
            'purpose' => 'Academic Counseling',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.scheduling.bookings.bulk-destroy'), [
            'all' => 1
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(0, Appointment::count());
    }
}
