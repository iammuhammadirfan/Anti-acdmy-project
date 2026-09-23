<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $action = $request->get('action');
        $module = $request->get('module');

        $query = ActivityLog::with('user')->latest();

        if ($action) {
            $query->where('action', $action);
        }
        if ($module) {
            $query->where('module', $module);
        }

        $logs = $query->paginate(25);

        return view('admin.activity.index', compact('logs', 'action', 'module'));
    }
}
