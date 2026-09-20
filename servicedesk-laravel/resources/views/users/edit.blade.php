@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('users.index') }}" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-2xl font-bold border border-red-200">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            @php
                $roleClasses = [
                    'admin' => 'bg-purple-50 text-purple-700 border-purple-100',
                    'employee' => 'bg-blue-50 text-blue-700 border-blue-100',
                    'user' => 'bg-gray-50 text-gray-700 border-gray-100',
                ];
                $roleClass = $roleClasses[$user->role] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                $statusClass = $user->status === 'Active' ? 'text-green-700 bg-green-50 border-green-100' : 'text-red-700 bg-red-50 border-red-100';
                $dotClass = $user->status === 'Active' ? 'bg-green-500' : 'bg-red-500';
            @endphp
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border capitalize {{ $roleClass }}">
                {{ ucfirst($user->role) }}
            </span>
            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border {{ $statusClass }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                {{ $user->status }}
            </span>
        </div>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @csrf
        @method('PUT')
        
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('name') border-red-300 bg-red-50 @enderror" placeholder="e.g. John Doe" />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('email') border-red-300 bg-red-50 @enderror" placeholder="john@company.com" />
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                        <input name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all" placeholder="+62 812-3456-7890" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">User ID</label>
                        <input type="text" value="{{ $user->id }}" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-sm text-gray-500 cursor-not-allowed" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Account Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                        <select name="role" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all capitalize @error('role') border-red-300 bg-red-50 @enderror">
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Account Status <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all @error('status') border-red-300 bg-red-50 @enderror">
                            <option value="Active" {{ old('status', $user->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $user->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm sticky top-24">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Actions</h2>
                <div class="space-y-3">
                    <button type="submit" class="w-full py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Save Changes
                    </button>
                    
                    <button type="button" x-data @click="$dispatch('open-delete-modal', { id: {{ $user->id }}, name: '{{ $user->name }}' })" class="w-full py-3 bg-white border-2 border-red-200 text-red-600 rounded-2xl text-sm font-semibold hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div x-data="{ open: false, userId: null, userName: '' }" 
     @open-delete-modal.window="open = true; userId = $event.detail.id; userName = $event.detail.name"
     x-show="open" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity" 
     style="display: none;">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl transform transition-all scale-100" @click.outside="open = false">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Delete User?</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to delete <span class="font-semibold text-gray-800" x-text="userName"></span>? This action cannot be undone.</p>
        </div>
        <div class="flex gap-3">
            <button @click="open = false" class="flex-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
            <form :action="'/users/' + userId" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-colors shadow-lg shadow-red-600/25">Yes, Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection