<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\BlockedDate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $settings = AppointmentSetting::getSettings();
        $blockedDates = BlockedDate::latest('date')->get();

        // Fetch calendar appointments for current month view
        $month = $request->get('month', date('Y-m'));
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $calendarAppointments = Appointment::whereBetween('appointment_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->groupBy(function ($item) {
                return $item->appointment_date->format('Y-m-d');
            });

        return view('admin.calendar.index', compact('settings', 'blockedDates', 'calendarAppointments', 'month'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'working_days' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required',
            'slot_duration_minutes' => 'required|integer|min:15|max:120',
            'max_per_slot' => 'required|integer|min:1',
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

        ActivityLog::log('update', 'calendar', "Updated appointment calendar working schedule and slot rules.");

        return back()->with('success', 'Calendar settings saved successfully.');
    }

    public function blockDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:blocked_dates,date',
            'reason' => 'nullable|string|max:191',
        ]);

        BlockedDate::create([
            'date' => $request->date,
            'reason' => $request->reason,
        ]);

        ActivityLog::log('create', 'calendar', "Blocked date: {$request->date} ({$request->reason})");

        return back()->with('success', 'Date added to blocked list.');
    }

    public function unblockDate(BlockedDate $blockedDate)
    {
        $d = $blockedDate->date->format('Y-m-d');
        $blockedDate->delete();
        ActivityLog::log('delete', 'calendar', "Unblocked date: {$d}");
        return back()->with('success', 'Date unblocked.');
    }
}
