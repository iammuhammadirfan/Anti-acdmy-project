@extends('layouts.admin')

@section('title', 'Edit Student Review')
@section('page_title', 'Edit Testimonial / Review')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('admin.reviews.update', $review) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Student Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $review->name) }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Course / Program Taken</label>
                    <input type="text" name="course" value="{{ old('course', $review->course) }}" placeholder="e.g. IELTS Academic (Band 8.0)"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Star Rating (1 to 5) *</label>
                    <select name="rating" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-brand-500">
                        <option value="5" {{ old('rating', $review->rating) == 5 ? 'selected' : '' }}>5 Stars (Excellent ★★★★★)</option>
                        <option value="4" {{ old('rating', $review->rating) == 4 ? 'selected' : '' }}>4 Stars (Very Good ★★★★☆)</option>
                        <option value="3" {{ old('rating', $review->rating) == 3 ? 'selected' : '' }}>3 Stars (Good ★★★☆☆)</option>
                        <option value="2" {{ old('rating', $review->rating) == 2 ? 'selected' : '' }}>2 Stars (Fair ★★☆☆☆)</option>
                        <option value="1" {{ old('rating', $review->rating) == 1 ? 'selected' : '' }}>1 Star (Poor ★☆☆☆☆)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $review->display_order) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Email Address (Optional)</label>
                    <input type="email" name="email" value="{{ old('email', $review->email) }}" placeholder="student@example.com"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Phone / WhatsApp (Optional)</label>
                    <input type="text" name="phone" value="{{ old('phone', $review->phone) }}" placeholder="+92 300 1234567"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Student Avatar / Photo (Optional)</label>
                @if($review->avatar)
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ asset('storage/' . $review->avatar) }}" class="w-12 h-12 rounded-full object-cover border border-slate-200">
                        <span class="text-xs text-slate-500">Current photo</span>
                    </div>
                @endif
                <input type="file" name="avatar" accept="image/*"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-700 focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Review / Testimonial Message *</label>
                <textarea name="review" rows="4" required placeholder="Write the student feedback or testimonial quote here..."
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">{{ old('review', $review->review) }}</textarea>
            </div>

            <div class="flex items-center gap-6 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $review->is_approved) ? 'checked' : '' }}
                           class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-xs font-semibold text-slate-800">Publish on Website</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $review->is_featured) ? 'checked' : '' }}
                           class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-xs font-semibold text-slate-800">Feature on Top</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.reviews.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition">
                    Update Testimonial
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
