@props([
    'label' => null,
    'name' => '',
    'options' => [],
    'required' => false,
    'error' => null,
    'placeholder' => 'Selecciona una opción',
])

@php
    $selectClasses = 'w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed';
    
    if ($error) {
        $selectClasses .= ' border-red-500 focus:border-red-500 focus:ring-red-500';
    }
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-300 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => $selectClasses]) }}
    >
        @if($placeholder)
            <option value="" class="text-gray-500">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
    
    @if($error)
        <p class="mt-1 text-sm text-red-400">{{ $error }}</p>
    @endif
</div>

