<?php

namespace App\Http\Middleware;

use App\Services\PageVisibilityService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPageVisibility
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $pageKey): Response
    {
        $isVisible = PageVisibilityService::isPageVisible($pageKey);

        if (!$isVisible) {
            // If logged in as Super Admin or Admin, allow them to view with preview flag
            if ($request->user() && ($request->user()->isSuperAdmin() || $request->user()->hasRole('admin'))) {
                view()->share('isAdminPreview', true);
                view()->share('hiddenPageKey', $pageKey);
                return $next($request);
            }

            // Public visitors cannot access the hidden page
            return redirect()->route('home')->with('info', 'The requested page is currently disabled or undergoing updates by the administration.');
        }

        return $next($request);
    }
}
