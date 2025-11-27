<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Sesiones del Curso: {{ $curso->nombre }}
                </h2>
                <p class="text-sm text-gray-600">{{ $curso->codigo }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('sesiones.create', $curso->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Sesión
                </a>
                <a href="{{ route('cursos.show', $curso->id) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Volver al Curso
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

<!-- ✅ NUEVO: Panel de Control de Horas Académicas -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">
            📊 Control de Horas Académicas del Curso
        </h3>
        <a href="{{ route('sesiones.create', $curso->id) }}" 
           class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Programar nueva sesión
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600 mb-1">Horas Totales del Curso</p>
            <p class="text-3xl font-bold text-blue-600">{{ $horasInfo['horas_totales'] }}h</p>
            <p class="text-xs text-gray-500 mt-1">{{ $horasInfo['minutos_totales'] }} minutos</p>
        </div>
        
        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-600 mb-1">Horas Programadas</p>
            <p class="text-3xl font-bold text-green-600">{{ $horasInfo['horas_usadas'] }}h</p>
            <p class="text-xs text-gray-500 mt-1">{{ $horasInfo['minutos_usados'] }} minutos</p>
        </div>
        
        <div class="bg-orange-50 rounded-lg p-4 border-l-4 border-orange-500">
            <p class="text-sm text-gray-600 mb-1">Horas Disponibles</p>
            <p class="text-3xl font-bold text-orange-600">{{ $horasInfo['horas_disponibles'] }}h</p>
            <p class="text-xs text-gray-500 mt-1">{{ $horasInfo['minutos_disponibles'] }} minutos</p>
        </div>

        <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
            <p class="text-sm text-gray-600 mb-1">Progreso</p>
            <p class="text-3xl font-bold text-purple-600">{{ $horasInfo['porcentaje_usado'] }}%</p>
            <p class="text-xs text-gray-500 mt-1">del curso programado</p>
        </div>
    </div>

    <!-- Barra de Progreso Visual -->
    <div class="mb-3">
        <div class="flex justify-between text-sm text-gray-600 mb-2">
            <span class="font-medium">Progreso de programación de sesiones</span>
            <span class="font-semibold">
                {{ $horasInfo['horas_usadas'] }}h / {{ $horasInfo['horas_totales'] }}h
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden shadow-inner">
            <div class="h-full rounded-full transition-all duration-500 flex items-center justify-end px-2
                        {{ $horasInfo['porcentaje_usado'] >= 100 ? 'bg-red-600' : 
                           ($horasInfo['porcentaje_usado'] >= 90 ? 'bg-red-500' : 
                           ($horasInfo['porcentaje_usado'] >= 70 ? 'bg-yellow-500' : 'bg-green-500')) }}"
                 style="width: {{ min($horasInfo['porcentaje_usado'], 100) }}%">
                @if($horasInfo['porcentaje_usado'] > 10)
                    <span class="text-xs font-bold text-white">{{ $horasInfo['porcentaje_usado'] }}%</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Mensajes de Alerta Condicionales -->
    @if($horasInfo['porcentaje_usado'] >= 100)
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-bold">🚫 Límite alcanzado</p>
                    <p class="text-sm">Has programado todas las horas del curso. No puedes crear más sesiones.</p>
                </div>
            </div>
        </div>
    @elseif($horasInfo['porcentaje_usado'] >= 90)
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-bold">⚠️ Atención - Casi sin horas</p>
                    <p class="text-sm">Solo te quedan {{ $horasInfo['horas_disponibles'] }}h disponibles ({{ $horasInfo['minutos_disponibles'] }} minutos).</p>
                </div>
            </div>
        </div>
    @elseif($horasInfo['porcentaje_usado'] >= 70)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-bold">ℹ️ Aviso</p>
                    <p class="text-sm">Has utilizado el {{ $horasInfo['porcentaje_usado'] }}% de las horas. Quedan {{ $horasInfo['horas_disponibles'] }}h disponibles.</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-bold">✅ Espacio disponible</p>
                    <p class="text-sm">Tienes {{ $horasInfo['horas_disponibles'] }}h disponibles para programar más sesiones.</p>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Estadísticas rápidas -->

            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Sesiones</p>
                            <p class="text-2xl font-bold">{{ $sesiones->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Programadas</p>
                            <p class="text-2xl font-bold">{{ $sesiones->where('estado', 'programada')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Finalizadas</p>
                            <p class="text-2xl font-bold">{{ $sesiones->where('estado', 'finalizada')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-full">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">En Vivo</p>
                            <p class="text-2xl font-bold">{{ $sesiones->where('estado', 'en_vivo')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Sesiones -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($sesiones->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($sesiones as $sesion)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="text-2xl font-bold text-gray-400 mr-4">#{{ $sesion->numero_sesion }}</span>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $sesion->titulo }}</h3>
                                        
                                        <span class="ml-3 px-3 py-1 rounded-full text-xs font-semibold bg-{{ $sesion->getEstadoColor() }}-100 text-{{ $sesion->getEstadoColor() }}-800">
                                            {{ $sesion->getEstadoTexto() }}
                                        </span>

                                        @if(!$sesion->visible)
                                        <span class="ml-2 px-2 py-1 rounded text-xs font-semibold bg-gray-200 text-gray-600">
                                            Oculto
                                        </span>
                                        @endif
                                    </div>

                                    @if($sesion->descripcion)
                                    <p class="text-sm text-gray-600 mb-3">{{ $sesion->descripcion }}</p>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
                                        </div>

                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                                        </div>

                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ ucfirst($sesion->plataforma_vivo) }}
                                        </div>

                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $sesion->permite_asistencia ? 'Permite asistencia' : 'No permite asistencia' }}
                                        </div>
                                    </div>

                                    @if($sesion->enlace_clase_vivo || $sesion->enlace_grabacion)
                                    <div class="mt-3 flex gap-2">
                                        @if($sesion->enlace_clase_vivo)
                                        <a href="{{ $sesion->enlace_clase_vivo }}" target="_blank"
                                           class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path>
                                            </svg>
                                            Enlace en vivo
                                        </a>
                                        @endif

                                        @if($sesion->enlace_grabacion)
                                        <a href="{{ $sesion->enlace_grabacion }}" target="_blank"
                                           class="text-xs text-green-600 hover:text-green-800 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path>
                                            </svg>
                                            Ver grabación
                                        </a>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                               <div class="flex flex-col gap-2 ml-4">
    <!-- Botones de Control de Sesión -->
    <div class="flex gap-2">
        @if($sesion->estado === 'programada')
            <!-- Botón Iniciar Sesión -->
            <form action="{{ route('docente.sesiones.iniciar', $sesion->id) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm inline-flex items-center"
                        onclick="return confirm('¿Iniciar la sesión? Se activará la asistencia por 15 minutos.')">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Iniciar
                </button>
            </form>
        @elseif($sesion->estado === 'en_vivo')
            <!-- Botón Finalizar Sesión -->
            <form action="{{ route('docente.sesiones.finalizar', $sesion->id) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm inline-flex items-center"
                        onclick="return confirm('¿Finalizar la sesión? Se cerrará la ventana de asistencia.')">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    Finalizar
                </button>
            </form>
            
            <!-- Indicador de Sesión en Vivo -->
            <span class="bg-red-100 text-red-800 px-3 py-2 rounded text-sm font-semibold inline-flex items-center">
                <span class="animate-pulse inline-block w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                EN VIVO
            </span>
        @elseif($sesion->estado === 'finalizada')
            <!-- Badge Finalizada -->
            <span class="bg-gray-100 text-gray-600 px-3 py-2 rounded text-sm font-semibold">
                ✓ Finalizada
            </span>
        @endif
    </div>

    <!-- ⭐⭐⭐ NUEVO: Botón Ver Asistencias ⭐⭐⭐ -->
    @if($sesion->estado === 'en_vivo' || $sesion->estado === 'finalizada')
        <a href="{{ route('docente.sesiones.asistencias', $sesion->id) }}" 
           class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 rounded text-sm inline-flex items-center justify-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            Ver Asistencias
        </a>
    @endif

    <!-- Botones de Editar y Eliminar -->
    <div class="flex gap-2">
        <a href="{{ route('sesiones.edit', $sesion->id) }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm">
            Editar
        </a>
        
        @if($sesion->estado !== 'en_vivo')
            <form action="{{ route('sesiones.destroy', $sesion->id) }}" method="POST" 
                  onsubmit="return confirm('¿Estás seguro de eliminar esta sesión?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">
                    Eliminar
                </button>
            </form>
        @else
            <button type="button" 
                    disabled
                    class="bg-gray-300 text-gray-500 px-3 py-2 rounded text-sm cursor-not-allowed"
                    title="No puedes eliminar una sesión en vivo">
                Eliminar
            </button>
        @endif
    </div>

    <!-- Información de Asistencia (si está activa) -->
    @if($sesion->permite_asistencia && $sesion->fecha_fin_asistencia)
        @php
            $ahora = now();
            $finAsistencia = \Carbon\Carbon::parse($sesion->fecha_fin_asistencia);
            $minutosRestantes = $ahora->diffInMinutes($finAsistencia, false);
        @endphp
        
        @if($minutosRestantes > 0)
            <div class="bg-green-50 border border-green-200 rounded px-3 py-2 text-xs">
                <p class="text-green-700 font-semibold">⏱️ Asistencia abierta</p>
                <p class="text-green-600">Cierra en {{ $minutosRestantes }} minutos</p>
            </div>
        @elseif($sesion->estado === 'en_vivo')
            <div class="bg-yellow-50 border border-yellow-200 rounded px-3 py-2 text-xs">
                <p class="text-yellow-700 font-semibold">⚠️ Ventana cerrada</p>
                <p class="text-yellow-600">Ya no se puede marcar asistencia</p>
            </div>
        @endif
    @endif
</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay sesiones</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza creando la primera sesión del curso</p>
                        <div class="mt-6">
                            <a href="{{ route('sesiones.create', $curso->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nueva Sesión
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>