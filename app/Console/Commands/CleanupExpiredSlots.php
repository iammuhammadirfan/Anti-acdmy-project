<?php

namespace App\Console\Commands;

use App\Models\AppointmentSlot;
use Illuminate\Console\Command;

class CleanupExpiredSlots extends Command
{
    protected $signature = 'appointments:cleanup-expired-slots';
    protected $description = 'Automatically delete expired unbooked slots from the database while keeping all slots with booking records safe';

    public function handle(): int
    {
        $deleted = AppointmentSlot::cleanupExpiredUnbookedSlots();

        $this->info("Successfully cleaned up {$deleted} expired unbooked slot(s). Slots with existing student bookings have been preserved.");
        return Command::SUCCESS;
    }
}
