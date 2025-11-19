<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    📊 Reporte de Asistencia
                </h2>
                <p class="text-sm text-gray-600 mt-1">Análisis detallado de asistencia del sistema</p>
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
                    <form method="GET" action="{{ route('reportes.asistencia') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Curso -->
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

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                                <select name="estado" id="estado" 
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option value="">Todos los estados</option>
                                    <option value="presente" {{ request('estado') == 'presente' ? 'selected' : '' }}>✅ Presente</option>
                                    <option value="ausente" {{ request('estado') == 'ausente' ? 'selected' : '' }}>❌ Ausente</option>
                                    <option value="tardanza" {{ request('estado') == 'tardanza' ? 'selected' : '' }}>⏰ Tardanza</option>
                                    <option value="justificado" {{ request('estado') == 'justificado' ? 'selected' : '' }}>📝 Justificado</option>
                                </select>
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
                            <a href="{{ route('reportes.asistencia') }}" 
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
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-6">
                <!-- Total -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb) !important;">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 mb-2" style="color: rgba(255, 255, 255, 0.9) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Total</p>
                        <p class="text-3xl font-bold mt-1" style="color: white !important;">{{ $estadisticas['total_registros'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Presentes -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #10b981, #059669) !important;">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 mb-2" style="color: rgba(255, 255, 255, 0.9) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Presentes</p>
                        <p class="text-3xl font-bold mt-1" style="color: white !important;">{{ $estadisticas['presentes'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Ausentes -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #ef4444, #dc2626) !important;">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 mb-2" style="color: rgba(255, 255, 255, 0.9) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Ausentes</p>
                        <p class="text-3xl font-bold mt-1" style="color: white !important;">{{ $estadisticas['ausentes'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Tardanzas -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #f59e0b, #d97706) !important;">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 mb-2" style="color: rgba(255, 255, 255, 0.9) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Tardanzas</p>
                        <p class="text-3xl font-bold mt-1" style="color: white !important;">{{ $estadisticas['tardanzas'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Justificados -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #9333ea, #7e22ce) !important;">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 mb-2" style="color: rgba(255, 255, 255, 0.9) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Justificados</p>
                        <p class="text-3xl font-bold mt-1" style="color: white !important;">{{ $estadisticas['justificados'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- % Asistencia -->
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #14b8a6, #0d9488) !important;">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 mb-2" style="color: rgba(255, 255, 255, 0.9) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">% Asistencia</p>
                        <p class="text-3xl font-bold mt-1" style="color: white !important;">{{ number_format($estadisticas['porcentaje_asistencia'] ?? 0, 1) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Registros de Asistencia 
                        <span class="ml-2 text-sm font-normal text-gray-600">({{ $asistencias->total() }} registros)</span>
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
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Sesión</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($asistencias as $asistencia)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #4f46e5) !important;">
                                                <span class="font-bold text-sm" style="color: white !important;">{{ substr($asistencia->nombres, 0, 1) }}{{ substr($asistencia->apellidos, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $asistencia->nombres }} {{ $asistencia->apellidos }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-800">
        {{ $asistencia->codigo_estudiante }}  // ✅ CORRECTO
    </span>
</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $asistencia->curso_nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center h-8 w-12 rounded-lg text-sm font-bold bg-indigo-100 text-indigo-800">
                                            {{ $asistencia->numero_sesion }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($asistencia->fecha_sesion)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($asistencia->estado == 'presente')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                <svg class="h-2 w-2 mr-1.5 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                                Presente
                                            </span>
                                        @elseif($asistencia->estado == 'ausente')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                <svg class="h-2 w-2 mr-1.5 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                                Ausente
                                            </span>
                                        @elseif($asistencia->estado == 'tardanza')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                                <svg class="h-2 w-2 mr-1.5 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                                Tardanza
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                                <svg class="h-2 w-2 mr-1.5 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                                Justificado
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        <p class="mt-4 text-lg font-semibold text-gray-900">No hay registros de asistencia</p>
                                        <p class="mt-2 text-sm text-gray-500">Los registros aparecerán aquí cuando se tomen las asistencias</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($asistencias->hasPages())
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        {{ $asistencias->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>