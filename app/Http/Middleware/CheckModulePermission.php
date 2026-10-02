<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, string $module, ?string $action = null): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated.');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $resolvedAction = $action;
        $method = $request->route() ? strtolower($request->route()->getActionMethod()) : '';
        $httpMethod = $request->method();

        if (empty($resolvedAction)) {
            if (in_array($method, ['create', 'store', 'batchgenerateslots', 'storeslot'])) {
                $resolvedAction = 'create';
            } elseif (in_array($method, ['edit', 'update', 'updateslot', 'toggleslot', 'updatebookingstatus', 'reschedulebooking', 'toggle', 'resetemail', 'updateemail', 'updatesettings', 'toggleactive', 'resetpassword'])) {
                $resolvedAction = 'edit';
            } elseif (in_array($method, ['destroy', 'destroyslot', 'bulkdestroyslots', 'destroybooking', 'bulkdestroybookings', 'delete'])) {
                $resolvedAction = 'delete';
            } elseif ($httpMethod === 'POST' || $httpMethod === 'PUT' || $httpMethod === 'PATCH') {
                $resolvedAction = ($httpMethod === 'POST' && str_contains($method, 'create')) ? 'create' : 'edit';
            } elseif ($httpMethod === 'DELETE') {
                $resolvedAction = 'delete';
            } else {
                $resolvedAction = 'view';
            }
        }

        // Sub-module routing for scheduling & appointment slots
        $checkModule = $module;
        if ($module === 'appointments') {
            $slotType = $request->input('type');

            if ($method === 'ietsschedule' || $slotType === 'iets_test') {
                $checkModule = 'scheduling_iets';
            } elseif ($method === 'counselingschedule' || $slotType === 'counseling') {
                $checkModule = 'scheduling_counseling';
            } elseif ($method === 'dashboard' || $method === 'slots') {
                if ($user->canAccessAnySection(['appointments', 'scheduling_iets', 'scheduling_counseling', 'calendar'])) {
                    return $next($request);
                }
            }
        }

        if (!$user->canAccessSection($checkModule, $resolvedAction)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Unauthorized: You do not have permission to {$resolvedAction} {$checkModule}.",
                ], 403);
            }

            abort(403, "You do not have permission to {$resolvedAction} {$checkModule}.");
        }

        return $next($request);
    }
}
