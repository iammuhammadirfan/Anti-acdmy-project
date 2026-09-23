@extends('layouts.admin')

@section('title', 'Add Statistic')
@section('header', 'Create Social Proof Metric')

@section('content')
<div class="max-w-2xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.statistics.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Metric Key (Identifier) *</label>
                    <input type="text" name="metric_key" value="{{ old('metric_key') }}" required placeholder="e.g. students_enrolled" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Public Label *</label>
                    <input type="text" name="label" value="{{ old('label') }}" required placeholder="e.g. Scholars Enrolled Globally" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Numeric Value *</label>
                    <input type="text" name="value" value="{{ old('value') }}" required placeholder="e.g. 15,000" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 font-bold">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Suffix Symbol</label>
                    <input type="text" name="suffix" value="{{ old('suffix', '+') }}" placeholder="e.g. + or %" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 font-bold">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Icon Identifier (Optional)</label>
                <input type="text" name="icon" value="{{ old('icon', 'academic-cap') }}" placeholder="academic-cap, globe-alt, star, user-group" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 font-mono">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.statistics.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Save Statistic</button>
            </div>
        </form>
    </div>
</div>
@endsection
