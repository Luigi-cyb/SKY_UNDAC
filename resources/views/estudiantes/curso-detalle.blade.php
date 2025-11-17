<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $curso->nombre }}
            </h2>
            <a href="{{ route('estudiantes.mis-cursos') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver a Mis Cursos
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

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <!-- Información del Curso -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Código</p>
                        <p class="text-lg font-semibold">{{ $curso->codigo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Modalidad</p>
                        <p class="text-lg font-semibold">{{ $curso->modalidad->nombre ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Periodo</p>
                        <p class="text-lg font-semibold">
                            {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Estado</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            {{ ucfirst($curso->estado) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600">{{ number_format($promedioFinal, 1) }}</div>
                        <p class="text-sm text-gray-600 mt-2">Promedio Final</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-green-600">{{ number_format($porcentajeAsistencia, 1) }}%</div>
                        <p class="text-sm text-gray-600 mt-2">Asistencia</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-purple-600">{{ $evaluaciones->count() }}</div>
                        <p class="text-sm text-gray-600 mt-2">Evaluaciones</p>
                    </div>
                </div>
            </div>

            <!-- SESIONES DEL CURSO (NUEVO) -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📅 Horario de Clases</h3>

                @php
                    $sesiones = \App\Models\SesionCurso::where('curso_id', $curso->id)
                        ->where('visible', true)
                        ->orderBy('numero_sesion')
                        ->get();
                @endphp

                @if($sesiones->count() > 0)
                    <div class="space-y-4">
                        @foreach($sesiones as $sesion)
                        <div class="border rounded-lg p-4 {{ $sesion->estaEnVivo() ? 'border-green-500 bg-green-50' : '' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h4 class="text-lg font-bold text-gray-900">{{ $sesion->titulo }}</h4>
                                        
                                        @if($sesion->estaEnVivo())
                                        <span class="ml-3 px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white animate-pulse">
                                            🔴 EN VIVO
                                        </span>
                                        @else
                                        <span class="ml-3 px-3 py-1 rounded-full text-xs font-semibold bg-{{ $sesion->getEstadoColor() }}-100 text-{{ $sesion->getEstadoColor() }}-800">
                                            {{ $sesion->getEstadoTexto() }}
                                        </span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ ucfirst($sesion->plataforma_vivo) }}
                                        </div>
                                    </div>

                                    @if($sesion->descripcion)
                                    <p class="text-sm text-gray-700 mb-3">{{ $sesion->descripcion }}</p>
                                    @endif

                                    <!-- Enlaces -->
                                    <div class="flex flex-wrap gap-2">
                                        @if($sesion->enlace_clase_vivo && ($sesion->estaEnVivo() || $sesion->estado === 'programada'))
                                        <a href="{{ $sesion->enlace_clase_vivo }}" target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                                            </svg>
                                            {{ $sesion->estaEnVivo() ? 'Unirse a Clase EN VIVO' : 'Ver Enlace de Clase' }}
                                        </a>
                                        @endif

                                        @if($sesion->enlace_grabacion)
                                        <a href="{{ $sesion->enlace_grabacion }}" target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                            </svg>
                                            Ver Grabación
                                        </a>
                                        @endif

                                        @if($sesion->puedeMarcarAsistencia())
                                        <form action="{{ route('estudiantes.marcar-asistencia', $sesion->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Marcar Asistencia
                                            </button>
                                        </form>
                                        @endif
                                    </div>

                                    <!-- Materiales de la Sesión -->
                                    @php
                                        $materialesSesion = \App\Models\MaterialCurso::where('curso_id', $curso->id)
                                            ->where('numero_sesion', $sesion->numero_sesion)
                                            ->where('visible', true)
                                            ->get();
                                    @endphp

                                    @if($materialesSesion->count() > 0)
                                    <div class="mt-4 pt-4 border-t">
                                        <h5 class="text-sm font-semibold text-gray-700 mb-2">📎 Materiales:</h5>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            @foreach($materialesSesion as $material)
                                            <a href="{{ $material->enlace_externo ?? route('materiales.descargar', $material->id) }}" 
                                               target="{{ $material->enlace_externo ? '_blank' : '_self' }}"
                                               class="flex items-center p-2 bg-gray-50 hover:bg-gray-100 rounded border">
                                                @if($material->tipo === 'video')
                                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                                </svg>
                                                @elseif($material->tipo === 'presentacion')
                                                <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                                </svg>
                                                @else
                                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                                @endif
                                                <span class="text-sm text-gray-700 truncate">{{ $material->titulo }}</span>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No hay sesiones programadas aún</p>
                @endif
            </div>
            <!-- ✅ NUEVO: EVALUACIONES DISPONIBLES -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Evaluaciones Disponibles</h3>

                @php
                    $ahora = now();
                    $evaluacionesActivas = $evaluaciones->where('activo', true);
                @endphp

                @if($evaluacionesActivas->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($evaluacionesActivas as $evaluacion)
                        @php
                            // Calcular estado de la evaluación
                            $fechaDisponible = $evaluacion->fecha_disponible ? \Carbon\Carbon::parse($evaluacion->fecha_disponible) : null;
                            $fechaLimite = $evaluacion->fecha_limite ? \Carbon\Carbon::parse($evaluacion->fecha_limite) : null;
                            
                            // Contar intentos usados
                            $intentosUsados = \App\Models\IntentoEvaluacion::where('evaluacion_id', $evaluacion->id)
                                ->where('inscripcion_id', $inscripcion->id)
                                ->count();
                            
                            // Obtener último intento
                            $ultimoIntento = \App\Models\IntentoEvaluacion::where('evaluacion_id', $evaluacion->id)
                                ->where('inscripcion_id', $inscripcion->id)
                                ->orderBy('created_at', 'desc')
                                ->first();
                            
                            // Determinar estado
                            $estado = 'no_disponible';
                            $mensajeEstado = 'No disponible aún';
                            $colorEstado = 'gray';
                            $puedeIniciar = false;
                            
                            if ($fechaDisponible && $fechaLimite) {
                                if ($ahora->lt($fechaDisponible)) {
                                    $estado = 'no_disponible';
                                    $mensajeEstado = 'Disponible desde ' . $fechaDisponible->format('d/m/Y H:i');
                                    $colorEstado = 'gray';
                                } elseif ($ahora->gt($fechaLimite)) {
                                    $estado = 'vencida';
                                    $mensajeEstado = 'Vencida';
                                    $colorEstado = 'red';
                                } elseif ($intentosUsados >= $evaluacion->numero_intentos_permitidos) {
                                    $estado = 'completada';
                                    $mensajeEstado = 'Completada';
                                    $colorEstado = 'blue';
                                } elseif ($ultimoIntento && $ultimoIntento->estado === 'en_progreso') {
                                    $estado = 'en_progreso';
                                    $mensajeEstado = 'En progreso';
                                    $colorEstado = 'yellow';
                                    $puedeIniciar = true; // Puede continuar
                                } else {
                                    $estado = 'disponible';
                                    $mensajeEstado = 'Disponible';
                                    $colorEstado = 'green';
                                    $puedeIniciar = true;
                                }
                            }
                            
                            // Calcular tiempo restante si hay un intento en progreso
                            $tiempoRestante = null;
                            if ($ultimoIntento && $ultimoIntento->estado === 'en_progreso') {
                                $fechaInicio = \Carbon\Carbon::parse($ultimoIntento->fecha_inicio);
                                $duracionTotal = $evaluacion->duracion_minutos;
                                $tiempoTranscurrido = $ahora->diffInMinutes($fechaInicio);
                                $tiempoRestante = max(0, $duracionTotal - $tiempoTranscurrido);
                            }
                        @endphp

                        <div class="border rounded-lg p-5 hover:shadow-lg transition {{ $estado === 'disponible' ? 'border-green-300 bg-green-50' : ($estado === 'en_progreso' ? 'border-yellow-300 bg-yellow-50' : '') }}">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $evaluacion->nombre }}</h4>
                                    <p class="text-sm text-gray-600">{{ ucfirst($evaluacion->tipo) }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-{{ $colorEstado }}-100 text-{{ $colorEstado }}-800">
                                    {{ $mensajeEstado }}
                                </span>
                            </div>

                            <!-- Descripción -->
                            @if($evaluacion->descripcion)
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($evaluacion->descripcion, 100) }}</p>
                            @endif

                            <!-- Información -->
                            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $evaluacion->duracion_minutos }} minutos</span>
                                </div>
                                
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span>{{ $evaluacion->peso_porcentaje }}% del curso</span>
                                </div>

                                @if($fechaDisponible)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $fechaDisponible->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif

                                @if($fechaLimite)
                                <div class="flex items-center {{ $ahora->gt($fechaLimite) ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Límite: {{ $fechaLimite->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Intentos -->
                            <div class="mb-4 p-3 bg-gray-100 rounded">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Intentos:</span>
                                    <span class="font-semibold {{ $intentosUsados >= $evaluacion->numero_intentos_permitidos ? 'text-red-600' : 'text-blue-600' }}">
                                        {{ $intentosUsados }} / {{ $evaluacion->numero_intentos_permitidos }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tiempo restante si está en progreso -->
                            @if($tiempoRestante !== null && $tiempoRestante > 0)
                            <div class="mb-4 p-3 bg-yellow-100 border border-yellow-300 rounded">
                                <div class="flex items-center justify-center text-yellow-800">
                                    <svg class="w-5 h-5 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-bold">⏰ Tiempo restante: {{ $tiempoRestante }} minutos</span>
                                </div>
                            </div>
                            @endif

                            <!-- Botones de Acción -->
                            <div class="mt-4">
                                @if($puedeIniciar && $estado === 'disponible')
                                    <form action="{{ route('estudiantes.evaluacion.iniciar', $evaluacion->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('¿Estás listo para iniciar la evaluación? El tiempo comenzará a correr una vez que presiones OK.')"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg inline-flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Iniciar Evaluación
                                        </button>
                                    </form>
                                @elseif($puedeIniciar && $estado === 'en_progreso')
                                    <a href="{{ route('estudiantes.evaluacion.resolver', $ultimoIntento->id) }}"
                                       class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded-lg inline-flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        Continuar Evaluación
                                    </a>
                                @elseif($estado === 'completada')
                                    <a href="{{ route('estudiantes.evaluacion.resultado', $ultimoIntento->id) }}"
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg inline-flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Ver Resultado
                                    </a>
                                @elseif($estado === 'vencida')
                                    <button disabled
                                            class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                                        ❌ Evaluación Vencida
                                    </button>
                                @else
                                    <button disabled
                                            class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                                        🔒 No disponible aún
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No hay evaluaciones disponibles en este momento</p>
                    </div>
                @endif
            </div>

            <!-- Calificaciones -->

            <!-- Calificaciones -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Mis Calificaciones</h3>

                @if($evaluaciones->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Peso</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nota</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($evaluaciones as $evaluacion)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $evaluacion->nombre }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $evaluacion->tipo == 'parcial' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $evaluacion->tipo == 'final' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $evaluacion->tipo == 'trabajo' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($evaluacion->tipo) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-semibold">
                                        {{ $evaluacion->peso_porcentaje }}%
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($evaluacion->calificaciones->count() > 0)
                                            @php
                                                $calificacion = $evaluacion->calificaciones->first();
                                            @endphp
                                            <span class="text-2xl font-bold {{ $calificacion->nota >= 10.5 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($calificacion->nota, 1) }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">Sin calificar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($evaluacion->calificaciones->count() > 0)
                                            @php
                                                $calificacion = $evaluacion->calificaciones->first();
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $calificacion->nota >= 10.5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $calificacion->nota >= 10.5 ? 'Aprobado' : 'Desaprobado' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
    @if($evaluacion->calificaciones->count() > 0)
        {{ $evaluacion->calificaciones->first()->observaciones ?? '-' }}
    @else
        -
    @endif
</td>
<td class="px-6 py-4 text-center">
    @if($evaluacion->calificaciones->count() > 0)
        @php
            $ultimoIntento = \App\Models\IntentoEvaluacion::where('evaluacion_id', $evaluacion->id)
                ->where('inscripcion_id', $inscripcion->id)
                ->where('estado', 'finalizado')
                ->orderBy('created_at', 'desc')
                ->first();
        @endphp
        @if($ultimoIntento)
            <a href="{{ route('estudiantes.evaluacion.resultado', $ultimoIntento->id) }}"
               class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded text-xs">
                👁️ Ver Resultado
            </a>
        @endif
    @else
        <span class="text-xs text-gray-400">-</span>
    @endif
</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No hay evaluaciones registradas</p>
                @endif
            </div>

            <!-- Asistencias (RESUMEN) -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">✅ Resumen de Asistencia</h3>
                    <div class="text-sm text-gray-600">
                        <strong>{{ $asistenciasPresente }}</strong> de <strong>{{ $totalAsistencias }}</strong> sesiones
                    </div>
                </div>

                @if($asistencias->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($asistencias->groupBy('estado') as $estado => $grupo)
                        <div class="text-center p-4 rounded-lg {{ $estado === 'presente' ? 'bg-green-50' : ($estado === 'ausente' ? 'bg-red-50' : 'bg-yellow-50') }}">
                            <div class="text-2xl font-bold {{ $estado === 'presente' ? 'text-green-600' : ($estado === 'ausente' ? 'text-red-600' : 'text-yellow-600') }}">
                                {{ $grupo->count() }}
                            </div>
                            <div class="text-sm text-gray-600 capitalize">{{ $estado }}</div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No hay asistencias registradas</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>