<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\AppointmentSlot;
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

    public function index(Request $request)
    {
        $defaultType = $request->get('type', 'counseling');
        if (!in_array($defaultType, ['counseling', 'iets_test'])) {
            $defaultType = 'counseling';
        }

        $settings = AppointmentSetting::getSettings();
        $blockedDates = BlockedDate::pluck('date')->map(function ($d) {
            return $d->format('Y-m-d');
        })->toArray();

        $seo = SeoMeta::getForPage('appointments');

        return view('frontend.appointments.index', compact('settings', 'blockedDates', 'seo', 'defaultType'));
    }

    public function getSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'nullable|string|in:counseling,iets_test',
        ]);

        $dateStr = $request->date;
        $type = $request->get('type', 'counseling');

        $slots = $this->slotService->getSlotsForType($dateStr, $type);

        return response()->json([
            'success' => true,
            'date' => $dateStr,
            'type' => $type,
            'slots' => $slots,
        ]);
    }

    public function book(Request $request)
    {
        $type = $request->input('appointment_type', 'counseling');
        if (!in_array($type, ['counseling', 'iets_test'])) {
            $type = 'counseling';
        }

        $rules = [
            'appointment_type' => 'required|in:counseling,iets_test',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'slot_id' => 'nullable|integer|exists:appointment_slots,id',
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:1000',
        ];

        if ($type === 'iets_test') {
            $rules['test_type'] = 'required|string|max:191';
            $rules['cnic_passport'] = 'nullable|string|max:50';
            $rules['program'] = 'nullable|string|max:191';
        } else {
            $rules['purpose'] = 'required|string|max:191';
        }

        $validated = $request->validate($rules);

        $dateCarbon = Carbon::parse($request->appointment_date);
        $date = $dateCarbon->toDateString();
        $slotTime = AppointmentSlot::normalizeTime($request->time_slot);
        $slotId = $request->slot_id ? (int) $request->slot_id : null;

        // Enforce that appointments cannot be booked for past dates or past timeslots today
        if ($dateCarbon->lt(Carbon::today())) {
            return back()->with('error', 'Cannot book appointments for past dates. Please select an upcoming date.')->withInput();
        }

        if ($dateCarbon->isToday()) {
            $slotDateTime = Carbon::parse($date . ' ' . $slotTime);
            if ($slotDateTime->lte(Carbon::now())) {
                return back()->with('error', "This time slot ({$slotTime}) has already passed for today. Please choose an upcoming time slot.")->withInput();
            }
        }

        // Double booking & capacity protection within database transaction with row locks
        try {
            $appointment = DB::transaction(function () use ($request, $date, $slotTime, $slotId, $type) {
                $slotModel = null;

                if ($slotId) {
                    $slotModel = AppointmentSlot::where('id', $slotId)
                        ->lockForUpdate()
                        ->first();
                } else {
                    // Try to match an explicit slot by date, time, and type
                    $slotModel = AppointmentSlot::where('slot_date', $date)
                        ->where('start_time', $slotTime)
                        ->where('type', $type)
                        ->lockForUpdate()
                        ->first();
                }

                if ($slotModel) {
                    if (!$slotModel->is_active) {
                        throw new \Exception('Sorry, this slot is currently inactive. Please choose another time.');
                    }

                    if ($slotModel->is_past) {
                        throw new \Exception('Sorry, this time slot has already passed for today. Please choose an upcoming time slot.');
                    }

                    // Duplicate check: has this student email already booked this slot?
                    $alreadyRegistered = Appointment::where('slot_id', $slotModel->id)
                        ->where('email', $request->email)
                        ->whereNotIn('status', ['cancelled'])
                        ->exists();

                    if ($alreadyRegistered) {
                        throw new \Exception('You have already registered for this test slot with email ' . $request->email . '.');
                    }

                    // Capacity check with row lock
                    $currentBookings = Appointment::where('slot_id', $slotModel->id)
                        ->whereNotIn('status', ['cancelled'])
                        ->lockForUpdate()
                        ->count();

                    if ($currentBookings >= (int) $slotModel->capacity) {
                        throw new \Exception('This slot is fully booked. Maximum capacity reached.');
                    }
                } else {
                    if ($type === 'iets_test') {
                        throw new \Exception('Please select an active, pre-scheduled IETS test slot.');
                    }

                    // Counseling fallback check against AppointmentSetting max_per_slot
                    $settings = AppointmentSetting::getSettings();
                    $maxCap = (int) ($settings->max_per_slot ?? 1);

                    $alreadyRegistered = Appointment::where('appointment_date', $date)
                        ->where('time_slot', $slotTime)
                        ->where('type', 'counseling')
                        ->where('email', $request->email)
                        ->whereNotIn('status', ['cancelled'])
                        ->exists();

                    if ($alreadyRegistered) {
                        throw new \Exception('You have already booked a counseling session at this date and time.');
                    }

                    $currentBookings = Appointment::where('appointment_date', $date)
                        ->where('time_slot', $slotTime)
                        ->where('type', 'counseling')
                        ->whereNotIn('status', ['cancelled'])
                        ->lockForUpdate()
                        ->count();

                    if ($currentBookings >= $maxCap) {
                        throw new \Exception('This counseling time slot has reached maximum capacity.');
                    }
                }

                $purpose = ($type === 'iets_test')
                    ? ($request->test_type ?: 'IETS Official Test')
                    : $request->purpose;

                return Appointment::create([
                    'slot_id' => $slotModel ? $slotModel->id : null,
                    'type' => $type,
                    'test_type' => ($type === 'iets_test') ? $request->test_type : null,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'whatsapp' => $request->whatsapp ?: $request->phone,
                    'cnic_passport' => $request->cnic_passport,
                    'program' => $request->program,
                    'appointment_date' => $date,
                    'time_slot' => $slotTime,
                    'purpose' => $purpose,
                    'message' => $request->message,
                    'status' => 'confirmed',
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        // Trigger email and WhatsApp notifications
        $this->notificationService->sendAppointmentCreated($appointment);

        $code = $appointment->registration_number ?: $appointment->booking_code;
        return redirect()->route('appointments.success', ['code' => $code]);
    }

    public function success(Request $request)
    {
        $code = $request->get('code');
        $appointment = Appointment::where('registration_number', $code)
            ->orWhere('booking_code', $code)
            ->firstOrFail();

        return view('frontend.appointments.success', compact('appointment'));
    }
}
