@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('categories.index') }}" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Category</h1>
            <p class="text-sm text-gray-500 mt-1">Update category information and details</p>
        </div>
    </div>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-6">
        @csrf
        @method('PUT')
        
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Category Details</h2>
        </div>
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                <input name="name" type="text" value="{{ old('name', $category->name) }}" placeholder="e.g. Network Issues" 
                       class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('name') border-red-300 bg-red-50 @enderror" />
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" placeholder="Briefly describe what this category is used for..." 
                          class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all resize-none @error('description') border-red-300 bg-red-50 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Total Tickets</label>
                <input type="text" value="{{ $category->tickets_count ?? 0 }}" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-sm text-gray-500 cursor-not-allowed" />
                <p class="text-xs text-gray-400 mt-1">This number updates automatically based on assigned tickets.</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('categories.index') }}" class="px-6 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection