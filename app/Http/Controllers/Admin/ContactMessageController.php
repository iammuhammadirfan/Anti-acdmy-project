<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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

        // Email the reply to the inquirer
        $this->notificationService->sendContactReplyNotification($message);

        return back()->with('success', 'Reply saved and email dispatched to the sender.');
    }

    public function destroy(ContactMessage $message)
    {
        $name = $message->name;
        $message->delete();
        ActivityLog::log('delete', 'contact', "Deleted message from: {$name}");
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
