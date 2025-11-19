<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    📊 Reporte de Calificaciones
                </h2>
                <p class="text-sm text-gray-600 mt-1">Análisis detallado de calificaciones del sistema</p>
            </div>
            <a href="{{ route('reportes.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                <div class="px-6 py-4" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important;">
                    <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtros de Búsqueda
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('reportes.calificaciones') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Filtro por Curso -->
                            <div>
                                <label for="curso_id" class="block text-sm font-bold text-gray-700 mb-2">Curso</label>
                                <select name="curso_id" id="curso_id" 
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option value="">Todos los cursos</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- DNI Estudiante -->
                            <div>
                                <label for="estudiante_dni" class="block text-sm font-bold text-gray-700 mb-2">DNI Estudiante</label>
                                <input type="text" name="estudiante_dni" id="estudiante_dni" 
                                       value="{{ request('estudiante_dni') }}"
                                       placeholder="Ej: 12345678"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <!-- Fecha Desde -->
                            <div>
                                <label for="fecha_desde" class="block text-sm font-bold text-gray-700 mb-2">Fecha Desde</label>
                                <input type="date" name="fecha_desde" id="fecha_desde" 
                                       value="{{ request('fecha_desde') }}" 
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <!-- Fecha Hasta -->
                            <div>
                                <label for="fecha_hasta" class="block text-sm font-bold text-gray-700 mb-2">Fecha Hasta</label>
                                <input type="date" name="fecha_hasta" id="fecha_hasta" 
                                       value="{{ request('fecha_hasta') }}" 
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-6 flex space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition shadow-lg"
                                    style="background: linear-gradient(to right, #3b82f6, #2563eb) !important; color: white !important;">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Buscar
                            </button>
                            <a href="{{ route('reportes.calificaciones') }}" 
                               class="inline-flex items-center px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded-lg transition uppercase text-sm">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <!-- Promedio General -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Promedio</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ number_format($estadisticas['promedio_general'] ?? 0, 2) }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Nota Más Alta -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #10b981, #059669) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Nota Alta</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ number_format($estadisticas['nota_mas_alta'] ?? 0, 2) }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>

                <!-- Nota Más Baja -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #ef4444, #dc2626) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Nota Baja</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ number_format($estadisticas['nota_mas_baja'] ?? 0, 2) }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                </div>

                <!-- Total Calificaciones -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #9333ea, #7e22ce) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Total</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['total_calificaciones'] ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>

                <!-- Aprobados -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #14b8a6, #0d9488) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Aprobados</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['aprobados'] ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabla de Calificaciones -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Calificaciones Registradas 
                        <span class="ml-2 text-sm font-normal text-gray-600">({{ $calificaciones->total() }} registros)</span>
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Estudiante</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Curso</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Evaluación</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Nota</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Peso %</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($calificaciones as $calificacion)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb) !important;">
                                                <span class="font-bold text-sm" style="color: white !important;">{{ substr($calificacion->nombres, 0, 1) }}{{ substr($calificacion->apellidos, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $calificacion->nombres }} {{ $calificacion->apellidos }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-800">
        {{ $calificacion->codigo_estudiante }}  // ✅ CORRECTO
    </span>
</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $calificacion->curso_nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $calificacion->evaluacion_nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center h-12 w-16 rounded-lg text-2xl font-bold {{ $calificacion->nota >= 11 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ number_format($calificacion->nota, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-100 text-indigo-800">
                                            {{ $calificacion->peso_porcentaje }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold {{ $calificacion->nota >= 11 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <svg class="h-2 w-2 mr-1.5 fill-current" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            {{ $calificacion->nota >= 11 ? 'Aprobado' : 'Desaprobado' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        <p class="mt-4 text-lg font-semibold text-gray-900">No hay calificaciones registradas</p>
                                        <p class="mt-2 text-sm text-gray-500">Los registros aparecerán aquí cuando se califiquen las evaluaciones</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($calificaciones->hasPages())
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        {{ $calificaciones->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>