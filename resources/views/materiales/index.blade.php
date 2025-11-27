<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Materiales del Curso') }}
            </h2>
            @can('materiales.subir')
            <a href="{{ route('materiales.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Subir Material
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Materiales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Total Materiales</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalMateriales }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PDFs -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Documentos PDF</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalPDFs }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Videos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Videos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalVideos }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Descargas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Total Descargas</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalDescargas }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('materiales.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Búsqueda -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Buscar material..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Curso -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Curso</label>
                            <select name="curso_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos los cursos</option>
                                @foreach($cursos as $curso)
                                <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                    {{ $curso->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de Material -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                            <select name="tipo_material" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos los tipos</option>
                                <option value="documento" {{ request('tipo_material') == 'documento' ? 'selected' : '' }}>Documento</option>
                                <option value="presentacion" {{ request('tipo_material') == 'presentacion' ? 'selected' : '' }}>Presentación</option>
                                <option value="video" {{ request('tipo_material') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="enlace" {{ request('tipo_material') == 'enlace' ? 'selected' : '' }}>Enlace</option>
                                <option value="otro" {{ request('tipo_material') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filtrar
                            </button>
                            <a href="{{ route('materiales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Grid de Materiales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($materiales as $material)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <!-- Icono del Tipo de Archivo -->
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-lg
                            @if($material->tipo_material == 'documento') bg-red-100
                            @elseif($material->tipo_material == 'presentacion') bg-orange-100
                            @elseif($material->tipo_material == 'video') bg-purple-100
                            @elseif($material->tipo_material == 'enlace') bg-blue-100
                            @else bg-gray-100
                            @endif">
                            @if($material->tipo_material == 'documento')
                                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            @elseif($material->tipo_material == 'presentacion')
                                <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                            @elseif($material->tipo_material == 'video')
                                <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            @elseif($material->tipo_material == 'enlace')
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            @else
                                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Información del Material -->
                        <div class="text-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $material->titulo }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">
                                {{ $material->curso->nombre }}
                            </p>
                            @if($material->descripcion)
                            <p class="text-xs text-gray-500 line-clamp-2">
                                {{ $material->descripcion }}
                            </p>
                            @endif
                        </div>

                        <!-- Metadatos -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Tipo:</span>
                                <span class="font-medium text-gray-900 capitalize">{{ $material->tipo_material }}</span>
                            </div>
                            @if($material->tamano_archivo)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Tamaño:</span>
                                <span class="font-medium text-gray-900">{{ number_format($material->tamano_archivo / 1024, 2) }} KB</span>
                            </div>
                            @endif
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Descargas:</span>
                                <span class="font-medium text-gray-900">{{ $material->descargas->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Subido:</span>
                                <span class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($material->created_at)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="mt-4 flex space-x-2">
                            @can('materiales.descargar')
                            <a href="{{ route('materiales.descargar', $material) }}" 
                               class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Descargar
                            </a>
                            @endcan

                            @can('materiales.editar')
                            <a href="{{ route('materiales.edit', $material) }}" 
                               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded"
                               title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            @endcan

                            @can('materiales.eliminar')
                            <form action="{{ route('materiales.destroy', $material) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded"
                                        onclick="return confirm('¿Está seguro de eliminar este material?')"
                                        title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
                @empty
                <div class="md:col-span-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay materiales</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza subiendo un nuevo material.</p>
                            @can('materiales.subir')
                            <div class="mt-6">
                                <a href="{{ route('materiales.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Subir Material
                                </a>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $materiales->links() }}
            </div>
        </div>
    </div>
</x-app-layout>