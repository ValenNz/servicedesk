@extends('layouts.app')

@section('title', 'My Activity Log')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">My Activity Log</h1>
        <p class="text-gray-400 text-sm mt-1">Track your ticket updates, comments, and actions</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('activity.export') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-2xl font-semibold text-sm hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export
        </a>
    </div>
</div>

<div class="bg-white rounded-3xl p-4 mb-6 border border-gray-100 shadow-sm">
    <form method="GET" action="{{ route('activity.index') }}" class="grid grid-cols-1 xl:grid-cols-12 gap-3">
        <div class="relative xl:col-span-4">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search your activities..." class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300" />
        </div>
        
        <div class="xl:col-span-3">
            <select name="type" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                <option value="">All Types</option>
                <option value="ticket_created" {{ request('type') == 'ticket_created' ? 'selected' : '' }}>Ticket Created</option>
                <option value="ticket_assigned" {{ request('type') == 'ticket_assigned' ? 'selected' : '' }}>Ticket Assigned</option>
                <option value="comment_added" {{ request('type') == 'comment_added' ? 'selected' : '' }}>Comment Added</option>
                <option value="status_changed" {{ request('type') == 'status_changed' ? 'selected' : '' }}>Status Changed</option>
                <option value="user_login" {{ request('type') == 'user_login' ? 'selected' : '' }}>Login</option>
                <option value="user_logout" {{ request('type') == 'user_logout' ? 'selected' : '' }}>Logout</option>
            </select>
        </div>
        
        <div class="xl:col-span-3">
            <select name="date" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                <option value="all" {{ request('date') == 'all' || !request('date') ? 'selected' : '' }}>All Time</option>
                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
            </select>
        </div>

        <div class="xl:col-span-2 flex items-center justify-end">
            @if(request()->hasAny(['search', 'type', 'date']))
                <a href="{{ route('activity.index') }}" class="p-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-colors" title="Clear Filters">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="divide-y divide-gray-100">
        @forelse($activities as $activity)
        <div class="p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start gap-4">
                @php
                    $icons = [
                        'ticket_created' => ['bg' => 'bg-blue-50', 'color' => 'text-blue-600', 'path' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                        'ticket_updated' => ['bg' => 'bg-yellow-50', 'color' => 'text-yellow-600', 'path' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                        'ticket_deleted' => ['bg' => 'bg-red-50', 'color' => 'text-red-600', 'path' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
                        'ticket_assigned' => ['bg' => 'bg-green-50', 'color' => 'text-green-600', 'path' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        'comment_added' => ['bg' => 'bg-purple-50', 'color' => 'text-purple-600', 'path' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                        'status_changed' => ['bg' => 'bg-orange-50', 'color' => 'text-orange-600', 'path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                        'user_login' => ['bg' => 'bg-teal-50', 'color' => 'text-teal-600', 'path' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1'],
                        'user_logout' => ['bg' => 'bg-gray-50', 'color' => 'text-gray-600', 'path' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'],
                    ];
                    $icon = $icons[$activity->action] ?? $icons['ticket_created'];
                @endphp
                
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $icon['bg'] }}">
                    <svg class="w-5 h-5 {{ $icon['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}" />
                    </svg>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4 mb-1">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $activity->description }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                <span>by {{ $activity->user_name }}</span>
                                <span>•</span>
                                <span>{{ \Carbon\Carbon::parse($activity->timestamp)->diffForHumans() }}</span>
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap
                            @if($activity->action == 'ticket_created') bg-blue-50 text-blue-700
                            @elseif($activity->action == 'ticket_updated') bg-yellow-50 text-yellow-700
                            @elseif($activity->action == 'ticket_deleted') bg-red-50 text-red-700
                            @elseif($activity->action == 'ticket_assigned') bg-green-50 text-green-700
                            @elseif($activity->action == 'comment_added') bg-purple-50 text-purple-700
                            @elseif($activity->action == 'status_changed') bg-orange-50 text-orange-700
                            @elseif($activity->action == 'user_login') bg-teal-50 text-teal-700
                            @else bg-gray-50 text-gray-700 @endif">
                            {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                        </span>
                    </div>
                    
                    @if($activity->metadata)
                    <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                        @if(isset($activity->metadata['ticket_id']))
                        <span class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            Ticket #{{ $activity->metadata['ticket_id'] }}
                        </span>
                        @endif
                        @if(isset($activity->metadata['old_status']) && isset($activity->metadata['new_status']))
                        <span class="flex items-center gap-1">
                            <span class="line-through opacity-60">{{ $activity->metadata['old_status'] }}</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            <span class="font-semibold text-gray-700">{{ $activity->metadata['new_status'] }}</span>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-gray-500 font-medium">No activities found</p>
            <p class="text-sm text-gray-400 mt-1">Try adjusting your filters</p>
        </div>
        @endforelse
    </div>
    
    @if($activities->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} activities
        </p>
        {{ $activities->links() }}
    </div>
    @endif
</div>
@endsection