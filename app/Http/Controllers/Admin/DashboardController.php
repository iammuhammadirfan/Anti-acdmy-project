<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\IetsProgram;
use App\Models\IetsResult;
use App\Models\Statistic;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_teachers' => Teacher::count(),
            'total_students' => Statistic::where('metric_key', 'total_students')->value('value') ?: '2,500+',
            'iets_programs' => IetsProgram::count(),
            'iets_results' => IetsResult::count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::pending()->count(),
            'confirmed_appointments' => Appointment::confirmed()->count(),
            'total_blogs' => Blog::count(),
            'total_videos' => Video::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'total_users' => User::count(),
        ];

        // Monthly appointments chart (last 6 months)
        $monthlyAppointments = [];
        $monthsLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStr = $month->format('M Y');
            $monthsLabels[] = $monthStr;
            $monthlyAppointments[] = Appointment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Status counts for doughnut chart
        $statusCounts = [
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        // IETS Band Distribution
        $bandDistribution = [
            'Band 6.5 - 7.0' => IetsResult::whereBetween('overall_band', [6.5, 7.0])->count(),
            'Band 7.5 - 8.0' => IetsResult::whereBetween('overall_band', [7.5, 8.0])->count(),
            'Band 8.5 - 9.0' => IetsResult::where('overall_band', '>=', 8.5)->count(),
        ];

        $recentAppointments = Appointment::latest()->limit(5)->get();
        $recentMessages = ContactMessage::latest()->limit(5)->get();
        $recentActivities = ActivityLog::with('user')->latest()->limit(6)->get();

        return view('admin.dashboard.index', compact(
            'metrics',
            'monthsLabels',
            'monthlyAppointments',
            'statusCounts',
            'bandDistribution',
            'recentAppointments',
            'recentMessages',
            'recentActivities'
        ));
    }
}
