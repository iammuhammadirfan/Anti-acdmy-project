<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send 24-hour reminder email and WhatsApp alerts to students with confirmed bookings for tomorrow';

    public function handle(NotificationService $notificationService): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = Appointment::where('appointment_date', $tomorrow)
            ->where('status', 'confirmed')
            ->whereNull('reminder_sent_at')
            ->get();

        $this->info("Found {$appointments->count()} appointments/tests scheduled for tomorrow ({$tomorrow}).");

        $sentCount = 0;
        foreach ($appointments as $apt) {
            try {
                $notificationService->sendReminder($apt);
                $apt->update(['reminder_sent_at' => now()]);
                $sentCount++;
                $this->line("Sent reminder for {$apt->registration_number} to {$apt->email}.");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for {$apt->registration_number}: " . $e->getMessage());
            }
        }

        $this->info("Successfully dispatched {$sentCount} reminders.");
        return Command::SUCCESS;
    }
}
