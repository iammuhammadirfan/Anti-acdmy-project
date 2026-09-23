@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('header', 'Edit FAQ')

@section('content')
<div class="max-w-3xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Question Title *</label>
                <input type="text" name="question" value="{{ old('question', $faq->question) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Category *</label>
                    <select name="category" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                        @foreach(['general' => 'General Inquiries', 'iets' => 'IETS & IELTS Tests', 'appointments' => 'Evaluation Bookings', 'courses' => 'Course Formats', 'admissions' => 'Global Admissions', 'facilities' => 'Campus & Labs'] as $cKey => $cLbl)
                            <option value="{{ $cKey }}" {{ $faq->category === $cKey ? 'selected' : '' }}>{{ $cLbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $faq->display_order) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Comprehensive Answer *</label>
                <textarea name="answer" rows="5" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('answer', $faq->answer) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.faqs.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Update FAQ</button>
            </div>
        </form>
    </div>
</div>
@endsection
