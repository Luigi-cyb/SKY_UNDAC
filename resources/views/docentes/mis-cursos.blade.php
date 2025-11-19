<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">
            📚 Mis Cursos Asignados
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(isset($mensaje))
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
                <p class="text-yellow-800">{{ $mensaje }}</p>
            </div>
            @endif

            @if(isset($cursosActivos) && $cursosActivos->isNotEmpty())
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📖 Cursos Activos ({{ $cursosActivos->count() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($cursosActivos as $curso)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border-l-4 border-green-500">
                        <!-- Encabezado del curso -->
                        <div class="bg-gradient-to-r from-green-500 to-teal-600 p-5">
                            <h4 class="text-white font-bold text-lg leading-tight mb-1">{{ $curso->nombre }}</h4>
                            <p class="text-green-100 text-sm font-medium">{{ $curso->codigo }}</p>
                        </div>
                        
                        <!-- Información del curso -->
                        <div class="p-5">
                            <div class="space-y-3 mb-4">
                                <!-- Estado -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Estado:</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($curso->estado == 'en_curso') bg-green-100 text-green-800
                                        @elseif($curso->estado == 'convocatoria') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $curso->estado)) }}
                                    </span>
                                </div>
                                
                                <!-- Estudiantes -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">👥 Estudiantes:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $curso->inscripciones_count ?? 0 }}</span>
                                </div>
                                
                                <!-- Horas académicas -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">⏱️ Horas:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $curso->horas_academicas ?? 0 }}h</span>
                                </div>

                                <!-- Modalidad -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">📍 Modalidad:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $curso->modalidad->nombre ?? 'N/A' }}</span>
                                </div>

                                <!-- Fechas -->
                                <div class="flex items-center justify-between text-xs text-gray-500 pt-2 border-t">
                                    <span>📅 Inicio: {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</span>
                                    <span>🏁 Fin: {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="space-y-2">
                                <!-- Botón principal: Sesiones -->
                                <a href="{{ route('docente.sesiones.index', $curso->id) }}" 
                                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 text-sm font-semibold shadow-sm hover:shadow-md">
                                    📅 Gestionar Sesiones
                                </a>
                                
                                <!-- Botones secundarios -->
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('asistencias.index') }}" 
                                       class="flex items-center justify-center gap-1 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300 text-xs font-semibold">
                                        ✅ Asistencia
                                    </a>
                                    <a href="{{ route('evaluaciones.index') }}" 
                                       class="flex items-center justify-center gap-1 px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-300 text-xs font-semibold">
                                        📝 Evaluaciones
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(isset($cursosFinalizados) && $cursosFinalizados->isNotEmpty())
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">✅ Cursos Finalizados ({{ $cursosFinalizados->count() }})</h3>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="space-y-3">
                        @foreach($cursosFinalizados as $curso)
                        <div class="flex items-center justify-between border-b pb-3 hover:bg-gray-50 p-3 rounded-lg transition">
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-800">{{ $curso->nombre }}</h5>
                                <p class="text-sm text-gray-600">{{ $curso->codigo }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    📅 {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-600">👥 {{ $curso->inscripciones_count ?? 0 }}</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                    Finalizado
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if((!isset($cursosActivos) || $cursosActivos->isEmpty()) && (!isset($cursosFinalizados) || $cursosFinalizados->isEmpty()))
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📚</div>
                    <p class="text-gray-600 text-lg font-semibold">No tienes cursos asignados</p>
                    <p class="text-gray-500 text-sm mt-2">Contacta con administración para que te asignen cursos</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>