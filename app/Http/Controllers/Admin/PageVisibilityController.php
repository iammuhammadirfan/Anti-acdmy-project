<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\PageVisibilityService;
use Illuminate\Http\Request;

class PageVisibilityController extends Controller
{
    /**
     * Display page visibility manager interface.
     * Accessible exclusively to administrators.
     */
    public function index()
    {
        $allPages = PageVisibilityService::getAllPages();
        $visibleKeys = PageVisibilityService::getVisiblePageKeys();

        $totalCount = count($allPages);
        $enabledCount = count($visibleKeys);
        $disabledCount = $totalCount - $enabledCount;

        return view('admin.page_visibility.index', compact('allPages', 'visibleKeys', 'totalCount', 'enabledCount', 'disabledCount'));
    }

    /**
     * Update all visible pages in bulk.
     */
    public function update(Request $request)
    {
        $pages = $request->input('pages', []);
        if (!is_array($pages)) {
            $pages = [];
        }

        PageVisibilityService::setVisiblePages($pages);

        ActivityLog::log('update', 'settings', "Updated website page visibility. Active pages: " . implode(', ', $pages));

        return back()->with('success', 'Website page visibility updated successfully! Only enabled pages are now accessible and visible on the website.');
    }

    /**
     * Toggle visibility of an individual page via AJAX or form.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'page_key' => 'required|string',
        ]);

        $pageKey = $request->input('page_key');
        $allPages = PageVisibilityService::getAllPages();

        if (!isset($allPages[$pageKey])) {
            return response()->json(['success' => false, 'message' => 'Invalid page key.'], 400);
        }

        $newStatus = PageVisibilityService::togglePage($pageKey);
        $pageName = $allPages[$pageKey]['name'];

        ActivityLog::log('status_change', 'settings', "Toggled page '{$pageName}' visibility to " . ($newStatus ? 'Visible' : 'Hidden'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'page_key' => $pageKey,
                'is_visible' => $newStatus,
                'message' => "Page '{$pageName}' is now " . ($newStatus ? 'visible on website' : 'hidden from website') . '.',
            ]);
        }

        return back()->with('success', "Page '{$pageName}' is now " . ($newStatus ? 'visible on website' : 'hidden from website') . '.');
    }
}
