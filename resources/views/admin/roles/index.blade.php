@extends('layouts.admin')

@section('title', 'Roles & RBAC')
@section('page_title', 'Roles & Permissions Matrix')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">System Roles</h2>
            <p class="text-xs text-slate-500">Define administrative roles and global module permissions.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" 
           class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Create New Role</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-slate-900 text-base">{{ $role->name }}</h4>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $role->slug }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">{{ $role->description ?: 'Standard role permissions.' }}</p>

                    <div class="text-xs text-slate-600 space-y-1 py-2 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <span>Assigned Staff:</span>
                            <span class="font-bold text-slate-900">{{ $role->users->count() }} user(s)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Permissions:</span>
                            <span class="font-bold text-brand-600">{{ $role->permissions->count() }} action(s)</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-xs font-semibold text-brand-600 hover:text-brand-800 flex items-center gap-1">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Permissions
                    </a>
                    @if($role->slug !== 'super-admin')
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete this role?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
