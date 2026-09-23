@extends('layouts.admin')

@section('title', 'Write Article')
@section('header', 'Compose Article')

@section('content')
<div class="max-w-4xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Article Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Category</label>
                    <select name="category_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Featured Banner Image</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Publishing Status *</label>
                    <select name="status" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="review">Needs Review</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Short Excerpt / Lead Paragraph</label>
                <textarea name="excerpt" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('excerpt') }}</textarea>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Full Article Body (HTML / Markdown supported) *</label>
                <textarea name="content" rows="8" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs font-mono text-white focus:outline-none focus:border-blue-500">{{ old('content') }}</textarea>
            </div>

            @if(count($tags) > 0)
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Associate Tags</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $t)
                            <label class="inline-flex items-center gap-1.5 text-xs text-slate-300 bg-slate-950 border border-slate-800 px-3 py-1.5 rounded-lg cursor-pointer hover:border-slate-700">
                                <input type="checkbox" name="tags[]" value="{{ $t->id }}" class="rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-0">
                                {{ $t->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="pt-4 border-t border-slate-800">
                <div class="text-xs font-bold uppercase text-slate-400 mb-4">Technical SEO Settings</div>
                <div class="space-y-4">
                    <input type="text" name="seo_title" placeholder="SEO Title tag" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                    <textarea name="seo_description" rows="2" placeholder="Meta description..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-blue-500"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.blog.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Publish Article</button>
            </div>
        </form>
    </div>
</div>
@endsection
