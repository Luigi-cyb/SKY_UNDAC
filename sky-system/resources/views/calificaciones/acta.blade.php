<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Acta de Calificaciones - {{ $curso->nombre }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('calificaciones.acta.pdf', $curso->id) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('calificaciones.acta.excel', $curso->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Excel
                </a>
                <a href="{{ route('calificaciones.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información del Curso -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="border-b pb-6 mb-6">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">ACTA DE CALIFICACIONES</h1>
                            <p class="text-lg text-gray-600">{{ $curso->nombre }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna Izquierda -->
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-40">Código:</span>
                                <span class="text-gray-900">{{ $curso->codigo }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-40">Modalidad:</span>
                                <span class="text-gray-900">{{ $curso->modalidad->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-40">Categoría:</span>
                                <span class="text-gray-900">{{ $curso->categoria->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-40">Duración:</span>
                                <span class="text-gray-900">{{ $curso->duracion_horas }} horas</span>
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-40">Docente:</span>
                                <span class="text-gray-900">{{ $docentePrincipal->nombres ?? 'N/A' }} {{ $docentePrincipal->apellidos ?? '' }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-40">Fecha Inicio:</span>
                                <span class="text-gray-900">{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-40">Fecha Fin:</span>
                                <span class="text-gray-900">{{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-semibold text-gray-700 w-40">Total Estudiantes:</span>
                                <span class="text-gray-900 font-bold">{{ $inscripciones->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas del Curso -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Promedio del Curso -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Promedio del Curso</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($promedioCurso, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aprobados -->
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
                                <p class="text-2xl font-semibold text-green-600">{{ $aprobados }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desaprobados -->
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
                                <p class="text-2xl font-semibold text-red-600">{{ $desaprobados }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasa de Aprobación -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Tasa Aprobación</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($tasaAprobacion, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Evaluaciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Evaluaciones del Curso</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($evaluaciones as $evaluacion)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $evaluacion->titulo }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $evaluacion->tipo }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $evaluacion->ponderacion }}%
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tabla de Acta de Calificaciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th rowspan="2" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase border">
                                        Nro
                                    </th>
                                    <th rowspan="2" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase border">
                                        Apellidos y Nombres
                                    </th>
                                    <th rowspan="2" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase border">
                                        DNI
                                    </th>
                                    
                                    <!-- Columnas de Evaluaciones -->
                                    <th colspan="{{ $evaluaciones->count() }}" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase border">
                                        Evaluaciones
                                    </th>
                                    
                                    <th rowspan="2" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase border bg-blue-50">
                                        Promedio Final
                                    </th>
                                    <th rowspan="2" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase border">
                                        Estado
                                    </th>
                                </tr>
                                <tr>
                                    @foreach($evaluaciones as $evaluacion)
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-600 border">
                                            {{ substr($evaluacion->titulo, 0, 10) }}<br>
                                            <span class="text-xs text-gray-500">({{ $evaluacion->ponderacion }}%)</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($inscripciones as $index => $inscripcion)
                                    @php
                                        $promedioFinal = 0;
                                        $totalPonderacion = 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-center text-sm font-medium text-gray-900 border">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-900 border">
                                            {{ $inscripcion->estudiante->apellidos }}, {{ $inscripcion->estudiante->nombres }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-900 border">
                                            {{ $inscripcion->estudiante->dni }}
                                        </td>
                                        
                                        <!-- Notas de cada evaluación -->
                                        @foreach($evaluaciones as $evaluacion)
                                            @php
                                                $calificacion = $inscripcion->calificaciones
                                                    ->where('evaluacion_id', $evaluacion->id)
                                                    ->first();
                                                $nota = $calificacion->nota ?? 0;
                                                $notaPonderada = ($nota * $evaluacion->ponderacion) / 100;
                                                $promedioFinal += $notaPonderada;
                                                $totalPonderacion += $evaluacion->ponderacion;
                                            @endphp
                                            <td class="px-3 py-3 text-center text-sm border {{ $nota >= 11 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                                {{ $calificacion ? number_format($nota, 1) : '-' }}
                                            </td>
                                        @endforeach
                                        
                                        <!-- Promedio Final -->
                                        <td class="px-4 py-3 text-center border bg-blue-50">
                                            <span class="text-lg font-bold {{ $promedioFinal >= 11 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($promedioFinal, 2) }}
                                            </span>
                                        </td>
                                        
                                        <!-- Estado -->
                                        <td class="px-4 py-3 text-center text-sm border">
                                            @if($promedioFinal >= 11)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    APROBADO
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    DESAPROBADO
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($inscripciones->count() == 0)
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay estudiantes inscritos</h3>
                            <p class="mt-1 text-sm text-gray-500">Este curso aún no tiene estudiantes inscritos.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Firmas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-12">
                        <!-- Docente -->
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-16">
                                <p class="font-semibold text-gray-900">{{ $docentePrincipal->nombres ?? '' }} {{ $docentePrincipal->apellidos ?? '' }}</p>
                                <p class="text-sm text-gray-600">Docente Responsable</p>
                            </div>
                        </div>

                        <!-- Director -->
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-16">
                                <p class="font-semibold text-gray-900">Director EISC</p>
                                <p class="text-sm text-gray-600">Visto Bueno</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pie de Página -->
                    <div class="mt-12 text-center text-sm text-gray-600">
                        <p>Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                        <p class="mt-2">Universidad Nacional Daniel Alcides Carrión - Escuela de Ingeniería de Sistemas y Computación</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>