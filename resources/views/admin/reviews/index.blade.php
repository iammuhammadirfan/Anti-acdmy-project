@extends('layouts.admin')

@section('title', 'Student Reviews & Testimonials')
@section('page_title', 'Student Reviews & Feedback Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Student Reviews &amp; Testimonials</h3>
            <p class="text-xs text-slate-500">Review feedback submitted by students from the contact page or add authentic testimonials to show on the website home page.</p>
        </div>
        <a href="{{ route('admin.reviews.create') }}" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all flex items-center gap-1.5 self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Testimonial / Review</span>
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3 text-xs font-semibold">
        <a href="{{ route('admin.reviews.index') }}" class="px-3.5 py-1.5 rounded-lg transition {{ empty($status) ? 'bg-brand-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
            All Reviews ({{ $reviews->total() }})
        </a>
        <a href="{{ route('admin.reviews.index', ['filter' => 'approved']) }}" class="px-3.5 py-1.5 rounded-lg transition {{ $status === 'approved' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
            Published / Approved ({{ $approvedCount }})
        </a>
        <a href="{{ route('admin.reviews.index', ['filter' => 'pending']) }}" class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
            <span>Pending Moderation</span>
            @if($pendingCount > 0)
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-200 text-amber-950 font-extrabold">{{ $pendingCount }}</span>
            @endif
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Course / Program</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Review Message</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviews as $rev)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-xs shrink-0 overflow-hidden">
                                        @if(!empty($rev->avatar))
                                            <img src="{{ asset('storage/' . $rev->avatar) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($rev->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-900 font-bold">{{ $rev->name }}</div>
                                        @if(!empty($rev->email))
                                            <div class="text-[11px] text-slate-400 font-normal">{{ $rev->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-md border border-slate-200 text-slate-700 font-semibold text-[11px]">
                                    {{ $rev->course ?: 'IETS / IELTS Course' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-0.5 text-amber-500 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rev->rating)
                                            ★
                                        @else
                                            <span class="text-slate-300">★</span>
                                        @endif
                                    @endfor
                                    <span class="text-[11px] text-slate-500 ml-1 font-mono">({{ $rev->rating }}/5)</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-sm">
                                <p class="text-slate-700 line-clamp-2 text-xs italic">"{{ $rev->review }}"</p>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $rev->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.reviews.toggle', $rev) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold flex items-center gap-1.5 transition {{ $rev->is_approved ? 'bg-emerald-100 text-emerald-800 border border-emerald-200 hover:bg-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200 hover:bg-amber-200' }}"
                                            title="Click to {{ $rev->is_approved ? 'Unpublish' : 'Approve & Publish on Website' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $rev->is_approved ? 'bg-emerald-600' : 'bg-amber-600' }}"></span>
                                        <span>{{ $rev->is_approved ? 'Approved / Live' : 'Pending Approval' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap">
                                <a href="{{ route('admin.reviews.edit', $rev) }}" class="text-brand-600 hover:text-brand-800 font-bold">Edit</a>
                                <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this review?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                <i data-lucide="message-square-off" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                <p>No student reviews found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
