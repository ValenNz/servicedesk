@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        {{ $isAdmin ? 'Admin Dashboard' : 'Employee Dashboard' }}
    </h1>
    <p class="text-gray-400 text-sm mt-1">
        {{ $isAdmin 
            ? 'Overview of all service desk activities' 
            : 'Welcome back, ' . auth()->user()->name . '! Here are your assigned tickets.' 
        }}
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">
                    {{ $isAdmin ? 'Total Tickets' : 'My Tickets' }}
                </p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Open Tickets</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['open'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">In Progress</p>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['in_progress'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Resolved</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['resolved'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800">
            {{ $isAdmin ? 'Recent Tickets' : 'My Recent Tickets' }}
        </h2>
        <a href="{{ route('tickets.index') }}" class="text-sm text-red-600 hover:text-red-700 font-semibold">
            View All
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Ticket ID</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Title</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Priority</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Assigned To</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($recentTickets as $ticket)
                <tr class="hover:bg-gray-50 cursor-pointer transition-colors" onclick="window.location='{{ route('tickets.show', $ticket->id) }}'">
                    <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-600">{{ $ticket->ticket_id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ Str::limit($ticket->title, 40) }}</td>
                    
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'Open' => 'bg-blue-100 text-blue-700',
                                'In Progress' => 'bg-yellow-100 text-yellow-700',
                                'Resolved' => 'bg-green-100 text-green-700',
                                'Closed' => 'bg-gray-100 text-gray-700',
                            ];
                            $statusClass = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ $ticket->status }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        @php
                            $priorityColors = [
                                'High' => 'bg-red-100 text-red-700',
                                'Medium' => 'bg-yellow-100 text-yellow-700',
                                'Low' => 'bg-green-100 text-green-700',
                            ];
                            $priorityClass = $priorityColors[$ticket->priority] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityClass }}">
                            {{ $ticket->priority }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $ticket->assignee ? $ticket->assignee->name : 'Unassigned' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 font-medium">
                            {{ $isAdmin ? 'No tickets yet' : 'No tickets assigned to you' }}
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            {{ $isAdmin ? 'Tickets will appear here once created' : 'Contact your admin to get assigned' }}
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection