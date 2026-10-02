@extends('layouts.admin')

@section('title', 'Staff & User Management')
@section('page_title', 'Staff & Role Assignment')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">System Users &amp; Staff</h2>
            <p class="text-xs text-slate-500">Manage administrator accounts, roles, and granular module section permissions.</p>
        </div>
        @if(auth()->user()->hasPermission('users', 'create'))
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Add New Staff User</span>
        </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-5">User</th>
                        <th class="py-3.5 px-4">Contact</th>
                        <th class="py-3.5 px-4">Roles</th>
                        <th class="py-3.5 px-4">Section Permissions</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-brand-600 font-extrabold flex items-center justify-center shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">{{ $user->phone ?: 'N/A' }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400">Custom Sections</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($user->isSuperAdmin())
                                    <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">All Modules (Full Access)</span>
                                @else
                                    <span class="text-xs text-slate-600 font-medium">{{ $user->sectionPermissions->count() }} module(s) assigned</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if(auth()->user()->hasPermission('users', 'edit'))
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-2.5 py-1 rounded-full text-xs font-bold uppercase transition 
                                                   {{ $user->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $user->is_active ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>
                                @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Disabled' }}
                                </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                @if(auth()->user()->hasPermission('users', 'edit'))
                                <button type="button" 
                                        onclick="openResetModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->email }}')" 
                                        class="p-1.5 text-slate-500 hover:text-amber-600 inline-block transition" 
                                        title="Quick Reset Password">
                                    <i data-lucide="key-round" class="w-4 h-4"></i>
                                </button>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-slate-500 hover:text-brand-600 inline-block transition" title="Edit User">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                @endif
                                @if($user->id !== auth()->id() && auth()->user()->hasPermission('users', 'delete'))
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 inline-block transition" title="Delete User">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Password Reset Modal for Super Admin -->
    <div id="reset-password-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 relative">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i data-lucide="key-round" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Reset Staff Password</h3>
                        <p class="text-[11px] text-slate-400 truncate max-w-[240px]" id="modal-user-info">Set a new password for staff member</p>
                    </div>
                </div>
                <button type="button" onclick="closeResetModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="reset-password-form" method="POST" action="" class="space-y-4 pt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">New Password (Min 8 Characters)</label>
                    <div class="relative">
                        <input type="text" id="modal-password-input" name="password" required minlength="8"
                               placeholder="Enter new password or click Auto..."
                               class="w-full pl-3.5 pr-20 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-600 font-mono">
                        <button type="button" onclick="generateRandomPassword()" 
                                class="absolute inset-y-1.5 right-1.5 px-2.5 bg-amber-100 hover:bg-amber-200 text-amber-900 text-[11px] font-bold rounded-lg transition flex items-center gap-1">
                            <i data-lucide="sparkles" class="w-3 h-3"></i>
                            <span>Auto</span>
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Click "Auto" to generate a secure random password automatically.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeResetModal()" 
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-sm transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openResetModal(userId, userName, userEmail) {
        const modal = document.getElementById('reset-password-modal');
        const form = document.getElementById('reset-password-form');
        const userInfo = document.getElementById('modal-user-info');
        const input = document.getElementById('modal-password-input');

        form.action = `/admin/users/${userId}/reset-password`;
        userInfo.innerText = `${userName} (${userEmail})`;
        input.value = '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        input.focus();
    }

    function closeResetModal() {
        const modal = document.getElementById('reset-password-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function generateRandomPassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
        let result = '';
        for (let i = 0; i < 10; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('modal-password-input').value = result;
    }
</script>
@endpush
@endsection
