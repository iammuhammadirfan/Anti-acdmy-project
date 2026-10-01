<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Setting;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark all booking alerts as read
     */
    public function markAllRead(Request $request)
    {
        try {
            Setting::set('admin_notifications_cleared_at', now()->toDateTimeString());
            Setting::set('admin_read_booking_ids', '[]');
            session()->put('admin_notifications_cleared_at', now()->toDateTimeString());
        } catch (\Throwable $e) {}

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0,
                'message' => 'All booking notifications marked as read.'
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark a single booking notification as read and redirect to its show page
     */
    public function readAndRedirect(Appointment $booking)
    {
        $this->markBookingAsRead($booking->id);

        return redirect()->route('admin.scheduling.booking.show', $booking->id);
    }

    /**
     * Mark a single booking as read via AJAX
     */
    public function markSingleRead(Appointment $booking)
    {
        $this->markBookingAsRead($booking->id);

        return response()->json([
            'success' => true,
            'booking_id' => $booking->id
        ]);
    }

    /**
     * Internal helper to record read booking ID
     */
    protected function markBookingAsRead(int $id): void
    {
        try {
            $raw = Setting::get('admin_read_booking_ids', '[]');
            $readIds = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
            if (!is_array($readIds)) {
                $readIds = [];
            }

            if (!in_array($id, $readIds)) {
                $readIds[] = $id;
                // Keep only last 200 IDs to keep size compact
                $readIds = array_values(array_slice($readIds, -200));
                Setting::set('admin_read_booking_ids', json_encode($readIds));
            }

            // Also keep in session
            $sessionRead = session()->get('admin_read_booking_ids', []);
            if (!in_array($id, $sessionRead)) {
                $sessionRead[] = $id;
                session()->put('admin_read_booking_ids', $sessionRead);
            }
        } catch (\Throwable $e) {}
    }
}
