<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\AppointmentSlot;
use App\Models\BlockedDate;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Services\AppointmentSlotService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SchedulingController extends Controller
{
    protected NotificationService $notificationService;
    protected AppointmentSlotService $slotService;

    public function __construct(NotificationService $notificationService, AppointmentSlotService $slotService)
    {
        $this->notificationService = $notificationService;
        $this->slotService = $slotService;
    }

    /**
     * Scheduling Dashboard: High-level KPI metrics & overview
     */
    public function dashboard()
    {
        $today = Carbon::today()->toDateString();

        $metrics = [
            'today_counseling' => Appointment::counseling()->where('appointment_date', $today)->whereNotIn('status', ['cancelled'])->count(),
            'today_iets' => Appointment::ietsTest()->where('appointment_date', $today)->whereNotIn('status', ['cancelled'])->count(),
            'total_bookings' => Appointment::count(),
            'confirmed_bookings' => Appointment::where('status', 'confirmed')->count(),
            'available_slots' => AppointmentSlot::where('type', 'iets_test')->where('is_active', true)->get()->filter(fn($s) => !$s->is_full)->count(),
            'fully_booked_slots' => AppointmentSlot::where('type', 'iets_test')->get()->filter(fn($s) => $s->is_full)->count(),
            'total_iets_students' => Appointment::ietsTest()->whereNotIn('status', ['cancelled'])->count(),
            'total_available_seats' => AppointmentSlot::where('type', 'iets_test')->where('is_active', true)->get()->sum(fn($s) => $s->remaining_seats),
        ];

        // Upcoming IETS Test Slots with capacity
        $upcomingTestSlots = AppointmentSlot::where('type', 'iets_test')
            ->where('slot_date', '>=', $today)
            ->where('is_active', true)
            ->orderBy('slot_date')
            ->orderByRaw("STR_TO_DATE(start_time, '%h:%i %p') ASC")
            ->limit(6)
            ->get();

        // Recent Bookings across all types
        $recentBookings = Appointment::latest()->limit(8)->get();

        return view('admin.scheduling.dashboard', compact('metrics', 'upcomingTestSlots', 'recentBookings'));
    }

    /**
     * Interactive Slot Calendar View
     */
    public function calendar(Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $slots = AppointmentSlot::whereBetween('slot_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->with(['appointments'])
            ->get()
            ->groupBy(function ($item) {
                return $item->slot_date->format('Y-m-d');
            });

        $selectedDate = $request->get('date', Carbon::today()->toDateString());
        $daySlots = AppointmentSlot::where('slot_date', $selectedDate)
            ->with(['appointments'])
            ->orderByRaw("STR_TO_DATE(start_time, '%h:%i %p') ASC")
            ->get();

        $counselingSlots = $this->slotService->getSlotsForType($selectedDate, 'counseling');

        $blockedDates = BlockedDate::pluck('date')->map(fn($d) => $d->format('Y-m-d'))->toArray();

        return view('admin.scheduling.calendar', compact('month', 'slots', 'selectedDate', 'daySlots', 'counselingSlots', 'blockedDates'));
    }

    /**
     * Slots List (Combined / Table view)
     */
    public function slots(Request $request)
    {
        $type = $request->get('type', 'iets_test');
        $date = $request->get('date');
        $status = $request->get('status');

        $query = AppointmentSlot::latest('slot_date');

        if ($type) {
            $query->where('type', $type);
        }
        if ($date) {
            $query->where('slot_date', $date);
        }
        if ($status !== null && $status !== '') {
            $query->where('is_active', (bool) $status);
        }

        $slots = $query->with('appointments')->paginate(20)->withQueryString();

        return view('admin.scheduling.slots.index', compact('slots', 'type', 'date', 'status'));
    }

    /**
     * Dedicated IETS Test Schedule Section
     */
    public function ietsSchedule(Request $request)
    {
        $date = $request->get('date');
        $status = $request->get('status');

        $query = AppointmentSlot::where('type', 'iets_test')->orderBy('slot_date')->orderByRaw("STR_TO_DATE(start_time, '%h:%i %p') ASC");

        if ($date) {
            $query->where('slot_date', $date);
        }
        if ($status !== null && $status !== '') {
            $query->where('is_active', (bool) $status);
        }

        $slots = $query->with('appointments')->paginate(25)->withQueryString();
        $defaultCapacity = (int) Setting::get('default_iets_capacity', 15);

        return view('admin.scheduling.iets_schedule', compact('slots', 'date', 'status', 'defaultCapacity'));
    }

    /**
     * Dedicated Counseling Schedule Section (9:00 AM - 6:00 PM auto slots)
     */
    public function counselingSchedule(Request $request)
    {
        $selectedDate = $request->get('date', Carbon::today()->toDateString());
        $settings = AppointmentSetting::getSettings();

        // Generated counseling slots for this day from 9:00 AM to 6:00 PM
        $counselingSlots = $this->slotService->getSlotsForType($selectedDate, 'counseling');

        // Booked counseling records on this day
        $bookedAppointments = Appointment::where('type', 'counseling')
            ->where('appointment_date', $selectedDate)
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->keyBy(fn($a) => AppointmentSlot::normalizeTime($a->time_slot));

        $totalSlotsCount = count($counselingSlots);
        $bookedCount = $counselingSlots ? count(array_filter($counselingSlots, fn($s) => $s['is_full'])) : 0;
        $availableCount = $totalSlotsCount - $bookedCount;

        return view('admin.scheduling.counseling_schedule', compact('selectedDate', 'settings', 'counselingSlots', 'bookedAppointments', 'totalSlotsCount', 'bookedCount', 'availableCount'));
    }

    /**
     * Store a single slot with Overlap Protection for IETS
     */
    public function storeSlot(Request $request)
    {
        $request->validate([
            'type' => 'required|in:counseling,iets_test',
            'slot_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'nullable|string',
            'capacity' => 'required|integer|min:1|max:50',
            'duration_minutes' => 'nullable|integer|min:15|max:240',
            'notes' => 'nullable|string',
        ]);

        $date = Carbon::parse($request->slot_date)->toDateString();
        $startTime = AppointmentSlot::normalizeTime($request->start_time);
        
        $duration = (int) ($request->duration_minutes ?: 60);
        $endTime = $request->end_time 
            ? AppointmentSlot::normalizeTime($request->end_time)
            : Carbon::parse($date . ' ' . $startTime)->addMinutes($duration)->format('h:i A');

        $startCarbon = Carbon::parse($date . ' ' . $startTime);
        $endCarbon = Carbon::parse($date . ' ' . $endTime);
        if ($endCarbon->lte($startCarbon)) {
            return back()->with('error', "End time ({$endTime}) must be strictly after start time ({$startTime}).")->withInput();
        }

        // Overlap Check for IETS Test Slots
        if ($request->type === 'iets_test') {
            $overlap = AppointmentSlot::checkIetsOverlap($date, $startTime, $endTime);
            if ($overlap) {
                return back()->with('error', "Overlap Conflict: The requested IETS slot ({$startTime} – {$endTime}) overlaps with an existing IETS slot ({$overlap->start_time} – {$overlap->end_time}) on {$date}. Overlapping IETS slots are strictly not allowed.")->withInput();
            }
        }

        // Exact match check for Counseling
        if ($request->type === 'counseling') {
            $exists = AppointmentSlot::where('slot_date', $date)
                ->where('start_time', $startTime)
                ->where('type', 'counseling')
                ->exists();

            if ($exists) {
                return back()->with('error', "A counseling slot at {$startTime} already exists on {$date}.")->withInput();
            }
        }

        $slot = AppointmentSlot::create([
            'type' => $request->type,
            'slot_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'capacity' => $request->capacity,
            'duration_minutes' => $duration,
            'is_active' => true,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        ActivityLog::log('create', 'appointments', "Created {$slot->type} slot on {$slot->slot_date} ({$slot->start_time} – {$slot->end_time}) with capacity {$slot->capacity}.");

        return back()->with('success', "{$slot->type} slot created for {$slot->slot_date->format('M d, Y')} ({$slot->start_time} – {$slot->end_time}) with {$slot->capacity} seats.");
    }

    /**
     * Batch Generate Slots for a Date Range with Overlap Check
     */
    public function batchGenerateSlots(Request $request)
    {
        $request->validate([
            'slot_date' => 'required|date',
            'type' => 'required|in:counseling,iets_test',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'required|integer|min:15|max:180',
            'capacity' => 'required|integer|min:1|max:50',
        ]);

        $date = Carbon::parse($request->slot_date)->toDateString();
        $start = Carbon::parse($date . ' ' . $request->start_time);
        $end = Carbon::parse($date . ' ' . $request->end_time);
        $duration = (int) $request->duration_minutes;
        $capacity = (int) $request->capacity;
        $type = $request->type;

        $createdCount = 0;
        $curr = $start->copy();

        while ($curr->copy()->addMinutes($duration)->lte($end)) {
            $formattedStart = $curr->format('h:i A');
            $formattedEnd = $curr->copy()->addMinutes($duration)->format('h:i A');

            $overlap = null;
            if ($type === 'iets_test') {
                $overlap = AppointmentSlot::checkIetsOverlap($date, $formattedStart, $formattedEnd);
            }

            if (!$overlap) {
                $exists = AppointmentSlot::where('slot_date', $date)
                    ->where('start_time', $formattedStart)
                    ->where('type', $type)
                    ->exists();

                if (!$exists) {
                    AppointmentSlot::create([
                        'type' => $type,
                        'slot_date' => $date,
                        'start_time' => $formattedStart,
                        'end_time' => $formattedEnd,
                        'capacity' => $capacity,
                        'duration_minutes' => $duration,
                        'is_active' => true,
                        'created_by' => auth()->id(),
                    ]);
                    $createdCount++;
                }
            }

            $curr->addMinutes($duration);
        }

        ActivityLog::log('create', 'appointments', "Batch generated {$createdCount} {$type} slots for {$date}.");

        return back()->with('success', "Successfully generated {$createdCount} slots for {$date}.");
    }

    /**
     * Update slot details (capacity, time, status) with Overlap Protection
     */
    public function updateSlot(Request $request, AppointmentSlot $slot)
    {
        $request->validate([
            'capacity' => 'required|integer|min:1|max:50',
            'start_time' => 'required|string',
            'end_time' => 'nullable|string',
            'is_active' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $startTime = AppointmentSlot::normalizeTime($request->start_time);
        $endTime = $request->end_time ? AppointmentSlot::normalizeTime($request->end_time) : $slot->end_time;

        if ($endTime) {
            $startCarbon = Carbon::parse($slot->slot_date->toDateString() . ' ' . $startTime);
            $endCarbon = Carbon::parse($slot->slot_date->toDateString() . ' ' . $endTime);
            if ($endCarbon->lte($startCarbon)) {
                return back()->with('error', "End time ({$endTime}) must be strictly after start time ({$startTime}).")->withInput();
            }
        }

        if ($slot->type === 'iets_test' && $endTime) {
            $overlap = AppointmentSlot::checkIetsOverlap($slot->slot_date->toDateString(), $startTime, $endTime, $slot->id);
            if ($overlap) {
                return back()->with('error', "Slot conflict: The updated time range ({$startTime} – {$endTime}) overlaps with another IETS slot ({$overlap->start_time} – {$overlap->end_time}).")->withInput();
            }
        }

        $slot->update([
            'capacity' => $request->capacity,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_active' => $request->is_active,
            'notes' => $request->notes,
        ]);

        ActivityLog::log('update', 'appointments', "Updated slot ID {$slot->id} on {$slot->slot_date} (Capacity: {$slot->capacity}).");

        return back()->with('success', "Slot updated successfully.");
    }

    /**
     * Toggle slot active / disabled
     */
    public function toggleSlot(AppointmentSlot $slot)
    {
        $slot->is_active = !$slot->is_active;
        $slot->save();

        ActivityLog::log('status_change', 'appointments', "Toggled slot {$slot->id} status to " . ($slot->is_active ? 'Active' : 'Disabled'));

        return back()->with('success', 'Slot status updated.');
    }

    /**
     * Delete slot
     */
    public function destroySlot(AppointmentSlot $slot)
    {
        $activeBookings = $slot->appointments()->whereNotIn('status', ['cancelled'])->count();
        if ($activeBookings > 0) {
            return back()->with('error', "Cannot delete slot: {$activeBookings} active student bookings are currently assigned to it. Please reschedule or cancel the bookings first.");
        }

        $slot->delete();
        ActivityLog::log('delete', 'appointments', "Deleted slot ID {$slot->id}.");

        return back()->with('success', 'Slot deleted successfully.');
    }

    /**
     * Bulk Delete Selected Slots (both IETS and Counseling)
     */
    public function bulkDestroySlots(Request $request)
    {
        $request->validate([
            'slot_ids' => 'required|array|min:1',
            'slot_ids.*' => 'integer|exists:appointment_slots,id',
            'force' => 'nullable|boolean',
        ]);

        $slotIds = $request->input('slot_ids', []);
        $force = (bool) $request->input('force', false);

        if (empty($slotIds)) {
            return back()->with('error', 'No slots selected for deletion. Please select at least one slot.');
        }

        $slots = AppointmentSlot::whereIn('id', $slotIds)
            ->withCount(['appointments' => function ($q) {
                $q->whereNotIn('status', ['cancelled']);
            }])
            ->get();

        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($slots as $slot) {
            if ($slot->appointments_count > 0 && !$force) {
                $skippedCount++;
                continue;
            }

            if ($force && $slot->appointments_count > 0) {
                $slot->appointments()->whereNotIn('status', ['cancelled'])->update([
                    'status' => 'cancelled',
                    'admin_notes' => 'Slot was bulk-deleted by administrator.',
                ]);
            }

            $slot->delete();
            $deletedCount++;
        }

        ActivityLog::log('delete', 'appointments', "Bulk deleted {$deletedCount} slots." . ($skippedCount > 0 ? " ({$skippedCount} skipped due to active bookings)" : ""));

        if ($skippedCount > 0 && $deletedCount > 0) {
            return back()->with('warning', "Successfully deleted {$deletedCount} slot(s). {$skippedCount} slot(s) were preserved because they have active student registrations.");
        } elseif ($skippedCount > 0 && $deletedCount === 0) {
            return back()->with('error', "Could not delete the selected slot(s) because all {$skippedCount} slot(s) have active student registrations. Cancel or reschedule those registrations first.");
        }

        return back()->with('success', "Successfully deleted all {$deletedCount} selected slot(s).");
    }

    /**
     * All Bookings / Registrations with comprehensive multi-field filtering
     */
    public function bookings(Request $request)
    {
        $type = $request->get('type');
        $testType = $request->get('test_type');
        $status = $request->get('status');
        $date = $request->get('date');
        $search = $request->get('search');

        $query = Appointment::with('slot')->latest('appointment_date');

        if ($type) {
            $query->where('type', $type);
        }
        if ($testType) {
            $query->where('test_type', $testType);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($date) {
            $query->where('appointment_date', $date);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('booking_code', 'like', "%{$search}%")
                  ->orWhere('cnic_passport', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(20)->withQueryString();

        return view('admin.scheduling.bookings.index', compact('bookings', 'type', 'testType', 'status', 'date', 'search'));
    }

    /**
     * Show booking details & reschedule drawer
     */
    public function showBooking(Appointment $booking)
    {
        // Available alternative slots for rescheduling
        $availableSlots = AppointmentSlot::where('type', $booking->type)
            ->where('slot_date', '>=', Carbon::today()->toDateString())
            ->where('is_active', true)
            ->withCount(['appointments' => function ($q) {
                $q->whereNotIn('status', ['cancelled']);
            }])
            ->get()
            ->filter(function ($s) use ($booking) {
                return $s->id !== $booking->slot_id && $s->appointments_count < $s->capacity;
            });

        return view('admin.scheduling.bookings.show', compact('booking', 'availableSlots'));
    }

    /**
     * Update booking status (Confirm, Cancel, Completed, No Show)
     */
    public function updateBookingStatus(Request $request, Appointment $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $booking->status;
        $booking->status = $request->status;
        if ($request->filled('admin_notes')) {
            $booking->admin_notes = $request->admin_notes;
        }
        $booking->save();

        ActivityLog::log('status_change', 'appointments', "Updated booking {$booking->registration_number} status from {$oldStatus} to {$booking->status}.");

        // Send notifications (including seat release notification if cancelled)
        $this->notificationService->sendAppointmentStatusUpdated($booking, $booking->status);

        $msg = "Booking status updated to {$booking->status}. Automated email notification sent to student.";
        if ($booking->status === 'cancelled') {
            $msg .= " The seat has been freed up and is now available for new students.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Reschedule a student booking to another available slot
     */
    public function rescheduleBooking(Request $request, Appointment $booking)
    {
        $request->validate([
            'new_slot_id' => 'required|exists:appointment_slots,id',
            'admin_notes' => 'nullable|string',
        ]);

        $newSlot = AppointmentSlot::findOrFail($request->new_slot_id);

        if (!$newSlot->is_active) {
            return back()->with('error', 'Target slot is inactive.');
        }

        // Capacity check with row lock
        $bookedCount = Appointment::where('slot_id', $newSlot->id)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        if ($bookedCount >= $newSlot->capacity) {
            return back()->with('error', 'Target slot has reached its maximum capacity of ' . $newSlot->capacity . ' students.');
        }

        $oldDate = $booking->appointment_date->format('M d, Y');
        $oldTime = $booking->time_slot;

        $booking->slot_id = $newSlot->id;
        $booking->appointment_date = $newSlot->slot_date;
        $booking->time_slot = $newSlot->start_time;
        if ($request->filled('admin_notes')) {
            $booking->admin_notes = ($booking->admin_notes ? $booking->admin_notes . "\n" : '') . "Rescheduled from {$oldDate} {$oldTime}: " . $request->admin_notes;
        }
        $booking->save();

        ActivityLog::log('update', 'appointments', "Rescheduled booking {$booking->registration_number} from {$oldDate} {$oldTime} to {$newSlot->slot_date} {$newSlot->start_time}.");

        // Dispatches updated rescheduling email with same registration number
        $this->notificationService->sendAppointmentRescheduled($booking);

        return back()->with('success', "Student booking rescheduled to {$newSlot->slot_date->format('M d, Y')} at {$newSlot->start_time}. Updated confirmation email dispatched.");
    }

    /**
     * Export Bookings to CSV
     */
    public function exportBookings(Request $request): StreamedResponse
    {
        $type = $request->get('type');
        $testType = $request->get('test_type');
        $status = $request->get('status');
        $date = $request->get('date');

        $query = Appointment::latest('appointment_date');
        if ($type) $query->where('type', $type);
        if ($testType) $query->where('test_type', $testType);
        if ($status) $query->where('status', $status);
        if ($date) $query->where('appointment_date', $date);

        $bookings = $query->get();
        $filename = 'scheduling_bookings_' . date('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Registration Number',
                'Appointment Type',
                'Test / Session Type',
                'Student Name',
                'Email',
                'Phone',
                'WhatsApp',
                'CNIC / Passport',
                'Program',
                'Scheduled Date',
                'Time Slot',
                'Status',
                'Created At'
            ]);

            foreach ($bookings as $b) {
                fputcsv($handle, [
                    $b->registration_number ?: $b->booking_code,
                    ucwords(str_replace('_', ' ', $b->type)),
                    $b->test_type ?: $b->purpose,
                    $b->name,
                    $b->email,
                    $b->phone,
                    $b->whatsapp,
                    $b->cnic_passport,
                    $b->program,
                    $b->appointment_date ? $b->appointment_date->format('Y-m-d') : '',
                    $b->time_slot,
                    ucfirst($b->status),
                    $b->created_at ? $b->created_at->format('Y-m-d H:i') : '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Aggregated Registered Students View
     */
    public function students(Request $request)
    {
        $search = $request->get('search');

        $query = Appointment::select('email', 'name', 'phone', DB::raw('COUNT(*) as total_bookings'), DB::raw('MAX(created_at) as last_booking_at'))
            ->groupBy('email', 'name', 'phone');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $students = $query->latest('last_booking_at')->paginate(20)->withQueryString();

        return view('admin.scheduling.students.index', compact('students', 'search'));
    }

    /**
     * Email Templates Management
     */
    public function emails()
    {
        $templates = EmailTemplate::all();
        return view('admin.scheduling.emails.index', compact('templates'));
    }

    /**
     * Edit Email Template
     */
    public function editEmail(EmailTemplate $template)
    {
        $variables = [
            '{{student_name}}' => 'Student / Candidate Full Name',
            '{{email}}' => 'Student Email Address',
            '{{phone}}' => 'Phone / WhatsApp Number',
            '{{date}}' => 'Scheduled Appointment / Test Date',
            '{{time}}' => 'Scheduled Time Slot',
            '{{registration_number}}' => 'Unique Registration / Enrollment Number (e.g. IETS-2026-000001)',
            '{{test_type}}' => 'Specific IETS Test Type or Counseling Subject',
            '{{purpose}}' => 'Booking purpose description',
            '{{status}}' => 'Booking status (Confirmed, Cancelled, etc.)',
            '{{academy_name}}' => 'Academy Official Name',
            '{{admin_notes}}' => 'Internal counselor instructions or message',
        ];

        return view('admin.scheduling.emails.edit', compact('template', 'variables'));
    }

    /**
     * Update Email Template
     */
    public function updateEmail(Request $request, EmailTemplate $template)
    {
        $request->validate([
            'subject' => 'required|string|max:191',
            'body' => 'required|string',
        ]);

        $template->update([
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        ActivityLog::log('update', 'appointments', "Updated email template: {$template->name}");

        return redirect()->route('admin.scheduling.emails')->with('success', "Template '{$template->name}' updated successfully.");
    }

    /**
     * Reset Email Template to default
     */
    public function resetEmail(EmailTemplate $template)
    {
        $defaults = EmailTemplate::getDefaults();
        if (isset($defaults[$template->slug])) {
            $template->update([
                'subject' => $defaults[$template->slug]['subject'],
                'body' => $defaults[$template->slug]['body'],
            ]);
            return back()->with('success', "Template reset to default configuration.");
        }
        return back()->with('error', "No default found for this template.");
    }

    /**
     * Scheduling Settings
     */
    public function settings()
    {
        $settings = AppointmentSetting::getSettings();
        $defaultIetsCapacity = (int) Setting::get('default_iets_capacity', 15);
        $blockedDates = BlockedDate::latest('date')->get();

        return view('admin.scheduling.settings', compact('settings', 'defaultIetsCapacity', 'blockedDates'));
    }

    /**
     * Update Scheduling Settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'working_days' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required',
            'slot_duration_minutes' => 'required|integer|min:15|max:180',
            'max_per_slot' => 'required|integer|min:1|max:50',
            'default_iets_capacity' => 'required|integer|min:1|max:100',
        ]);

        $settings = AppointmentSetting::getSettings();
        $settings->working_days = $request->working_days;
        $settings->start_time = $request->start_time;
        $settings->end_time = $request->end_time;
        $settings->slot_duration_minutes = (int) $request->slot_duration_minutes;
        $settings->break_start = $request->break_start;
        $settings->break_end = $request->break_end;
        $settings->max_per_slot = (int) $request->max_per_slot;
        $settings->save();

        Setting::set('default_iets_capacity', $request->default_iets_capacity, 'scheduling');

        ActivityLog::log('update', 'appointments', "Updated appointment & IETS scheduling global settings.");

        return back()->with('success', 'Scheduling settings saved successfully.');
    }
}
