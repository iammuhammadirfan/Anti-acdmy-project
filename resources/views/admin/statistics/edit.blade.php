@extends('layouts.admin')

@section('title', 'Edit Statistic')
@section('header', 'Edit Metric: ' . $statistic->label)

@section('content')
<div class="max-w-2xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.statistics.update', $statistic) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Metric Key (Identifier)</label>
                    <input type="text" disabled value="{{ $statistic->metric_key }}" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Public Label *</label>
                    <input type="text" name="label" value="{{ old('label', $statistic->label) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Numeric Value *</label>
                    <input type="text" name="value" value="{{ old('value', $statistic->value) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 font-bold">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Suffix Symbol</label>
                    <input type="text" name="suffix" value="{{ old('suffix', $statistic->suffix) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 font-bold">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $statistic->display_order) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Icon Identifier</label>
                <input type="text" name="icon" value="{{ old('icon', $statistic->icon) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 font-mono">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.statistics.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Update Statistic</button>
            </div>
        </form>
    </div>
</div>
@endsection
