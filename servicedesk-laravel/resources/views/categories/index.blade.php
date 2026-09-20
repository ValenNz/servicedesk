@extends('layouts.app')

@section('title', 'Ticket Categories')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6" x-data="{ deleteModalOpen: false, categoryToDelete: null, categoryId: null }">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Ticket Categories</h1>
        <p class="text-gray-400 text-sm mt-1">Manage and organize ticket categories</p>
    </div>
    
    <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-2xl font-semibold text-sm hover:bg-red-700 transition-all shadow-lg shadow-red-600/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Category
    </a>

    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display: none;"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl" @click.outside="deleteModalOpen = false">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Delete Category?</h3>
                <p class="text-sm text-gray-500 mt-2">Are you sure you want to delete this category? This action cannot be undone.</p>
            </div>
            <div class="flex gap-3">
                <button @click="deleteModalOpen = false" class="flex-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
                <form :action="'/categories/' + categoryId" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-colors shadow-lg shadow-red-600/25">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl p-4 mb-6 border border-gray-100 shadow-sm">
    <form method="GET" action="{{ route('categories.index') }}" class="grid grid-cols-1 xl:grid-cols-12 gap-3">
        <div class="relative xl:col-span-10">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300" />
        </div>
        <div class="xl:col-span-2 flex items-center gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">Search</button>
            @if(request('search'))
                <a href="{{ route('categories.index') }}" class="p-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-colors" title="Clear Filters">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            @endif
        </div>
    </form>
</div>

@if($categories->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($categories as $category)
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <div class="flex gap-1">
                    <a href="{{ route('categories.edit', $category->id) }}" class="p-2 rounded-xl hover:bg-gray-100 text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    </a>
                    <button @click="deleteModalOpen = true; categoryId = {{ $category->id }}" class="p-2 rounded-xl hover:bg-gray-100 text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
            
            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $category->name }}</h3>
            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $category->description }}</p>
            
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Tickets</span>
                <span class="text-sm font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full">{{ $category->tickets_count ?? 0 }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex justify-center">
        {{ $categories->appends(request()->query())->links() }}
    </div>
@else
    <div class="text-center py-12">
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm inline-block">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            <p class="text-gray-500 font-medium">No categories found</p>
            <p class="text-sm text-gray-400 mt-1">Try adjusting your search or create a new one.</p>
        </div>
    </div>
@endif
@endsection

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>