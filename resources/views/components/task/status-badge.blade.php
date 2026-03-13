@props(['status'])

@php
    $colors = match ($status) {
        'pending' => 'bg-gray-100 text-gray-600',
        'in_progress' => 'bg-blue-100 text-blue-700',
        'completed' => 'bg-green-100 text-green-700',
        default => 'bg-red-100 text-red-600',
    };
@endphp

<span class="px-3 py-1 rounded-full text-xs font-medium {{ $colors }}">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
