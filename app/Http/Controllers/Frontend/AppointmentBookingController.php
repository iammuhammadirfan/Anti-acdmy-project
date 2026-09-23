<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\BlockedDate;
use App\Models\SeoMeta;
use App\Services\AppointmentSlotService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentBookingController extends Controller
{
    protected AppointmentSlotService $slotService;
    protected NotificationService $notificationService;

    public function __construct(AppointmentSlotService $slotService, NotificationService $notificationService)
    {
        $this->slotService = $slotService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $settings = AppointmentSetting::getSettings();
        $blockedDates = BlockedDate::pluck('date')->map(function ($d) {
            return $d->format('Y-m-d');
        })->toArray();

        $seo = SeoMeta::getForPage('appointments');

        return view('frontend.appointments.index', compact('settings', 'blockedDates', 'seo'));
    }

    public function getSlots(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        $dateStr = $request->date;
        $slots = $this->slotService->getAvailableSlots($dateStr);

        return response()->json([
            'success' => true,
            'date' => $dateStr,
            'slots' => $slots,
        ]);
    }

    public function book(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'purpose' => 'required|string|max:191',
            'message' => 'nullable|string',
        ]);

        $date = Carbon::parse($request->appointment_date)->toDateString();
        $slot = trim($request->time_slot);

        // Prevent double booking via database transaction with lock
        $appointment = DB::transaction(function () use ($request, $date, $slot) {
            if (!$this->slotService->isSlotAvailable($date, $slot)) {
                return null;
            }

            return Appointment::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp ?: $request->phone,
                'appointment_date' => $date,
                'time_slot' => $slot,
                'purpose' => $request->purpose,
                'message' => $request->message,
                'status' => 'pending',
            ]);
        });

        if (!$appointment) {
            return back()->with('error', 'Sorry, that time slot was just taken or is no longer available. Please select another time slot.')->withInput();
        }

        // Trigger notifications
        $this->notificationService->sendAppointmentCreated($appointment);

        return redirect()->route('appointments.success', ['code' => $appointment->booking_code]);
    }

    public function success(Request $request)
    {
        $code = $request->get('code');
        $appointment = Appointment::where('booking_code', $code)->firstOrFail();
        return view('frontend.appointments.success', compact('appointment'));
    }
}
