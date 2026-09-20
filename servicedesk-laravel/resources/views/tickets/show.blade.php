@extends('layouts.app')

@section('title', 'Ticket ' . $ticket->ticket_id)

@section('content')
<div class="space-y-6">
    <div class="flex items-start gap-4">
        <a href="{{ route('tickets.index') }}" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200 mt-1">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <h1 class="text-2xl font-bold text-gray-800">{{ $ticket->ticket_id }}</h1>
                <x-status-badge :status="$ticket->status" />
                <x-priority-badge :priority="$ticket->priority" />
            </div>
            <p class="text-sm text-gray-500">{{ $ticket->title }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-gray-800 mb-6">Ticket Progress</h3>
        @php
            $steps = ['Open' => 1, 'In Progress' => 2, 'Resolved' => 3, 'Closed' => 4];
            $currentStep = $steps[$ticket->status] ?? 1;
            $progressPercentage = (($currentStep - 1) / 3) * 100;
        @endphp
        <div class="relative mb-8">
            <div class="absolute top-1/2 left-0 w-full h-1.5 bg-gray-100 -translate-y-1/2 rounded-full"></div>
            <div class="absolute top-1/2 left-0 h-1.5 bg-blue-500 -translate-y-1/2 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
            
            <div class="relative flex justify-between">
                @foreach(['Open' => 'Submitted', 'In Progress' => 'In Progress', 'Resolved' => 'Resolved', 'Closed' => 'Closed'] as $step => $label)
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-4 transition-all duration-300 z-10
                            {{ $currentStep >= $steps[$step] ? 'bg-blue-500 border-blue-100 text-white shadow-lg shadow-blue-500/30' : 'bg-white border-gray-200 text-gray-400' }}">
                            @if($currentStep > $steps[$step])
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                {{ $steps[$step] }}
                            @endif
                        </div>
                        <span class="mt-3 text-xs font-semibold {{ $currentStep >= $steps[$step] ? 'text-blue-600' : 'text-gray-400' }}">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Issue Details</h2>
                
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Description</p>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-2xl border border-gray-100 whitespace-pre-wrap">{{ $ticket->description }}</p>
                </div>

                @if($ticket->file_path)
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Attachments</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $ticket->file_name }}</p>
                                <p class="text-xs text-gray-500">{{ $ticket->file_size }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $ticket->file_path) }}" download class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Download">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Conversation History</h2>
                
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                        </div>
                        <div class="flex-1 pb-6">
                            <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                                <p class="text-sm font-semibold text-blue-900">Ticket Submitted</p>
                                <p class="text-xs text-blue-700 mt-1">By {{ $ticket->user->name }} • {{ $ticket->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @foreach($ticket->comments as $comment)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $comment->user_id === auth()->id() ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                <span class="text-xs font-bold">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                            </div>
                            @if(!$loop->last)
                            <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-6">
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-gray-800">{{ $comment->user->name }}</span>
                                        @if($comment->user_id === auth()->id())
                                        <span class="text-[10px] px-1.5 py-0.5 bg-red-100 text-red-600 rounded-md font-medium">You</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comment->comment }}</p>
                                
                                @if($comment->file_path)
                                <div class="mt-3 flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 w-max hover:border-red-200 hover:bg-red-50 transition-colors cursor-pointer">
                                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-800">{{ $comment->file_name }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $comment->file_size }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                                @if(in_array($ticket->status, ['Resolved', 'Closed']))
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 text-center">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">
                                {{ $ticket->status === 'Closed' ? 'This ticket is closed.' : 'This ticket has been resolved.' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $ticket->status === 'Closed' 
                                    ? 'No further comments can be added to this ticket.' 
                                    : 'No further comments can be added. If the issue persists, please create a new ticket.' }}
                            </p>
                        </div>
                    </div>
                @else
                    <form action="{{ route('tickets.comment', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 pt-6 border-t border-gray-100">
                        @csrf
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Add a Comment</label>
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="flex-1">
                                <textarea name="comment" rows="3" placeholder="Type your reply or add more details here..." class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 resize-none transition-all @error('comment') border-red-300 bg-red-50 @enderror">{{ old('comment') }}</textarea>
                                @error('comment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                
                                <div class="mt-3 flex items-center gap-3 flex-wrap">
                                    <label class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-100 hover:border-gray-300 cursor-pointer transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        Attach File
                                        <input type="file" name="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.docx" />
                                    </label>
                                </div>
                                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                                <div class="flex justify-between items-center mt-4">
                                    <p class="text-xs text-gray-400">Max 5MB • PDF, JPG, PNG, DOCX</p>
                                    <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all flex items-center gap-2 shadow-lg shadow-red-600/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Send Reply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm sticky top-24">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Ticket Details</h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Ticket ID</label>
                        <p class="text-sm font-mono font-semibold text-gray-800">{{ $ticket->ticket_id }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Category</label>
                        <p class="text-sm font-medium text-gray-800">{{ $ticket->category->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Priority</label>
                        <x-priority-badge :priority="$ticket->priority" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Created On</label>
                        <p class="text-sm font-medium text-gray-800">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    @if($ticket->status === 'Resolved')
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-xs font-semibold text-amber-800">Auto-Close Reminder</p>
                                <p class="text-[10px] text-amber-700 mt-0.5">
                                    This ticket will be automatically closed on 
                                    <strong>{{ $ticket->updated_at->addDays(3)->format('d M Y') }}</strong> 
                                    if no further action is taken.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Created By</label>
                        <p class="text-sm font-medium text-gray-800">{{ $ticket->user->name }}</p>
                    </div>

                    @if($ticket->assignee)
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Assigned To</label>
                        <p class="text-sm font-medium text-gray-800">{{ $ticket->assignee->name }}</p>
                    </div>
                    @endif

                                        @if(auth()->user()->role === 'admin')
                    <div class="pt-5 border-t border-gray-100 space-y-4">
                        <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                            @csrf
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Assign To</label>
                            <select name="assigned_to" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-300">
                                <option value="">-- Unassigned --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $ticket->assigned_to == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }} ({{ ucfirst($emp->role) }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full mt-3 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Assign Ticket
                            </button>
                        </form>

                        <form action="{{ route('tickets.update-status', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Update Status</label>
                            <select name="status" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300">
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            <button type="submit" class="w-full mt-3 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all">Update Status</button>
                        </form>
                    </div>
                    @elseif(auth()->user()->role === 'employee')
                    <div class="pt-5 border-t border-gray-100">
                        <form action="{{ route('tickets.update-status', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Update Status</label>
                            <select name="status" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300">
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            <button type="submit" class="w-full mt-3 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all">Update Status</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection