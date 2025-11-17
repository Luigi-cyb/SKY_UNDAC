<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    📋 Detalles de Inscripción
                </h2>
                <p class="text-sm text-gray-600 mt-1">Código: <span class="font-semibold">{{ $inscripcion->codigo_inscripcion }}</span></p>
            </div>
            <div class="flex space-x-2">
                @can('inscripciones.editar')
                <a href="{{ route('inscripciones.edit', $inscripcion) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:ring-offset-2 transition shadow-lg"
                   style="background: linear-gradient(to right, #f59e0b, #d97706) !important; color: white !important;">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                @endcan
                <a href="{{ route('inscripciones.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información de Inscripción -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                <div class="px-6 py-4" style="background: linear-gradient(to right, #9333ea, #7e22ce) !important;">
                    <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Información de la Inscripción
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Código</p>
                            <p class="text-lg font-bold text-purple-900 font-mono">{{ $inscripcion->codigo_inscripcion }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Fecha de Inscripción</p>
                            <p class="text-lg font-bold text-blue-900">{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-gradient-to-br 
                            @if($inscripcion->estado == 'confirmada') from-green-100 to-green-200 border-green-500
                            @elseif($inscripcion->estado == 'provisional') from-yellow-100 to-yellow-200 border-yellow-500
                            @elseif($inscripcion->estado == 'cancelada') from-red-100 to-red-200 border-red-500
                            @else from-gray-100 to-gray-200 border-gray-500
                            @endif rounded-lg p-4 border-l-4">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Estado</p>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold
                                @if($inscripcion->estado == 'confirmada') bg-green-600 text-white
                                @elseif($inscripcion->estado == 'provisional') bg-yellow-600 text-white
                                @elseif($inscripcion->estado == 'cancelada') bg-red-600 text-white
                                @else bg-gray-600 text-white
                                @endif">
                                <svg class="h-3 w-3 mr-1.5 fill-current" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                {{ ucfirst($inscripcion->estado) }}
                            </span>
                        </div>
                        <div class="bg-gradient-to-br {{ $inscripcion->pago_confirmado ? 'from-green-100 to-green-200 border-green-500' : 'from-orange-100 to-orange-200 border-orange-500' }} rounded-lg p-4 border-l-4">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Estado de Pago</p>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold {{ $inscripcion->pago_confirmado ? 'bg-green-600 text-white' : 'bg-orange-600 text-white' }}">
                                {{ $inscripcion->pago_confirmado ? '✅ Pagado' : '⏳ Pendiente' }}
                            </span>
                        </div>
                    </div>

                    @if($inscripcion->observaciones)
                    <div class="mt-4 bg-gray-50 rounded-lg p-4 border-l-4 border-gray-400">
                        <p class="text-xs text-gray-600 font-semibold mb-2">Observaciones:</p>
                        <p class="text-sm text-gray-800">{{ $inscripcion->observaciones }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Información del Estudiante -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                <div class="px-6 py-4" style="background: linear-gradient(to right, #10b981, #059669) !important;">
                    <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Información del Estudiante
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 h-20 w-20 rounded-full flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669) !important;">
                            <span class="font-bold text-3xl" style="color: white !important;">{{ substr($inscripcion->estudiante->nombres, 0, 1) }}{{ substr($inscripcion->estudiante->apellidos, 0, 1) }}</span>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-xl font-bold text-gray-900">{{ $inscripcion->estudiante->nombres }} {{ $inscripcion->estudiante->apellidos }}</h4>
                            <p class="text-sm text-gray-600">DNI: {{ $inscripcion->estudiante->dni }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-green-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Correo Institucional</p>
                            <p class="text-sm font-bold text-green-900">{{ $inscripcion->estudiante->correo_institucional }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Teléfono</p>
                            <p class="text-sm font-bold text-green-900">{{ $inscripcion->estudiante->telefono ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('estudiantes.show', $inscripcion->estudiante) }}" 
                           class="inline-flex items-center text-green-700 hover:text-green-900 font-semibold text-sm">
                            Ver perfil completo del estudiante
                            <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del Curso -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                <div class="px-6 py-4" style="background: linear-gradient(to right, #3b82f6, #2563eb) !important;">
                    <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Información del Curso
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h4 class="text-xl font-bold text-gray-900">{{ $inscripcion->curso->nombre }}</h4>
                        <p class="text-sm text-gray-600">Código: {{ $inscripcion->curso->codigo }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Modalidad</p>
                            <p class="text-sm font-bold text-blue-900">{{ $inscripcion->curso->modalidad->nombre ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Costo</p>
                            <p class="text-sm font-bold text-blue-900">S/ {{ number_format($inscripcion->curso->costo_inscripcion, 2) }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Duración</p>
                            <p class="text-sm font-bold text-blue-900">{{ $inscripcion->curso->duracion_horas }} horas</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Fecha Inicio</p>
                            <p class="text-sm font-bold text-blue-900">{{ \Carbon\Carbon::parse($inscripcion->curso->fecha_inicio)->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Fecha Fin</p>
                            <p class="text-sm font-bold text-blue-900">{{ \Carbon\Carbon::parse($inscripcion->curso->fecha_fin)->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Estado</p>
                            <p class="text-sm font-bold text-blue-900">{{ ucfirst(str_replace('_', ' ', $inscripcion->curso->estado)) }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('cursos.show', $inscripcion->curso) }}" 
                           class="inline-flex items-center text-blue-700 hover:text-blue-900 font-semibold text-sm">
                            Ver información completa del curso
                            <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Asistencias -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #f59e0b, #d97706) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Asistencias ({{ $inscripcion->asistencias->count() }} sesiones)
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($inscripcion->asistencias->count() > 0)
                            @php
                                $totalSesiones = $inscripcion->asistencias->count();
                                $presentes = $inscripcion->asistencias->whereIn('estado', ['presente', 'tardanza'])->count();
                                $porcentaje = $totalSesiones > 0 ? round(($presentes / $totalSesiones) * 100, 2) : 0;
                            @endphp
                            <div class="mb-6 bg-orange-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700 font-semibold mb-2">Porcentaje de Asistencia</p>
                                <div class="flex items-center">
                                    <div class="flex-grow bg-gray-200 rounded-full h-6 mr-3">
                                        <div class="rounded-full h-6 transition-all duration-500 flex items-center justify-center text-white text-xs font-bold" 
                                             style="width: {{ $porcentaje }}%; background: linear-gradient(to right, #10b981, #059669);">
                                            {{ $porcentaje }}%
                                        </div>
                                    </div>
                                    <span class="text-2xl font-bold text-orange-700">{{ $porcentaje }}%</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">{{ $presentes }} de {{ $totalSesiones }} sesiones</p>
                            </div>
                            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Sesión</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Fecha</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($inscripcion->asistencias as $asistencia)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-semibold">Sesión {{ $asistencia->numero_sesion }}</td>
                                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($asistencia->fecha_sesion)->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                                    @if($asistencia->estado == 'presente') bg-green-100 text-green-800
                                                    @elseif($asistencia->estado == 'tardanza') bg-yellow-100 text-yellow-800
                                                    @elseif($asistencia->estado == 'justificado') bg-blue-100 text-blue-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    <svg class="h-2 w-2 mr-1 fill-current" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3"/>
                                                    </svg>
                                                    {{ ucfirst($asistencia->estado) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">No hay asistencias registradas</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Calificaciones -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #ef4444, #dc2626) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Calificaciones
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($inscripcion->calificaciones->count() > 0)
                            @php
                                $promedio = 0;
                                $pesoTotal = 0;
                                foreach($inscripcion->calificaciones as $calif) {
                                    if($calif->evaluacion->activo) {
                                        $promedio += ($calif->nota * $calif->evaluacion->peso_porcentaje) / 100;
                                        $pesoTotal += $calif->evaluacion->peso_porcentaje;
                                    }
                                }
                                $promedio = round($promedio, 2);
                                $aprobado = $promedio >= ($inscripcion->curso->nota_minima_aprobacion ?? 11);
                            @endphp
                            <div class="mb-6 rounded-xl p-6 text-center" style="background: linear-gradient(to bottom right, {{ $aprobado ? '#dcfce7, #bbf7d0' : '#fee2e2, #fecaca' }}) !important;">
                                <p class="text-sm font-semibold mb-2" style="color: {{ $aprobado ? '#166534' : '#991b1b' }} !important;">Promedio Final</p>
                                <p class="text-5xl font-bold" style="color: {{ $aprobado ? '#15803d' : '#dc2626' }} !important;">{{ $promedio }}</p>
                                <p class="text-xs mt-2" style="color: {{ $aprobado ? '#166534' : '#991b1b' }} !important;">Peso acumulado: {{ $pesoTotal }}%</p>
                                @if($pesoTotal >= 100)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold mt-3 {{ $aprobado ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                        {{ $aprobado ? '✅ APROBADO' : '❌ DESAPROBADO' }}
                                    </span>
                                @endif
                            </div>
                            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Evaluación</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Peso</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Nota</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($inscripcion->calificaciones as $calificacion)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <p class="text-sm font-semibold text-gray-900">{{ $calificacion->evaluacion->nombre }}</p>
                                                <p class="text-xs text-gray-500">{{ ucfirst($calificacion->evaluacion->tipo) }}</p>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold">{{ $calificacion->evaluacion->peso_porcentaje }}%</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center justify-center h-8 w-12 rounded-lg text-sm font-bold {{ $calificacion->nota >= ($inscripcion->curso->nota_minima_aprobacion ?? 11) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $calificacion->nota }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">No hay calificaciones registradas</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>