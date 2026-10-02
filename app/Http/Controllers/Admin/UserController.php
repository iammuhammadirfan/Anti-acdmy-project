<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSectionPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public static array $modules = [
        'homepage' => 'Homepage Sections',
        'sliders' => 'Hero Sliders',
        'about' => 'About & History',
        'teachers' => 'Teachers Management',
        'classrooms' => 'Classrooms & Labs',
        'gallery' => 'Campus Gallery',
        'students' => 'Statistics & Students',
        'iets' => 'IETS Programs',
        'iets_results' => 'IETS Results',
        'videos' => 'Videos & Vlogs',
        'blog' => 'Blog & News',
        'faq' => 'FAQs Management',
        'appointments' => 'Appointment Bookings (View, Edit, Reschedule, Delete)',
        'scheduling_iets' => 'IETS Test Slots (View, Create, Edit, Delete)',
        'scheduling_counseling' => 'Counseling Slots (View, Create, Edit, Delete)',
        'calendar' => 'Calendar & Date Blocking',
        'contact' => 'Contact Inquiries',
        'media' => 'Media Library',
        'seo' => 'Technical SEO & Sitemaps',
        'geo' => 'GEO & Schema Markup',
        'ai' => 'Agentic AI Chatbot',
        'users' => 'Users & Staff',
        'roles' => 'Roles & Permissions',
        'settings' => 'System Settings',
    ];

    public function index()
    {
        $users = User::with('roles', 'sectionPermissions')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $modules = self::$modules;
        return view('admin.users.create', compact('roles', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'nullable|string|max:50',
            'roles' => 'array',
            'sections' => 'array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        // Save dynamic section permissions
        if ($request->has('sections')) {
            $sections = $request->input('sections', []);
            $hasAnyCustom = false;
            foreach ($sections as $acts) {
                if (!empty($acts)) {
                    $hasAnyCustom = true;
                    break;
                }
            }

            if ($hasAnyCustom) {
                foreach (self::$modules as $moduleKey => $label) {
                    $actions = $sections[$moduleKey] ?? [];
                    $canCreate = !empty($actions['create']);
                    $canEdit = !empty($actions['edit']);
                    $canDelete = !empty($actions['delete']);
                    $canPublish = !empty($actions['publish']);
                    $canView = !empty($actions['view']) || $canCreate || $canEdit || $canDelete || $canPublish;

                    UserSectionPermission::create([
                        'user_id' => $user->id,
                        'module' => $moduleKey,
                        'can_view' => $canView,
                        'can_create' => $canCreate,
                        'can_edit' => $canEdit,
                        'can_delete' => $canDelete,
                        'can_publish' => $canPublish,
                    ]);
                }
            }
        }

        ActivityLog::log('create', 'users', "Created staff user: {$user->name}");

        return redirect()->route('admin.users.index')->with('success', 'User created successfully with role and module assignments.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $modules = self::$modules;
        $userRoleIds = $user->roles()->pluck('id')->toArray();
        $userPermissions = $user->sectionPermissions()->get()->keyBy('module');

        return view('admin.users.edit', compact('user', 'roles', 'modules', 'userRoleIds', 'userPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'phone' => 'nullable|string|max:50',
            'roles' => 'array',
            'sections' => 'array',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->is_active = $request->boolean('is_active', true);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        // Sync module section permissions
        $user->sectionPermissions()->delete();
        if ($request->has('sections')) {
            $sections = $request->input('sections', []);
            $hasAnyCustom = false;
            foreach ($sections as $acts) {
                if (!empty($acts)) {
                    $hasAnyCustom = true;
                    break;
                }
            }

            if ($hasAnyCustom) {
                foreach (self::$modules as $moduleKey => $label) {
                    $actions = $sections[$moduleKey] ?? [];
                    $canCreate = !empty($actions['create']);
                    $canEdit = !empty($actions['edit']);
                    $canDelete = !empty($actions['delete']);
                    $canPublish = !empty($actions['publish']);
                    $canView = !empty($actions['view']) || $canCreate || $canEdit || $canDelete || $canPublish;

                    UserSectionPermission::create([
                        'user_id' => $user->id,
                        'module' => $moduleKey,
                        'can_view' => $canView,
                        'can_create' => $canCreate,
                        'can_edit' => $canEdit,
                        'can_delete' => $canDelete,
                        'can_publish' => $canPublish,
                    ]);
                }
            }
        }

        ActivityLog::log('update', 'users', "Updated staff user: {$user->name}");

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $state = $user->is_active ? 'activated' : 'deactivated';
        ActivityLog::log('status_change', 'users', "User {$user->name} was {$state}.");

        return back()->with('success', "User account {$state}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $name = $user->name;
        $user->delete();

        ActivityLog::log('delete', 'users', "Deleted user: {$name}");

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLog::log('admin_password_reset', 'users', "Admin reset password for staff user: {$user->name} ({$user->email})");

        return back()->with('success', "Password for user {$user->name} ({$user->email}) has been updated successfully.");
    }
}
