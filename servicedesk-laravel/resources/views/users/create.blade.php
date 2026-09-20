@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New User</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new user to the system</p>
        </div>
    </div>

    <form action="{{ route('users.store') }}" method="POST" class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
            <input name="name" type="text" value="{{ old('name') }}" placeholder="e.g., John Doe" class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('name') border-red-300 bg-red-50 @enderror" />
            @error('name') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> {{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
            <input name="email" type="email" value="{{ old('email') }}" placeholder="e.g., employee@servicedesk.com" class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('email') border-red-300 bg-red-50 @enderror" />
            @error('email') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> {{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                <select name="role" class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all capitalize @error('role') border-red-300 bg-red-50 @enderror">
                    <option value="">Select a role...</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option> 
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> {{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="Active" class="peer sr-only" {{ old('status', 'Active') == 'Active' ? 'checked' : '' }}>
                        <div class="px-3 py-2.5 rounded-xl text-xs font-semibold border-2 transition-all text-center peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 bg-white border-gray-200 text-gray-600 hover:border-gray-300">
                            Active
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="Inactive" class="peer sr-only" {{ old('status') == 'Inactive' ? 'checked' : '' }}>
                        <div class="px-3 py-2.5 rounded-xl text-xs font-semibold border-2 transition-all text-center peer-checked:bg-gray-50 peer-checked:border-gray-500 peer-checked:text-gray-700 bg-white border-gray-200 text-gray-600 hover:border-gray-300">
                            Inactive
                        </div>
                    </label>
                </div>
                @error('status') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> {{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('users.index') }}" class="px-6 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create User
            </button>
        </div>
    </form>
</div>
@endsection