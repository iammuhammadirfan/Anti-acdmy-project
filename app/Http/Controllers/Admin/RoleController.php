<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions', 'users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $modules = UserController::$modules;
        $actions = ['view', 'create', 'edit', 'delete', 'publish'];
        return view('admin.roles.create', compact('modules', 'actions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $permissionIds = [];
            foreach ($request->permissions as $module => $actions) {
                foreach ($actions as $action => $val) {
                    $perm = Permission::firstOrCreate(
                        ['module' => $module, 'action' => $action],
                        ['description' => ucfirst($action) . ' ' . ucfirst($module)]
                    );
                    $permissionIds[] = $perm->id;
                }
            }
            $role->permissions()->sync($permissionIds);
        }

        ActivityLog::log('create', 'roles', "Created role: {$role->name}");

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $modules = UserController::$modules;
        $actions = ['view', 'create', 'edit', 'delete', 'publish'];
        $rolePermissions = $role->permissions()->get()->groupBy('module');

        return view('admin.roles.edit', compact('role', 'modules', 'actions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();

        if ($request->has('permissions')) {
            $permissionIds = [];
            foreach ($request->permissions as $module => $actions) {
                foreach ($actions as $action => $val) {
                    $perm = Permission::firstOrCreate(
                        ['module' => $module, 'action' => $action],
                        ['description' => ucfirst($action) . ' ' . ucfirst($module)]
                    );
                    $permissionIds[] = $perm->id;
                }
            }
            $role->permissions()->sync($permissionIds);
        } else {
            $role->permissions()->detach();
        }

        ActivityLog::log('update', 'roles', "Updated role: {$role->name}");

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->slug === 'super-admin') {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        $name = $role->name;
        $role->delete();

        ActivityLog::log('delete', 'roles', "Deleted role: {$name}");

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
