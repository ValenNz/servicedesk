@extends('layouts.app')

@section('title', 'Create New Ticket')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('tickets.index') }}" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New Ticket</h1>
            <p class="text-sm text-gray-500 mt-1">Tell us about your issue and we'll help you resolve it</p>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="text-sm text-blue-800">
            <p class="font-semibold mb-1">How it works:</p>
            <ul class="list-disc list-inside text-xs text-blue-700 space-y-0.5">
                <li>Fill in the details about your issue below</li>
                <li>Our team will review and assign it to the right agent</li>
                <li>You'll receive updates via comments on this ticket</li>
            </ul>
        </div>
    </div>

    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Ticket Title <span class="text-red-500">*</span></label>
            <input name="title" type="text" value="{{ old('title') }}" placeholder="e.g., Cannot login to my email account" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('title') border-red-300 bg-red-50 @enderror" />
            @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-400 mt-1">Brief summary of your issue (5-100 characters)</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                <select name="category_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('category_id') border-red-300 bg-red-50 @enderror">
                    <option value="">-- Select a category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Priority Level <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['Low', 'Medium', 'High'] as $priority)
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="{{ $priority }}" class="peer sr-only" {{ old('priority') == $priority ? 'checked' : '' }}>
                            <div class="px-3 py-2.5 rounded-xl text-xs font-semibold border-2 transition-all text-center peer-checked:border-current bg-white border-gray-200 text-gray-600 hover:border-gray-300
                                {{ $priority == 'Low' ? 'peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700' : '' }}
                                {{ $priority == 'Medium' ? 'peer-checked:bg-yellow-50 peer-checked:border-yellow-500 peer-checked:text-yellow-700' : '' }}
                                {{ $priority == 'High' ? 'peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700' : '' }}">
                                {{ $priority }}
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('priority') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
            <textarea name="description" rows="5" placeholder="Please describe your issue in detail..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all resize-none @error('description') border-red-300 bg-red-50 @enderror">{{ old('description') }}</textarea>
            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Attachment <span class="text-xs font-normal text-gray-400">(Optional)</span></label>
            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300" />
            @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG, DOCX (Max 5MB)</p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('tickets.index') }}" class="px-6 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                Submit Ticket
            </button>
        </div>
    </form>
</div>
@endsection