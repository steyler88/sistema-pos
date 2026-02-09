@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

@php
    $cardClasses = 'bg-gray-800 border border-gray-700 rounded-lg shadow-lg overflow-hidden transition-all duration-200 hover:shadow-xl';
    $contentClasses = $padding ? 'p-6' : '';
@endphp

<div {{ $attributes->merge(['class' => $cardClasses]) }}>
    @if($title || $subtitle)
        <div class="px-6 py-4 border-b border-gray-700 bg-gray-800/50">
            @if($title)
                <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-gray-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="{{ $contentClasses }}">
        {{ $slot }}
    </div>
</div>

