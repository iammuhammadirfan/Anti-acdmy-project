<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        $date = $request->get('date');
        $search = $request->get('search');

        $query = Appointment::latest('appointment_date');

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
                  ->orWhere('booking_code', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(20);

        return view('admin.appointments.index', compact('appointments', 'status', 'date', 'search'));
    }

    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $appointment->status;
        $appointment->status = $request->status;
        if ($request->filled('admin_notes')) {
            $appointment->admin_notes = $request->admin_notes;
        }
        $appointment->save();

        ActivityLog::log('status_change', 'appointments', "Updated appointment {$appointment->booking_code} status from {$oldStatus} to {$appointment->status}.");

        // Send notifications on status change
        $this->notificationService->sendAppointmentStatusUpdated($appointment, $appointment->status);

        return back()->with('success', "Appointment status updated to {$appointment->status} and notifications dispatched.");
    }

    public function destroy(Appointment $appointment)
    {
        $code = $appointment->booking_code;
        $appointment->delete();
        ActivityLog::log('delete', 'appointments', "Deleted appointment: {$code}");
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted.');
    }
}
