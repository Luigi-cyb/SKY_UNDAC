<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Evaluaciones
            </h2>
            <a href="{{ route('evaluaciones.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Evaluación
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full mr-4">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full mr-4">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Activas</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ $stats['activas'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Calificadas</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['con_calificaciones'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-full mr-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pendientes</p>
                            <p class="text-2xl font-bold text-red-600">{{ $stats['pendientes'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <form method="GET" action="{{ route('evaluaciones.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                        <select name="curso_id" class="w-full rounded-md border-gray-300">
                            <option value="">Todos</option>
                            @foreach($cursos ?? [] as $curso)
                                <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                    {{ $curso->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select name="tipo" class="w-full rounded-md border-gray-300">
                            <option value="">Todos</option>
                            <option value="parcial" {{ request('tipo') == 'parcial' ? 'selected' : '' }}>Parcial</option>
                            <option value="final" {{ request('tipo') == 'final' ? 'selected' : '' }}>Final</option>
                            <option value="trabajo" {{ request('tipo') == 'trabajo' ? 'selected' : '' }}>Trabajo</option>
                            <option value="practica" {{ request('tipo') == 'practica' ? 'selected' : '' }}>Práctica</option>
                            <option value="proyecto" {{ request('tipo') == 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="activo" class="w-full rounded-md border-gray-300">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activa</option>
                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactiva</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buscar
                        </button>
                        <a href="{{ route('evaluaciones.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    @if(isset($evaluaciones) && $evaluaciones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peso</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($evaluaciones as $evaluacion)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm">
                                            <div class="font-medium text-gray-900">{{ $evaluacion->curso->nombre ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $evaluacion->curso->codigo ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $evaluacion->nombre }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                {{ $evaluacion->tipo == 'parcial' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $evaluacion->tipo == 'final' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $evaluacion->tipo == 'trabajo' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $evaluacion->tipo == 'practica' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $evaluacion->tipo == 'proyecto' ? 'bg-purple-100 text-purple-800' : '' }}">
                                                {{ ucfirst($evaluacion->tipo) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $evaluacion->fecha_evaluacion ? \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $evaluacion->peso_porcentaje }}%</td>
                                        <td class="px-6 py-4 text-center">
    <div class="flex justify-center gap-3">
        <a href="{{ route('evaluaciones.preguntas', $evaluacion) }}" 
   class="text-blue-600 hover:text-blue-900" title="Gestionar preguntas">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
</a>
        <a href="{{ route('evaluaciones.calificar', $evaluacion) }}" 
           class="text-green-600 hover:text-green-900" title="Calificar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </a>
    </div>
</td>
                                       <td class="px-6 py-4 text-center">
    <div class="flex justify-center gap-2">
      

        {{-- 🆕 GESTIONAR PREGUNTAS --}}
        <a href="{{ route('evaluaciones.preguntas', $evaluacion) }}" 
           class="text-purple-600 hover:text-purple-900 p-1" 
           title="Gestionar Preguntas">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </a>

        {{-- Editar --}}
        <a href="{{ route('evaluaciones.edit', $evaluacion) }}" 
           class="text-yellow-600 hover:text-yellow-900 p-1" 
           title="Editar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </a>

        {{-- Calificar --}}
        <a href="{{ route('evaluaciones.calificar', $evaluacion) }}" 
           class="text-green-600 hover:text-green-900 p-1" 
           title="Calificar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </a>

        {{-- Cambiar estado --}}
        <form action="{{ route('evaluaciones.toggle-status', $evaluacion) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" 
                    class="p-1 {{ $evaluacion->activo ? 'text-red-600 hover:text-red-900' : 'text-gray-600 hover:text-gray-900' }}" 
                    title="{{ $evaluacion->activo ? 'Desactivar' : 'Activar' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($evaluacion->activo)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    @endif
                </svg>
            </button>
        </form>
    </div>
</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $evaluaciones->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay evaluaciones</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva evaluación</p>
                            <div class="mt-6">
                                <a href="{{ route('evaluaciones.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Nueva Evaluación
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>