@props(['priority'])

@php
    $classes = match($priority) {
        'High' => 'bg-red-50 text-red-700 border border-red-200',
        'Medium' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
        'Low' => 'bg-green-50 text-green-700 border border-green-200',
        default => 'bg-gray-50 text-gray-700 border border-gray-200',
    };
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $classes }}">
    {{ $priority }}
</span>