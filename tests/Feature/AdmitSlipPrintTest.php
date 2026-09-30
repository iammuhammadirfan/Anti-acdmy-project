<?php

namespace Tests\Feature;

use App\Models\Appointment;
use Carbon\Carbon;
use Tests\TestCase;

class AdmitSlipPrintTest extends TestCase
{
    public function test_booking_success_renders_official_slip_and_image_download(): void
    {
        $code = 'IETS-' . rand(1000, 9999);
        $appointment = Appointment::create([
            'booking_code' => $code,
            'registration_number' => $code,
            'name' => 'Testing Candidate Slip',
            'email' => 'slip_test@example.com',
            'phone' => '+15559876543',
            'type' => 'iets_test',
            'appointment_date' => Carbon::tomorrow()->toDateString(),
            'time_slot' => '01:00 PM',
            'purpose' => 'IETS Official Test',
            'status' => 'confirmed',
        ]);

        $response = $this->get('/appointments/success?code=' . $code);

        $response->assertStatus(200);
        $response->assertSee('printable-slip');
        $response->assertSee('Download as Image (PNG)');
        $response->assertSee('Print Official Slip (1-Page)');
        $response->assertSee('downloadSlipAsImage');
        $response->assertSee('html2canvas');
        $response->assertSee($code);
    }
}
