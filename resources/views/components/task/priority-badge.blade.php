@props(['priority'])

@php
    $colors = match ($priority) {
        'low' => 'bg-green-100 text-green-700',
        'medium' => 'bg-yellow-100 text-yellow-700',
        'high' => 'bg-red-100 text-red-600',
        default => 'bg-gray-100 text-gray-600',
    };
@endphp

<span {{ $attributes->merge([
    'class' => "px-3 py-1 rounded-full text-xs font-medium $colors",
]) }}>
    {{ ucfirst($priority) }}
</span>
