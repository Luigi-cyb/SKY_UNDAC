@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
    'icon' => true
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-400',
            'text' => 'text-green-800',
            'icon' => '✓',
            'iconBg' => 'bg-green-100'
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-400',
            'text' => 'text-red-800',
            'icon' => '✕',
            'iconBg' => 'bg-red-100'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-400',
            'text' => 'text-yellow-800',
            'icon' => '⚠',
            'iconBg' => 'bg-yellow-100'
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-400',
            'text' => 'text-blue-800',
            'icon' => 'ℹ',
            'iconBg' => 'bg-blue-100'
        ],
        'primary' => [
            'bg' => 'bg-indigo-50',
            'border' => 'border-indigo-400',
            'text' => 'text-indigo-800',
            'icon' => '●',
            'iconBg' => 'bg-indigo-100'
        ]
    ];
    
    $config = $types[$type] ?? $types['info'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-start p-4 rounded-lg border-l-4 {$config['bg']} {$config['border']} {$config['text']}"]) }}
     role="alert"
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
    
    @if($icon)
    <div class="flex-shrink-0">
        <span class="flex items-center justify-center w-8 h-8 {{ $config['iconBg'] }} rounded-full text-lg font-bold">
            {{ $config['icon'] }}
        </span>
    </div>
    @endif
    
    <div class="flex-1 {{ $icon ? 'ml-3' : '' }}">
        @if($title)
        <h3 class="text-sm font-bold mb-1">
            {{ $title }}
        </h3>
        @endif
        
        <div class="text-sm">
            {{ $slot }}
        </div>
    </div>
    
    @if($dismissible)
    <button @click="show = false" 
            type="button" 
            class="flex-shrink-0 ml-3 inline-flex {{ $config['text'] }} hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
            aria-label="Cerrar">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    @endif
</div>