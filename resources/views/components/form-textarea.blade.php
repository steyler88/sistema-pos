@props([
    'label' => null,
    'name' => '',
    'placeholder' => '',
    'rows' => 3,
    'required' => false,
    'error' => null,
    'helpText' => null,
])

@php
    $textareaClasses = 'w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed resize-y';
    
    if ($error) {
        $textareaClasses .= ' border-red-500 focus:border-red-500 focus:ring-red-500';
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
    
    <textarea 
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => $textareaClasses]) }}
    >{{ $slot }}</textarea>
    
    @if($helpText)
        <p class="mt-1 text-xs text-gray-400">{{ $helpText }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-400">{{ $error }}</p>
    @endif
</div>

