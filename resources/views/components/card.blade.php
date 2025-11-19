@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
    'footer' => null
])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg']) }}>
    @if($title || $subtitle)
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        @if($title)
        <h3 class="text-lg font-semibold text-gray-900">
            {{ $title }}
        </h3>
        @endif
        
        @if($subtitle)
        <p class="mt-1 text-sm text-gray-600">
            {{ $subtitle }}
        </p>
        @endif
    </div>
    @endif
    
    <div class="{{ $padding ? 'p-6' : '' }} text-gray-900">
        {{ $slot }}
    </div>
    
    @if($footer)
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $footer }}
    </div>
    @endif
</div>