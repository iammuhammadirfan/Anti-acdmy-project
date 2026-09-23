@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('page_title', 'My Profile & Security')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
                <div class="w-16 h-16 rounded-2xl bg-brand-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-500">{{ $user->email }} • {{ $user->isSuperAdmin() ? 'Super Admin' : 'Staff' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100">
                <h4 class="font-bold text-slate-900 text-sm mb-1">Change Password</h4>
                <p class="text-xs text-slate-500 mb-4">Leave blank if you do not want to change your password.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">New Password</label>
                        <input type="password" name="password" minlength="8"
                               placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" minlength="8"
                               placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
