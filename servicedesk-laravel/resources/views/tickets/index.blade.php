@extends('layouts.app')

@section('title', auth()->user()->role === 'admin' ? 'All Tickets' : 'My Tickets')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            {{ auth()->user()->role === 'admin' ? 'All Tickets' : 'My Tickets' }}
        </h1>
        <p class="text-gray-400 text-sm mt-1">Track your submitted and assigned service requests</p>
    </div>
    
    <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-2xl font-semibold text-sm hover:bg-red-700 transition-all shadow-lg shadow-red-600/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create New Ticket
    </a>
</div>

<div class="bg-white rounded-3xl p-4 mb-6 border border-gray-100 shadow-sm">
    <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 xl:grid-cols-12 gap-3">
        <div class="relative xl:col-span-4">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search your tickets..." class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300" />
        </div>
        
        <div class="xl:col-span-3 flex items-center gap-1">
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="flex-1 px-2 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 text-gray-600" />
            <span class="text-gray-400 text-xs">-</span>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="flex-1 px-2 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 text-gray-600" />
        </div>
        
        <div class="xl:col-span-2">
            <select name="status" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                <option value="">All Status</option>
                <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        
        <div class="xl:col-span-2">
            <select name="category" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="xl:col-span-1">
            <select name="priority" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                <option value="">All</option>
                <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-50">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Ticket ID</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Title</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Category</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Priority</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Assigned To</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer" onclick="window.location='{{ route('tickets.show', $ticket->id) }}'">
                    <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-400">{{ $ticket->ticket_id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ Str::limit($ticket->title, 40) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center text-xs font-medium text-gray-600 bg-gray-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 mr-1.5"></span>
                            {{ $ticket->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <x-priority-badge :priority="$ticket->priority" />
                    </td>
                    <td class="px-6 py-4">
                        <x-status-badge :status="$ticket->status" />
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->assignee->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ $ticket->created_at->diffForHumans() }}</td>
                    
                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="p-2 rounded-xl hover:bg-blue-50 text-blue-500 transition-colors" title="View Ticket">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            
                            @if(auth()->user()->role === 'admin')
                            <button 
                                x-data 
                                @click="$dispatch('open-delete-modal', { id: {{ $ticket->id }}, ticketNumber: '{{ $ticket->ticket_id }}' })"
                                class="p-2 rounded-xl hover:bg-red-50 text-red-500 transition-colors" 
                                title="Delete Ticket"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 font-medium">No tickets found</p>
                        <p class="text-sm text-gray-400 mt-1">You haven't created or been assigned any tickets yet.</p>
                        <a href="{{ route('tickets.create') }}" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">
                            Create Your First Ticket
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} tickets
        </p>
        {{ $tickets->links() }}
    </div>
    @endif
</div>

<div x-data="{ open: false, ticketId: null, ticketNumber: '' }" 
     @open-delete-modal.window="open = true; ticketId = $event.detail.id; ticketNumber = $event.detail.ticketNumber"
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
            <h3 class="text-xl font-bold text-gray-800">Delete Ticket?</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to delete ticket <span class="font-semibold text-gray-800" x-text="ticketNumber"></span>? This action cannot be undone.</p>
        </div>
        <div class="flex gap-3">
            <button @click="open = false" class="flex-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
            <form :action="'/tickets/' + ticketId" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-colors shadow-lg shadow-red-600/25">Yes, Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection