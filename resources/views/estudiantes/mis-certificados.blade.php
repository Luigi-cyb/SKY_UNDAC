<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">
            🎓 Mis Certificados
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(isset($mensaje))
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
                <p class="text-yellow-800">{{ $mensaje }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(isset($certificados) && $certificados->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                   @foreach($certificados as $certificado)
<div class="border-2 border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
    <div class="flex items-center justify-between mb-4">
        <span class="text-3xl">🎓</span>
        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
            {{ ucfirst($certificado->estado) }}
        </span>
    </div>
    <h3 class="font-bold text-lg text-gray-900 mb-2">
        {{ $certificado->inscripcion->curso->nombre ?? 'Curso sin nombre' }}
    </h3>
    <p class="text-sm text-gray-600 mb-2">
        Código: {{ $certificado->codigo_certificado }}
    </p>
    <p class="text-xs text-gray-500 mb-4">
        Emitido: {{ $certificado->fecha_emision ? \Carbon\Carbon::parse($certificado->fecha_emision)->format('d/m/Y') : 'N/A' }}
    </p>
    @if($certificado->pdf_url)
    <div class="flex gap-3">
        {{-- Botón Ver Certificado (redirige a vista pública) --}}
        <a href="{{ route('certificado.publico', $certificado->codigo_qr) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Ver Certificado
        </a>
        
       {{-- Botón Descargar PDF --}}
<a href="{{ route('certificados.descargar-pdf', $certificado->id) }}" 
   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Descargar PDF
        </a>
    </div>
@else
    <span class="text-sm text-gray-500">PDF no disponible</span>
@endif
</div>
@endforeach     
                </div>
                @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🎓</div>
                    <p class="text-gray-600 text-lg font-semibold">No tienes certificados disponibles</p>
                    <p class="text-gray-500 text-sm mt-2">Los certificados aparecerán cuando completes los cursos</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
