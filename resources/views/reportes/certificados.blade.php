<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    🎓 Reporte de Certificados
                </h2>
                <p class="text-sm text-gray-600 mt-1">Análisis de certificados emitidos y validaciones</p>
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
                <div class="px-6 py-4" style="background: linear-gradient(to right, #8b5cf6, #7c3aed) !important;">
                    <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtros de Búsqueda
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('reportes.certificados') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="fecha_desde" class="block text-sm font-bold text-gray-700 mb-2">Fecha Desde</label>
                                <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                            </div>
                            <div>
                                <label for="fecha_hasta" class="block text-sm font-bold text-gray-700 mb-2">Fecha Hasta</label>
                                <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                            </div>
                        </div>
                        <div class="mt-6 flex space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2 transition shadow-lg"
                                    style="background: linear-gradient(to right, #8b5cf6, #7c3aed) !important; color: white !important;">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filtrar
                            </button>
                            <a href="{{ route('reportes.certificados') }}" 
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Total Emitidos</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['total_emitidos'] }}</p>
                            <p class="text-xs mt-1" style="color: rgba(255, 255, 255, 0.8) !important;">Certificados generados</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #ef4444, #dc2626) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Revocados</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['revocados'] }}</p>
                            <p class="text-xs mt-1" style="color: rgba(255, 255, 255, 0.8) !important;">Certificados anulados</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>

                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #10b981, #059669) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Validaciones</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['validaciones'] }}</p>
                            <p class="text-xs mt-1" style="color: rgba(255, 255, 255, 0.8) !important;">Verificaciones realizadas</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabla de Certificados -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Certificados Emitidos 
                        <span class="ml-2 text-sm font-normal text-gray-600">({{ $certificados->count() }} registros)</span>
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">N° Serie</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Estudiante</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Curso</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Fecha Emisión</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($certificados as $certificado)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-purple-100 text-purple-800 font-mono">
                                            {{ $certificado->numero_serie }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed) !important;">
                                                <span class="font-bold text-sm" style="color: white !important;">{{ substr($certificado->inscripcion->estudiante->nombres, 0, 1) }}{{ substr($certificado->inscripcion->estudiante->apellidos, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $certificado->inscripcion->estudiante->nombres }} {{ $certificado->inscripcion->estudiante->apellidos }}</div>
                                                <div class="text-xs text-gray-500">DNI: {{ $certificado->inscripcion->estudiante->dni }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $certificado->inscripcion->curso->nombre }}</div>
                                        <div class="text-xs text-gray-500">{{ $certificado->inscripcion->curso->codigo }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($certificado->fecha_emision)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($certificado->fecha_revocacion)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                <svg class="h-2 w-2 mr-1.5 fill-current" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Revocado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                <svg class="h-2 w-2 mr-1.5 fill-current" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Válido
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="mt-4 text-lg font-semibold text-gray-900">No hay certificados emitidos</p>
                                        <p class="mt-2 text-sm text-gray-500">No se encontraron certificados en el periodo seleccionado</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>