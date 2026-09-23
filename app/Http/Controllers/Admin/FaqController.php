<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('display_order', 'asc')->paginate(20);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:191',
            'answer' => 'required|string',
            'category' => 'required|string|max:100',
            'display_order' => 'integer',
        ]);

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'display_order' => (int) $request->display_order,
            'status' => $request->boolean('status', true),
        ]);

        ActivityLog::log('create', 'faq', "Created FAQ: {$faq->question}");

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:191',
            'answer' => 'required|string',
            'category' => 'required|string|max:100',
            'display_order' => 'integer',
        ]);

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->category = $request->category;
        $faq->display_order = (int) $request->display_order;
        $faq->status = $request->boolean('status', true);
        $faq->save();

        ActivityLog::log('update', 'faq', "Updated FAQ: {$faq->question}");

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function toggle(Faq $faq)
    {
        $faq->status = !$faq->status;
        $faq->save();

        $state = $faq->status ? 'active' : 'hidden';
        ActivityLog::log('update', 'faq', "Toggled status for FAQ {$faq->question} to {$state}.");

        return back()->with('success', "FAQ status changed to {$state}.");
    }

    public function destroy(Faq $faq)
    {
        $q = $faq->question;
        $faq->delete();
        ActivityLog::log('delete', 'faq', "Deleted FAQ: {$q}");
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }
}
