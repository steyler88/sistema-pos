@props([
    'variant' => 'default', // default, success, warning, danger, info
    'size' => 'md', // sm, md, lg
])

@php
    $baseClasses = 'inline-flex items-center font-semibold rounded-full';
    
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
        default => 'px-3 py-1 text-sm',
    };
    
    $variantClasses = match($variant) {
        'success' => 'bg-green-500/20 text-green-400 border border-green-500/30',
        'warning' => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
        'danger' => 'bg-red-500/20 text-red-400 border border-red-500/30',
        'info' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
        'primary' => 'bg-orange-500/20 text-orange-400 border border-orange-500/30',
        default => 'bg-gray-700 text-gray-300 border border-gray-600',
    };
    
    $classes = trim("{$baseClasses} {$sizeClasses} {$variantClasses}");
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>

