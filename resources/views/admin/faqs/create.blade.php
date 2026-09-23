@extends('layouts.admin')

@section('title', 'Add FAQ')
@section('header', 'Create Frequently Asked Question')

@section('content')
<div class="max-w-3xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Question Title *</label>
                <input type="text" name="question" value="{{ old('question') }}" required placeholder="e.g. What is the fee structure for Academic Masterclasses?" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Category *</label>
                    <select name="category" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                        <option value="general">General Inquiries</option>
                        <option value="iets">IETS & IELTS Tests</option>
                        <option value="appointments">Evaluation Bookings</option>
                        <option value="courses">Course Formats</option>
                        <option value="admissions">Global Admissions</option>
                        <option value="facilities">Campus & Labs</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Comprehensive Answer *</label>
                <textarea name="answer" rows="5" required placeholder="Provide a detailed, clear explanation for students..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('answer') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.faqs.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Save FAQ</button>
            </div>
        </form>
    </div>
</div>
@endsection
