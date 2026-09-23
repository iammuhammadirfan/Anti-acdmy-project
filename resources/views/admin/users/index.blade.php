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
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Add New Staff User</span>
        </a>
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
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-2.5 py-1 rounded-full text-xs font-bold uppercase transition 
                                                   {{ $user->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $user->is_active ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-slate-500 hover:text-brand-600 inline-block transition" title="Edit User">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                @if($user->id !== auth()->id())
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
</div>
@endsection
