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

                <div x-data="{ showPass: false }">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Reset Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" minlength="8" placeholder="Leave empty to keep current"
                               class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        <button type="button" @click="showPass = !showPass" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none"
                                :title="showPass ? 'Hide Password' : 'Show Password'">
                            <i data-lucide="eye" class="w-4 h-4" x-show="!showPass"></i>
                            <i data-lucide="eye-off" class="w-4 h-4" x-show="showPass" x-cloak></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Role Toggles (ON / OFF switches) -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Assign Roles</label>
                        <p class="text-[11px] text-slate-500">Toggle roles ON or OFF to grant or revoke system roles.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($roles as $role)
                        @php
                            $isAssigned = in_array($role->id, $userRoleIds);
                        @endphp
                        <label class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-slate-300 transition-all cursor-pointer shadow-xs select-none">
                            <span class="font-semibold text-sm text-slate-800 pr-2">{{ $role->name }}</span>
                            <div class="relative inline-flex items-center shrink-0">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                       {{ $isAssigned ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Dynamic Website Section Assignment Matrix -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Dynamic Module &amp; Section Assignment</h3>
                    <p class="text-xs text-slate-500">Super Admin can customize which sections this staff user can view, edit, create, delete, and publish.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="toggleColumn('view', true)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">Select All View</button>
                    <button type="button" onclick="toggleAllMatrix(true)" class="px-2.5 py-1 bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold rounded-lg transition">Select All Full</button>
                    <button type="button" onclick="toggleAllMatrix(false)" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-lg transition">Clear All</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="permission-matrix">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[11px] tracking-wider border-y border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Module / Section</th>
                            <th class="py-3 px-4 text-center cursor-pointer hover:bg-slate-100" onclick="toggleColumnHeader('view')">View</th>
                            <th class="py-3 px-4 text-center cursor-pointer hover:bg-slate-100" onclick="toggleColumnHeader('create')">Create</th>
                            <th class="py-3 px-4 text-center cursor-pointer hover:bg-slate-100" onclick="toggleColumnHeader('edit')">Edit</th>
                            <th class="py-3 px-4 text-center cursor-pointer hover:bg-slate-100" onclick="toggleColumnHeader('delete')">Delete</th>
                            <th class="py-3 px-4 text-center cursor-pointer hover:bg-slate-100" onclick="toggleColumnHeader('publish')">Publish</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($modules as $key => $label)
                            @php
                                $perm = $userPermissions->get($key);
                            @endphp
                            <tr class="hover:bg-slate-50/50 {{ in_array($key, ['appointments', 'scheduling_iets', 'scheduling_counseling']) ? 'bg-amber-50/20' : '' }}" data-module="{{ $key }}">
                                <td class="py-3 px-4 font-semibold text-slate-900">
                                    <div class="flex items-center gap-2">
                                        @if($key === 'scheduling_iets')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">IELTS</span>
                                        @elseif($key === 'scheduling_counseling')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-blue-100 text-blue-800">Counseling</span>
                                        @elseif($key === 'appointments')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-purple-100 text-purple-800">Bookings</span>
                                        @endif
                                        <span>{{ $label }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][view]" value="1" data-action="view"
                                           {{ $perm && $perm->can_view ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][create]" value="1" data-action="create" onchange="autoEnableView(this)"
                                           {{ $perm && $perm->can_create ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][edit]" value="1" data-action="edit" onchange="autoEnableView(this)"
                                           {{ $perm && $perm->can_edit ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][delete]" value="1" data-action="delete" onchange="autoEnableView(this)"
                                           {{ $perm && $perm->can_delete ? 'checked' : '' }} class="rounded text-brand-600">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="sections[{{ $key }}][publish]" value="1" data-action="publish" onchange="autoEnableView(this)"
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

<script>
function autoEnableView(el) {
    if (el.checked) {
        const row = el.closest('tr');
        const viewCheckbox = row.querySelector('input[data-action="view"]');
        if (viewCheckbox) {
            viewCheckbox.checked = true;
        }
    }
}

function toggleColumn(action, state) {
    document.querySelectorAll(`#permission-matrix input[data-action="${action}"]`).forEach(cb => {
        cb.checked = state;
    });
}

function toggleColumnHeader(action) {
    const cbs = document.querySelectorAll(`#permission-matrix input[data-action="${action}"]`);
    const anyUnchecked = Array.from(cbs).some(cb => !cb.checked);
    cbs.forEach(cb => {
        cb.checked = anyUnchecked;
        if (anyUnchecked && action !== 'view') {
            autoEnableView(cb);
        }
    });
}

function toggleAllMatrix(state) {
    document.querySelectorAll('#permission-matrix input[type="checkbox"]').forEach(cb => {
        cb.checked = state;
    });
}
</script>
@endsection
