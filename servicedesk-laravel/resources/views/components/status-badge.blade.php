@props(['status'])

@php
    $bgClasses = match($status) {
        'Open' => 'bg-blue-50 text-blue-700',
        'In Progress' => 'bg-yellow-50 text-yellow-700',
        'Resolved' => 'bg-green-50 text-green-700',
        'Closed' => 'bg-gray-50 text-gray-700',
        'Pending' => 'bg-orange-50 text-orange-700',
        default => 'bg-gray-50 text-gray-700',
    };

    $dotClasses = match($status) {
        'Open' => 'bg-blue-500',
        'In Progress' => 'bg-yellow-500',
        'Resolved' => 'bg-green-500',
        'Closed' => 'bg-gray-500',
        'Pending' => 'bg-orange-500',
        default => 'bg-gray-500',
    };
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $bgClasses }}">
    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotClasses }}"></span>
    {{ $status }}
</span>