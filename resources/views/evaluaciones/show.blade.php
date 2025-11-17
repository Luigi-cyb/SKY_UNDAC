<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles de la Evaluación
            </h2>
            <div class="flex gap-2">
    <!-- Botón Gestionar Preguntas -->
    <a href="{{ route('evaluaciones.preguntas', $evaluacion) }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Gestionar Preguntas
    </a>
    
    <!-- Botón Calificar -->
    <a href="{{ route('evaluaciones.calificar', $evaluacion) }}" 
       class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        Calificar
    </a>
    
    <!-- Botón Volver -->
    <a href="{{ route('evaluaciones.index') }}" 
       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
        Volver
    </a>
</div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información General -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                    Información General
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Título</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $evaluacion->nombre }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Curso</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $evaluacion->curso->nombre }}</p>
                        <p class="text-xs text-gray-500">{{ $evaluacion->curso->codigo }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Tipo</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                            {{ $evaluacion->tipo == 'parcial' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $evaluacion->tipo == 'final' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $evaluacion->tipo == 'trabajo' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $evaluacion->tipo == 'practica' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $evaluacion->tipo == 'proyecto' ? 'bg-purple-100 text-purple-800' : '' }}">
                            {{ ucfirst($evaluacion->tipo) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Fecha de Evaluación</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $evaluacion->fecha_evaluacion ? \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') : 'No definida' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Peso Porcentual</p>
                        <p class="text-lg font-bold text-blue-600">{{ $evaluacion->peso_porcentaje }}%</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Nota Máxima</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $evaluacion->nota_maxima }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Estado</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $evaluacion->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $evaluacion->activo ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    @if($evaluacion->descripcion)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Descripción</p>
                        <p class="text-gray-900 mt-1">{{ $evaluacion->descripcion }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_calificados'] ?? 0 }}</p>
                        <p class="text-sm text-gray-600 mt-2">Estudiantes Calificados</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">{{ $stats['promedio'] ?? 0 }}</p>
                        <p class="text-sm text-gray-600 mt-2">Promedio</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">{{ $stats['aprobados'] ?? 0 }}</p>
                        <p class="text-sm text-gray-600 mt-2">Aprobados</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-red-600">{{ $stats['desaprobados'] ?? 0 }}</p>
                        <p class="text-sm text-gray-600 mt-2">Desaprobados</p>
                    </div>
                </div>
            </div>

            <!-- Lista de Calificaciones -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                    Calificaciones Registradas
                </h3>

                @if($evaluacion->calificaciones && $evaluacion->calificaciones->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nota</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($evaluacion->calificaciones as $calificacion)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $calificacion->inscripcion->estudiante->nombres ?? 'N/A' }}
                                        {{ $calificacion->inscripcion->estudiante->apellidos ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-lg font-bold {{ $calificacion->nota >= 10.5 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($calificacion->nota, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $calificacion->nota >= 10.5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $calificacion->nota >= 10.5 ? 'Aprobado' : 'Desaprobado' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $calificacion->observaciones ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No hay calificaciones registradas aún</p>
                        <a href="{{ route('evaluaciones.calificar', $evaluacion) }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Comenzar a Calificar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>