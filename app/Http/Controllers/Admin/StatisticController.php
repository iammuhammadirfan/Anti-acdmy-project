<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Statistic;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        $statistics = Statistic::orderBy('display_order', 'asc')->get();
        return view('admin.statistics.index', compact('statistics'));
    }

    public function create()
    {
        return view('admin.statistics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'metric_key' => 'required|string|max:100|unique:statistics,metric_key',
            'label' => 'required|string|max:191',
            'value' => 'required|string|max:50',
            'suffix' => 'nullable|string|max:10',
            'icon' => 'nullable|string|max:50',
            'display_order' => 'integer',
        ]);

        $stat = Statistic::create([
            'metric_key' => $request->metric_key,
            'label' => $request->label,
            'value' => $request->value,
            'suffix' => $request->suffix,
            'icon' => $request->icon,
            'display_order' => (int) $request->display_order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log('create', 'students', "Created statistic: {$stat->label}");

        return redirect()->route('admin.statistics.index')->with('success', 'Statistic created.');
    }

    public function edit(Statistic $statistic)
    {
        return view('admin.statistics.edit', compact('statistic'));
    }

    public function update(Request $request, Statistic $statistic)
    {
        $request->validate([
            'label' => 'required|string|max:191',
            'value' => 'required|string|max:50',
            'suffix' => 'nullable|string|max:10',
            'icon' => 'nullable|string|max:50',
            'display_order' => 'integer',
        ]);

        $statistic->label = $request->label;
        $statistic->value = $request->value;
        $statistic->suffix = $request->suffix;
        $statistic->icon = $request->icon;
        $statistic->display_order = (int) $request->display_order;
        $statistic->is_active = $request->boolean('is_active', true);
        $statistic->save();

        ActivityLog::log('update', 'students', "Updated statistic: {$statistic->label}");

        return redirect()->route('admin.statistics.index')->with('success', 'Statistic updated.');
    }

    public function toggle(Statistic $statistic)
    {
        $statistic->is_active = !$statistic->is_active;
        $statistic->save();

        $state = $statistic->is_active ? 'active' : 'disabled';
        ActivityLog::log('update', 'students', "Toggled status for statistic {$statistic->label} to {$state}.");

        return back()->with('success', "Statistic is now {$state}.");
    }

    public function destroy(Statistic $statistic)
    {
        $label = $statistic->label;
        $statistic->delete();
        ActivityLog::log('delete', 'students', "Deleted statistic: {$label}");
        return redirect()->route('admin.statistics.index')->with('success', 'Statistic deleted.');
    }
}
