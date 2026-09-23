<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = ContactMessage::latest();
        if ($status) {
            $query->where('status', $status);
        }
        $messages = $query->paginate(20);
        return view('admin.messages.index', compact('messages', 'status'));
    }

    public function show(ContactMessage $message)
    {
        if ($message->status === 'unread') {
            $message->status = 'read';
            $message->save();
        }
        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, ContactMessage $message)
    {
        $request->validate(['admin_reply' => 'required|string']);

        $message->admin_reply = $request->admin_reply;
        $message->status = 'replied';
        $message->save();

        ActivityLog::log('update', 'contact', "Replied to message from: {$message->name}");

        return back()->with('success', 'Reply saved.');
    }

    public function destroy(ContactMessage $message)
    {
        $name = $message->name;
        $message->delete();
        ActivityLog::log('delete', 'contact', "Deleted message from: {$name}");
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
