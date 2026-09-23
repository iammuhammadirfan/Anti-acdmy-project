<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, string $module, string $action = 'view'): Response
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

        if (!$user->canAccessSection($module, $action)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Unauthorized access to {$module} ({$action}).",
                ], 403);
            }

            abort(403, "You do not have permission to {$action} {$module}.");
        }

        return $next($request);
    }
}
