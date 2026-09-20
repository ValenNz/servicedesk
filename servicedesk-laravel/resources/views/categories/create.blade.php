@extends('layouts.app')

@section('title', 'Create New Category')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ name: '', description: '' }">
    <div class="flex items-center gap-4">
        <a href="{{ route('categories.index') }}" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New Category</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new category to organize your tickets</p>
        </div>
    </div>

    <form action="{{ route('categories.store') }}" method="POST" class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
            <input x-model="name" name="name" type="text" placeholder="e.g., Network, Software, Hardware" 
                   class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('name') border-red-300 bg-red-50 @enderror" />
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-400 mt-1">This name will appear in ticket filters and dropdowns</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
            <textarea x-model="description" name="description" rows="4" placeholder="Briefly describe what types of issues belong to this category..." 
                      class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all resize-none @error('description') border-red-300 bg-red-50 @enderror"></textarea>
            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div x-show="name || description" class="bg-gray-50 rounded-2xl p-6 border border-gray-100" style="display: none;">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Live Preview
            </h3>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-1" x-text="name || 'Category Name'"></h4>
                <p class="text-sm text-gray-500 mb-4" x-text="description || 'Category description will appear here...'"></p>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Tickets</span>
                    <span class="text-sm font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full">0</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('categories.index') }}" class="px-6 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Create Category
            </button>
        </div>
    </form>
</div>
@endsection