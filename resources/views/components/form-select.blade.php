@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Seleccionar opción',
    'hint' => null,
    'error' => null,
    'multiple' => false
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
    
    <select 
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->merge([
            'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm' .
                      ($error ? ' border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500' : '')
        ]) }}
    >
        @if(!$multiple && $placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif
        
        @if(is_array($options))
            @foreach($options as $value => $text)
                <option value="{{ $value }}" 
                    {{ old($name, $selected) == $value ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        @else
            {{ $options }}
        @endif
    </select>
    
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