@extends('layouts.admin')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role & Permissions')

@section('content')
<div class="max-w-4xl space-y-6">
    <a href="{{ route('admin.roles.index') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Roles
    </a>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-base">Role Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Role Name *</label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
                    <input type="text" name="description" value="{{ old('description', $role->description) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-base">Permissions Matrix</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[11px] tracking-wider border-y border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Module</th>
                            @foreach($actions as $action)
                                <th class="py-3 px-4 text-center capitalize">{{ $action }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($modules as $key => $label)
                            @php
                                $modPerms = $rolePermissions->get($key, collect())->keyBy('action');
                            @endphp
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $label }}</td>
                                @foreach($actions as $action)
                                    <td class="py-3 px-4 text-center">
                                        <input type="checkbox" name="permissions[{{ $key }}][{{ $action }}]" value="1" 
                                               {{ $modPerms->has($action) ? 'checked' : '' }}
                                               class="rounded text-brand-600">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.roles.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold transition">Cancel</a>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">Save Permissions</button>
        </div>
    </form>
</div>
@endsection
