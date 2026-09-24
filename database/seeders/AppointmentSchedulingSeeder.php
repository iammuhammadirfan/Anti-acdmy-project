<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSchedulingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Backfill any existing appointments without registration_number
        Appointment::whereNull('registration_number')->get()->each(function ($apt) {
            $apt->type = $apt->type ?: 'counseling';
            $apt->registration_number = $apt->booking_code;
            $apt->saveQuietly();
        });

        // 2. Seed Email Templates
        $templates = EmailTemplate::getDefaults();
        foreach ($templates as $slug => $data) {
            EmailTemplate::updateOrCreate(['slug' => $slug], $data);
        }

        // 3. Clear any overlapping test slots and seed non-overlapping IETS Test Slots
        $sept25 = '2026-09-25';
        $sept26 = '2026-09-26';
        $sept28 = '2026-09-28';

        $slotsData = [
            // Sept 25 non-overlapping ranges
            ['date' => $sept25, 'time' => '09:00 AM', 'end_time' => '10:00 AM', 'capacity' => 15, 'type' => 'iets_test'],
            ['date' => $sept25, 'time' => '10:00 AM', 'end_time' => '11:00 AM', 'capacity' => 15, 'type' => 'iets_test'],
            ['date' => $sept25, 'time' => '11:30 AM', 'end_time' => '12:30 PM', 'capacity' => 15, 'type' => 'iets_test'],
            ['date' => $sept25, 'time' => '02:00 PM', 'end_time' => '03:00 PM', 'capacity' => 15, 'type' => 'iets_test'],

            // Sept 26 non-overlapping ranges
            ['date' => $sept26, 'time' => '09:00 AM', 'end_time' => '10:00 AM', 'capacity' => 15, 'type' => 'iets_test'],
            ['date' => $sept26, 'time' => '10:00 AM', 'end_time' => '11:00 AM', 'capacity' => 15, 'type' => 'iets_test'],
            ['date' => $sept26, 'time' => '02:00 PM', 'end_time' => '03:00 PM', 'capacity' => 15, 'type' => 'iets_test'],

            // Sept 28 non-overlapping ranges
            ['date' => $sept28, 'time' => '09:00 AM', 'end_time' => '10:00 AM', 'capacity' => 15, 'type' => 'iets_test'],
            ['date' => $sept28, 'time' => '11:30 AM', 'end_time' => '12:30 PM', 'capacity' => 15, 'type' => 'iets_test'],
        ];

        // Delete old overlapping slot (e.g. 09:30 AM) on Sept 25 if it exists and remap bookings
        $oldSlot0930 = AppointmentSlot::where('slot_date', $sept25)->where('start_time', '09:30 AM')->first();

        $createdSlots = [];
        foreach ($slotsData as $sd) {
            $slot = AppointmentSlot::updateOrCreate(
                [
                    'slot_date' => $sd['date'],
                    'start_time' => $sd['time'],
                    'type' => $sd['type'],
                ],
                [
                    'end_time' => $sd['end_time'],
                    'capacity' => $sd['capacity'],
                    'duration_minutes' => 60,
                    'is_active' => true,
                ]
            );
            $createdSlots[$sd['date'] . '_' . $sd['time'] . '_' . $sd['type']] = $slot;
        }

        // 4. Ensure demo bookings populate accurate capacity scenarios:
        // Wipe existing demo appointments so re-seeding is clean and idempotent
        Appointment::where('email', 'like', '%@example.com')->orWhere('appointment_date', $sept25)->delete();

        // Slot 1: 09:00 AM IETS -> 8 bookings (7 seats remaining)
        $slot0900 = $createdSlots[$sept25 . '_09:00 AM_iets_test'] ?? null;
        if ($slot0900) {
            for ($i = 1; $i <= 8; $i++) {
                Appointment::create([
                    'slot_id' => $slot0900->id,
                    'type' => 'iets_test',
                    'test_type' => 'IELTS Academic Test',
                    'name' => "Student {$i} TestUser",
                    'email' => "student{$i}@example.com",
                    'phone' => "+1 555 010 " . sprintf('%04d', $i),
                    'appointment_date' => $sept25,
                    'time_slot' => '09:00 AM',
                    'purpose' => 'IETS Test Registration',
                    'cnic_passport' => '42201-123456' . $i . '-1',
                    'program' => 'Undergraduate Admissions',
                    'status' => 'confirmed',
                ]);
            }
        }

        // Slot 2: 10:00 AM IETS -> exactly 15 bookings (FULLY BOOKED)
        $slot1000 = $createdSlots[$sept25 . '_10:00 AM_iets_test'] ?? null;
        if ($slot1000) {
            for ($i = 1; $i <= 15; $i++) {
                Appointment::create([
                    'slot_id' => $slot1000->id,
                    'type' => 'iets_test',
                    'test_type' => ($i % 2 === 0) ? 'IELTS General Training' : 'IELTS Academic Test',
                    'name' => "Candidate {$i} Booked",
                    'email' => "fullslot_{$i}@example.com",
                    'phone' => "+1 555 020 " . sprintf('%04d', $i),
                    'appointment_date' => $sept25,
                    'time_slot' => '10:00 AM',
                    'purpose' => 'IETS Test Registration',
                    'status' => 'confirmed',
                ]);
            }
        }

        // Slot 3: 11:30 AM IETS -> exactly 3 bookings (12 seats remaining)
        $slot1130 = $createdSlots[$sept25 . '_11:30 AM_iets_test'] ?? null;
        if ($slot1130) {
            for ($i = 1; $i <= 3; $i++) {
                Appointment::create([
                    'slot_id' => $slot1130->id,
                    'type' => 'iets_test',
                    'test_type' => 'IELTS Academic Test',
                    'name' => "Midday Candidate {$i}",
                    'email' => "midday{$i}@example.com",
                    'phone' => "+1 555 030 " . sprintf('%04d', $i),
                    'appointment_date' => $sept25,
                    'time_slot' => '11:30 AM',
                    'purpose' => 'IETS Test Registration',
                    'status' => 'confirmed',
                ]);
            }
        }

        // Counseling bookings (independent single slots)
        $counselingTimes = ['09:30 AM', '11:00 AM', '02:30 PM'];
        foreach ($counselingTimes as $idx => $cTime) {
            $cSeq = $idx + 1;
            Appointment::create([
                'type' => 'counseling',
                'name' => "Counseling Student {$cSeq}",
                'email' => "counseling{$cSeq}@example.com",
                'phone' => "+1 555 099 " . sprintf('%04d', $cSeq),
                'appointment_date' => $sept25,
                'time_slot' => $cTime,
                'purpose' => 'Admissions & Course Enrollment',
                'status' => 'confirmed',
            ]);
        }
    }
}
