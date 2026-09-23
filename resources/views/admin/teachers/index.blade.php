@extends('layouts.admin')

@section('title', 'Teachers Directory')
@section('page_title', 'Faculty & Teacher Management')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Academic Faculty</h2>
            <p class="text-xs text-slate-500">Manage instructor profiles, qualifications, subjects, and social links.</p>
        </div>
        <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add New Teacher</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teachers as $teacher)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                            @if($teacher->profile_image)
                                <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-bold text-xl text-brand-600 bg-brand-50">
                                    {{ substr($teacher->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-slate-900 text-base truncate">{{ $teacher->name }}</h4>
                            <span class="text-xs font-semibold text-brand-600 block">{{ $teacher->designation }}</span>
                            <span class="text-[11px] text-slate-500 block truncate">{{ $teacher->qualification }}</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs space-y-1.5 text-slate-600">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Subject:</span>
                            <span class="font-semibold text-slate-800">{{ $teacher->subject }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Experience:</span>
                            <span class="font-semibold text-slate-800">{{ $teacher->experience ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Status:</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $teacher->status ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $teacher->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-xs font-bold text-brand-600 hover:text-brand-800 flex items-center gap-1">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Profile
                    </a>
                    <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Delete this teacher profile?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                No teachers recorded yet. Click "Add New Teacher" to create a profile.
            </div>
        @endforelse
    </div>
</div>
@endsection
