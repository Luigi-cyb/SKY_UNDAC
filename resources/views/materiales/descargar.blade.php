<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Descargar Material') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Icono y Título -->
                    <div class="text-center mb-8">
                        <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 rounded-full
                            @if($material->tipo_material == 'documento') bg-red-100
                            @elseif($material->tipo_material == 'presentacion') bg-orange-100
                            @elseif($material->tipo_material == 'video') bg-purple-100
                            @elseif($material->tipo_material == 'enlace') bg-blue-100
                            @else bg-gray-100
                            @endif">
                            @if($material->tipo_material == 'documento')
                                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            @elseif($material->tipo_material == 'presentacion')
                                <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                            @elseif($material->tipo_material == 'video')
                                <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            @elseif($material->tipo_material == 'enlace')
                                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            @else
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $material->titulo }}
                        </h3>
                        <p class="text-gray-600">
                            {{ $material->curso->nombre }}
                        </p>
                    </div>

                    <!-- Información del Material -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Tipo de Material:</p>
                                <p class="text-sm text-gray-900 capitalize">{{ $material->tipo_material }}</p>
                            </div>
                            @if($material->tamano_archivo)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Tamaño del Archivo:</p>
                                <p class="text-sm text-gray-900">{{ number_format($material->tamano_archivo / 1024, 2) }} KB</p>
                            </div>
                            @endif
                            @if($material->sesion_numero)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Sesión:</p>
                                <p class="text-sm text-gray-900">Sesión {{ $material->sesion_numero }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-700">Fecha de Publicación:</p>
                                <p class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($material->created_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($material->descripcion)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-2">Descripción:</p>
                            <p class="text-sm text-gray-900">{{ $material->descripcion }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Estadísticas de Descarga -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">
                                    Este material ha sido descargado {{ $material->descargas->count() }} {{ $material->descargas->count() == 1 ? 'vez' : 'veces' }}
                                </p>
                                <p class="text-xs text-blue-700 mt-1">
                                    Última descarga: {{ $material->descargas->count() > 0 ? \Carbon\Carbon::parse($material->descargas->first()->created_at)->diffForHumans() : 'Ninguna' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($material->tipo_material == 'enlace')
                            <!-- Abrir Enlace Externo -->
                            <a href="{{ $material->enlace_externo }}" target="_blank" rel="noopener noreferrer"
                               class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-3 px-6 rounded inline-flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Abrir Enlace
                            </a>
                        @else
                            <!-- Descargar Archivo -->
                            @if($material->permite_descarga)
                            <form action="{{ route('materiales.descargar', $material) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded inline-flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Descargar Archivo
                                </button>
                            </form>
                            @else
                            <div class="flex-1">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                    <svg class="w-8 h-8 text-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-yellow-800">
                                        Descarga no disponible
                                    </p>
                                    <p class="text-xs text-yellow-700 mt-1">
El docente ha restringido la descarga de este material
</p>
</div>
</div>
@endif
@endif
<!-- Volver -->
                    <a href="{{ route('materiales.index') }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-3 px-6 rounded inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Materiales
                    </a>
                </div>

                <!-- Advertencia de Material Obligatorio -->
                @if($material->es_obligatorio)
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-900">
                                ⚠️ Material Obligatorio
                            </p>
                            <p class="text-xs text-red-700 mt-1">
                                Este material es de lectura/visualización obligatoria para el curso
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Historial de Descargas (Solo para Docentes/Admin) -->
                @can('materiales.ver-historial')
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Historial de Descargas</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha y Hora
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($material->descargas()->latest()->take(10)->get() as $descarga)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $descarga->estudiante->nombres }} {{ $descarga->estudiante->apellidos }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $descarga->estudiante->codigo_estudiante }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($descarga->created_at)->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($descarga->created_at)->diffForHumans() }}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                        No hay descargas registradas
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>