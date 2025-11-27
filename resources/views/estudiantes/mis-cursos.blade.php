<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Cursos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
            @endif

            <!-- Botón para ver cursos disponibles -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="text-white">
                            <h3 class="text-xl font-bold">¿Buscas más cursos?</h3>
                            <p class="text-blue-100 text-sm">Explora y matricúlate en cursos disponibles sin costo inicial</p>
                        </div>
                    </div>
                    <a href="{{ route('estudiantes.cursos-disponibles') }}" 
                       class="bg-white hover:bg-gray-100 text-blue-600 font-bold py-3 px-6 rounded-lg transition shadow-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Explorar Cursos
                    </a>
                </div>
            </div>

            <!-- Panel de Sesiones con Asistencia Disponible -->
            @if(isset($sesionesDisponibles) && $sesionesDisponibles->count() > 0)
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h3 class="text-xl font-bold">⏰ Sesiones con Asistencia Disponible HOY</h3>
                        <p class="text-green-100 text-sm">Marca tu asistencia antes de que se cierre la ventana</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($sesionesDisponibles as $sesion)
                    <div class="bg-white rounded-lg p-4 shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold mr-3">
                                        Sesión #{{ $sesion->numero_sesion }}
                                    </span>
                                    <h4 class="font-bold text-gray-900">{{ $sesion->titulo }}</h4>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-2">{{ $sesion->curso->nombre }}</p>
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                                    </div>
                                    
                                    @if($sesion->minutos_restantes > 0)
                                    <div class="flex items-center text-orange-600 font-semibold">
                                        <svg class="w-4 h-4 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Cierra en {{ $sesion->minutos_restantes }} minutos
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="ml-4">
                                @if($sesion->asistencia_marcada)
                                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-bold text-sm inline-flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        ✓ Asistencia Registrada
                                    </div>
                                @else
                                    <form action="{{ route('estudiantes.marcar-asistencia', $sesion->id) }}" method="POST" 
                                          onsubmit="return confirm('¿Confirmar tu asistencia a esta sesión?')">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition inline-flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Marcar Asistencia
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Estadísticas -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full mr-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-medium">Cursos Inscritos</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $inscripciones->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full mr-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-medium">En Curso</p>
                                <p class="text-2xl font-bold text-green-600">{{ $inscripciones->where('estado', 'confirmada')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full mr-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-medium">Promedio General</p>
                                <p class="text-2xl font-bold text-purple-600">{{ number_format($promedioGeneral ?? 0, 1) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full mr-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-medium">Asistencia</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ number_format($porcentajeAsistencia ?? 0, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Cursos -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Cursos Actuales</h3>

                    @if($inscripciones->count() > 0)
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($inscripciones as $inscripcion)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900">{{ $inscripcion->curso->nombre }}</h4>
                                        <p class="text-sm text-gray-600">{{ $inscripcion->curso->codigo }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $inscripcion->estado == 'confirmada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($inscripcion->estado) }}
                                    </span>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($inscripcion->curso->fecha_inicio)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($inscripcion->curso->fecha_fin)->format('d/m/Y') }}
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $inscripcion->curso->modalidad->nombre ?? 'N/A' }}
                                    </div>

                                    <!-- ⭐ NUEVO: Información de aprobación y pago -->
                                    @if($inscripcion->nota_final !== null && $inscripcion->porcentaje_asistencia !== null)
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                                <div class="flex items-center">
                                                    <span class="font-semibold text-gray-700 mr-2">Nota:</span>
                                                    <span class="px-2 py-1 rounded text-xs font-bold
                                                        {{ $inscripcion->nota_final >= $inscripcion->curso->nota_minima_aprobacion ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ number_format($inscripcion->nota_final, 1) }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="font-semibold text-gray-700 mr-2">Asistencia:</span>
                                                    <span class="px-2 py-1 rounded text-xs font-bold
                                                        {{ $inscripcion->porcentaje_asistencia >= $inscripcion->curso->asistencia_minima_porcentaje ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $inscripcion->porcentaje_asistencia }}%
                                                    </span>
                                                </div>
                                            </div>

                                            @php
                                                $cumpleRequisitos = $inscripcion->nota_final >= $inscripcion->curso->nota_minima_aprobacion 
                                                                 && $inscripcion->porcentaje_asistencia >= $inscripcion->curso->asistencia_minima_porcentaje;
                                            @endphp

                                            @if($cumpleRequisitos)
                                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                                    <div class="flex items-center text-green-800 mb-2">
                                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="font-bold text-sm">¡Felicitaciones! Has aprobado</span>
                                                    </div>

                                                    @if(!$inscripcion->pago_confirmado)
                                                        @php
                                                            $pagosPendientes = $inscripcion->pagos()->where('estado', 'pendiente')->count();
                                                            $pagoRechazado = $inscripcion->pagos()->where('estado', 'rechazado')->latest()->first();
                                                        @endphp

                                                        @if($pagosPendientes > 0)
                                                            <div class="flex items-center justify-between bg-yellow-50 p-2 rounded border border-yellow-200 mt-2">
                                                                <div class="flex items-center text-yellow-800">
                                                                    <svg class="w-4 h-4 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <span class="font-semibold text-xs">Pago en revisión</span>
                                                                </div>
                                                                <span class="text-yellow-700 text-xs">⏳ Espera confirmación</span>
                                                            </div>
                                                        @else
                                                            <p class="text-xs text-green-700 mb-2">
                                                                Paga <span class="font-bold">S/. {{ number_format($inscripcion->curso->costo_inscripcion, 2) }}</span> para tu certificado
                                                            </p>
                                                            <a href="{{ route('estudiantes.pago.mostrar', $inscripcion->id) }}" 
                                                               class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-lg transition shadow">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                                </svg>
                                                                💳 Pagar Ahora
                                                            </a>

                                                            @if($pagoRechazado)
                                                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs">
                                                                    <p class="text-red-800">
                                                                        <strong>Pago rechazado:</strong> {{ $pagoRechazado->motivo_rechazo ?? 'Intenta nuevamente' }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <div class="flex items-center text-blue-700 mt-2">
                                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="font-bold text-sm">✅ Certificado disponible</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                                    <div class="flex items-center text-red-800">
                                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="font-bold text-sm">No cumple requisitos</span>
                                                    </div>
                                                    <p class="text-xs text-red-700 mt-1">
                                                        Nota ≥ {{ $inscripcion->curso->nota_minima_aprobacion }} y Asistencia ≥ {{ $inscripcion->curso->asistencia_minima_porcentaje }}%
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="border-t pt-4">
                                    <a href="{{ route('estudiantes.curso.detalle', $inscripcion->curso->id) }}" 
                                       class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No estás inscrito en ningún curso</h3>
                            <p class="mt-1 text-sm text-gray-500">Explora los cursos disponibles y matricúlate</p>
                            <div class="mt-6">
                                <a href="{{ route('estudiantes.cursos-disponibles') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Ver Cursos Disponibles
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>