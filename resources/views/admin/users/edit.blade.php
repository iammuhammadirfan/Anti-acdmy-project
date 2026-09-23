@extends('layouts.admin')

@section('title', 'Edit Staff User')
@section('page_title', 'Edit Staff User & Permissions')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to User List
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic User Details -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
            <h3 class="font-bold text-slate-900 text-base">Account Credentials</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Reset Password</label>
                    <input type="password" name="password" minlength="8" placeholder="Leave empty to keep current"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Role Checkboxes -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Assign Roles</label>
                <div class="flex flex-wrap gap-4">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200 text-sm">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                   {{ in_array($role->id, $userRoleIds) ? 'checked' : '' }}
                                   class="rounded text-brand-600 focus:ring-brand-500">
                            <span class="font-semibold text-slate-800">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Dynamic Website Section Assignment Matrix -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Dynamic Module &amp; Section Assignment</h3>
                <p class="text-xs text-slate-500">Super Admin can customize which sections this staff user can view, edit, create, delete, and publish.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[11px] tracking-wider border-y border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Module / Section</th>
                            <th class="py-3 px-4 text-center">View</th>
                            <th class="py-3 px-4 text-center">Create</th>
                            <th class="py-3 px-4 text-center">Edit</th>
                            <th class="py-3 px-4 text-center">Delete</th>
                            <th class="py-3 px-4 text-center">Publish</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($modules as $key => $label)
                            @php
                                $perm = $userPermissions->get($key);
                            @endphp
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $label }}</td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][view]" value="1" 
                                           {{ $perm && $perm->can_view ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][create]" value="1" 
                                           {{ $perm && $perm->can_create ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][edit]" value="1" 
                                           {{ $perm && $perm->can_edit ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][delete]" value="1" 
                                           {{ $perm && $perm->can_delete ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][publish]" value="1" 
                                           {{ $perm && $perm->can_publish ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold transition">Cancel</a>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">Save User Permissions</button>
        </div>
    </form>
</div>
@endsection
