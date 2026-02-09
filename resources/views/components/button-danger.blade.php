@props([
    'type' => 'button',
    'size' => 'md',
    'fullWidth' => false,
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        default => 'px-4 py-2 text-base',
    };
    
    $colorClasses = 'bg-red-600 hover:bg-red-700 active:bg-red-800 text-white shadow-md hover:shadow-lg';
    
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

