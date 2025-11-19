@props([
    'headers' => [],
    'striped' => true,
    'hoverable' => true,
    'bordered' => false
])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
        @if(!empty($headers))
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        @endif
        
        <tbody class="{{ $striped ? 'bg-white divide-y divide-gray-200' : 'bg-white' }}">
            {{ $slot }}
        </tbody>
    </table>
</div>

@push('styles')
<style>
    @if($hoverable)
    tbody tr:hover {
        background-color: #f9fafb;
    }
    @endif
    
    @if($striped)
    tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }
    @endif
    
    @if($bordered)
    table {
        border: 1px solid #e5e7eb;
    }
    th, td {
        border: 1px solid #e5e7eb;
    }
    @endif
</style>
@endpush