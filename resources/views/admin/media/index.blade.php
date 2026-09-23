@extends('layouts.admin')

@section('title', 'Media Library')
@section('page_title', 'Centralized Media Library')

@section('content')
<div class="space-y-6" x-data="{ uploadModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Digital Assets &amp; Media</h2>
            <p class="text-xs text-slate-500">Manage uploaded images, campus galleries, documents, and student certificates.</p>
        </div>
        <button @click="uploadModal = true" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">
            <i data-lucide="upload-cloud" class="w-4 h-4"></i>
            <span>Upload New Files</span>
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs font-semibold">
            <a href="{{ route('admin.media.index') }}" class="px-3 py-1.5 rounded-lg {{ !$type ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All Files</a>
            <a href="{{ route('admin.media.index', ['type' => 'image']) }}" class="px-3 py-1.5 rounded-lg {{ $type === 'image' ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Images</a>
            <a href="{{ route('admin.media.index', ['type' => 'video']) }}" class="px-3 py-1.5 rounded-lg {{ $type === 'video' ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Videos</a>
            <a href="{{ route('admin.media.index', ['type' => 'pdf']) }}" class="px-3 py-1.5 rounded-lg {{ $type === 'pdf' ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">PDF Documents</a>
        </div>

        <form action="{{ route('admin.media.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search files..."
                   class="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
            <i data-lucide="search" class="w-3.5 h-3.5 absolute left-2.5 top-2 text-slate-400"></i>
        </form>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($mediaItems as $item)
            <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between group">
                <div class="h-32 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                    @if($item->file_type === 'image')
                        <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    @elseif($item->file_type === 'video')
                        <i data-lucide="video" class="w-10 h-10 text-slate-400"></i>
                    @else
                        <i data-lucide="file-text" class="w-10 h-10 text-slate-400"></i>
                    @endif
                </div>

                <div class="p-2.5 text-xs">
                    <span class="font-bold text-slate-900 truncate block" title="{{ $item->title }}">{{ $item->title }}</span>
                    <span class="text-[10px] text-slate-400 block">{{ number_format($item->file_size / 1024, 1) }} KB</span>
                </div>

                <div class="p-2 border-t border-slate-100 flex items-center justify-between text-xs bg-slate-50">
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $item->url }}'); alert('URL copied to clipboard!');" 
                            class="text-brand-600 hover:text-brand-800 p-1" title="Copy URL">
                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                    </button>
                    <form action="{{ route('admin.media.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this media file?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Delete">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                No media files found. Click "Upload New Files" to add images or documents.
            </div>
        @endforelse
    </div>

    @if($mediaItems->hasPages())
        <div class="pt-4">
            {{ $mediaItems->links() }}
        </div>
    @endif

    <!-- Upload Modal -->
    <div x-show="uploadModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-base">Upload Files to Library</h3>
                <button @click="uploadModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center bg-slate-50 hover:bg-slate-100/50 transition">
                    <i data-lucide="upload-cloud" class="w-10 h-10 text-brand-600 mx-auto mb-2"></i>
                    <p class="text-sm font-semibold text-slate-700">Choose images, PDFs, or videos</p>
                    <p class="text-xs text-slate-400 mt-1">Select one or multiple files (max 20MB per file)</p>
                    <input type="file" name="files[]" multiple required class="mt-4 text-xs mx-auto">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="uploadModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold">Cancel</button>
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-5 py-2 rounded-xl shadow">Upload to Library</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
