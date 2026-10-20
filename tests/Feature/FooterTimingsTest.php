<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FooterTimingsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_footer_displays_both_mon_fri_and_saturday_timings(): void
    {
        Setting::set('academy_timings_mon_fri', 'Mon - Fri: 8:00 AM - 7:00 PM', 'general');
        Setting::set('academy_timings_sat', 'Saturday: 9:00 AM - 2:00 PM', 'general');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Mon - Fri: 8:00 AM - 7:00 PM');
        $response->assertSee('Saturday: 9:00 AM - 2:00 PM');
    }

    public function test_admin_can_update_both_timings_in_general_settings(): void
    {
        $admin = User::where('email', 'admin@antiacademy.edu')->first() ?? User::first();

        $response = $this->actingAs($admin)->post('/admin/settings', [
            'group' => 'general',
            'academy_name' => 'Anti Academy',
            'academy_timings_mon_fri' => 'Monday to Friday: 08:30 AM - 06:30 PM',
            'academy_timings_sat' => 'Saturday: 10:00 AM - 03:00 PM',
            'contact_email' => 'info@antiacademy.edu',
            'contact_phone' => '+15551234567',
            'contact_address' => 'Knowledge Park',
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals('Monday to Friday: 08:30 AM - 06:30 PM', Setting::get('academy_timings_mon_fri'));
        $this->assertEquals('Saturday: 10:00 AM - 03:00 PM', Setting::get('academy_timings_sat'));

        // Verify footer on frontend now shows new timings
        $frontendResponse = $this->get('/');
        $frontendResponse->assertSee('Monday to Friday: 08:30 AM - 06:30 PM');
        $frontendResponse->assertSee('Saturday: 10:00 AM - 03:00 PM');
    }
}
