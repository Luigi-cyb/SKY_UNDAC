@props([
    'type' => 'text',
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => null,
    'hint' => null,
    'error' => null,
    'icon' => null,
    'prepend' => null,
    'append' => null
])

<div class="mb-4">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <div class="relative">
        @if($prepend)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">{{ $prepend }}</span>
        </div>
        @endif
        
        @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            {{ $icon }}
        </div>
        @endif
        
        <input 
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm' . 
                          ($icon || $prepend ? ' pl-10' : '') . 
                          ($append ? ' pr-12' : '') .
                          ($error ? ' border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : '')
            ]) }}
        />
        
        @if($append)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">{{ $append }}</span>
        </div>
        @endif
    </div>
    
    @if($hint && !$error)
    <p class="mt-1 text-sm text-gray-500">
        {{ $hint }}
    </p>
    @endif
    
    @if($error)
    <p class="mt-1 text-sm text-red-600">
        {{ $error }}
    </p>
    @elseif($errors->has($name))
    <p class="mt-1 text-sm text-red-600">
        {{ $errors->first($name) }}
    </p>
    @endif
</div>