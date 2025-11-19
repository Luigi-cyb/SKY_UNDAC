<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Historial de Calificaciones - {{ $estudiante->nombres }} {{ $estudiante->apellidos }}
            </h2>
            <a href="{{ route('calificaciones.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información del Estudiante -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-20 w-20">
                                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-2xl">
                                        {{ substr($estudiante->nombres, 0, 1) }}{{ substr($estudiante->apellidos, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ $estudiante->nombres }} {{ $estudiante->apellidos }}
                                </h3>
                                <div class="mt-2 space-y-1">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">DNI:</span> {{ $estudiante->dni }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Email:</span> {{ $estudiante->email }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Código:</span> {{ $estudiante->codigo_estudiante ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Promedio General -->
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500">Promedio General</p>
                            <p class="text-4xl font-bold {{ $promedioGeneral >= 11 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($promedioGeneral, 2) }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $totalCursosAprobados }}/{{ $totalCursosInscritos }} Cursos Aprobados
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Cursos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Cursos Inscritos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalCursosInscritos }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cursos Aprobados -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Aprobados</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalCursosAprobados }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cursos Desaprobados -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Desaprobados</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalCursosDesaprobados }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Evaluaciones -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Evaluaciones</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalEvaluaciones }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial por Curso -->
            @foreach($cursos as $curso)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <!-- Encabezado del Curso -->
                        <div class="flex justify-between items-start mb-6 pb-4 border-b">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $curso->nombre }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $curso->codigo }} - {{ $curso->modalidad->nombre ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-500">Promedio del Curso</p>
                                <p class="text-3xl font-bold {{ $curso->promedio_final >= 11 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($curso->promedio_final, 2) }}
                                </p>
                                @if($curso->promedio_final >= 11)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        APROBADO
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        DESAPROBADO
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Tabla de Calificaciones -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Evaluación
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipo
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ponderación
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nota
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nota Ponderada
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Observaciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($curso->calificaciones as $calificacion)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $calificacion->evaluacion->titulo }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($calificacion->evaluacion->tipo == 'Final') bg-red-100 text-red-800
                                                    @elseif($calificacion->evaluacion->tipo == 'Parcial') bg-blue-100 text-blue-800
                                                    @elseif($calificacion->evaluacion->tipo == 'Proyecto') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $calificacion->evaluacion->tipo }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                                {{ $calificacion->evaluacion->ponderacion }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-xl font-bold {{ $calificacion->nota >= 11 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($calificacion->nota, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-900">
                                                {{ number_format(($calificacion->nota * $calificacion->evaluacion->ponderacion) / 100, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($calificacion->created_at)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $calificacion->observaciones ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Fila de Promedio -->
                                    <tr class="bg-gray-100 font-bold">
                                        <td colspan="4" class="px-6 py-4 text-right text-sm text-gray-900">
                                            PROMEDIO FINAL:
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-2xl font-bold {{ $curso->promedio_final >= 11 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($curso->promedio_final, 2) }}
                                            </span>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($cursos->count() == 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay calificaciones registradas</h3>
                        <p class="mt-1 text-sm text-gray-500">Este estudiante aún no tiene calificaciones en ningún curso.</p>
                    </div>
                </div>
            @endif

            <!-- Botón de Exportar -->
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('calificaciones.historial.pdf', $estudiante->id) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Descargar PDF
                </a>
                <a href="{{ route('calificaciones.historial.excel', $estudiante->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar Excel
                </a>
            </div>
        </div>
    </div>
</x-app-layout>