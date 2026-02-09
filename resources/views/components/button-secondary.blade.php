@props([
    'type' => 'button',
    'size' => 'md',
    'fullWidth' => false,
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        default => 'px-4 py-2 text-base',
    };
    
    $colorClasses = 'bg-gray-700 hover:bg-gray-600 active:bg-gray-500 text-white border border-gray-600';
    
    $widthClass = $fullWidth ? 'w-full' : '';
    
    $classes = trim("{$baseClasses} {$sizeClasses} {$colorClasses} {$widthClass}");
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($icon)
        <span class="mr-2">{!! $icon !!}</span>
    @endif
    
    {{ $slot }}
</button>

