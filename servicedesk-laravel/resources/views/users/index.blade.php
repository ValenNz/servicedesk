@extends('layouts.app')

@section('title', 'All Users')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">All Users</h1>
        <p class="text-gray-400 text-sm mt-1">Manage system users and their roles</p>
    </div>
    
    <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-2xl font-semibold text-sm hover:bg-red-700 transition-all shadow-lg shadow-red-600/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create New User
    </a>
</div>

<div class="bg-white rounded-3xl p-4 mb-6 border border-gray-100 shadow-sm">
    <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 xl:grid-cols-12 gap-3">
        <div class="relative xl:col-span-4">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300" />
        </div>
        
        <div class="xl:col-span-3">
            <select name="role" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option> 
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
        
        <div class="xl:col-span-3">
            <select name="status" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                <option value="">All Status</option>
                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        
        <div class="xl:col-span-2 flex items-center justify-end gap-2">
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('users.index') }}" class="p-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-colors" title="Clear Filters">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
            <button type="submit" class="px-4 py-2.5 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">Search</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-50">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-50 text-red-600 rounded-full flex items-center justify-center font-semibold text-sm border border-red-100">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $roleClasses = [
                                'admin' => 'bg-purple-50 text-purple-700 border-purple-100',
                                'employee' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'user' => 'bg-gray-50 text-gray-700 border-gray-100',
                            ];
                            $class = $roleClasses[$user->role] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border capitalize {{ $class }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusClass = $user->status === 'Active' ? 'text-green-700 bg-green-50 border-green-100' : 'text-red-700 bg-red-50 border-red-100';
                            $dotClass = $user->status === 'Active' ? 'bg-green-500' : 'bg-red-500';
                        @endphp
                        <span class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-full border {{ $statusClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                            {{ $user->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('users.edit', $user->id) }}" class="p-2 rounded-xl hover:bg-blue-50 text-blue-500 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <button x-data @click="$dispatch('open-delete-modal', { id: {{ $user->id }}, name: '{{ $user->name }}' })" class="p-2 rounded-xl hover:bg-red-50 text-red-500 transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="text-gray-500 font-medium">No users found</p>
                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </p>
        {{ $users->links() }}
    </div>
    @endif
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