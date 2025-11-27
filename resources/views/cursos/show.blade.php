<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles del Curso') }}
            </h2>
           <div class="space-x-2">
    @can('cursos.editar')
    <a href="{{ route('sesiones.index', $curso->id) }}" 
       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs !text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        📅 Sesiones
    </a>
    <a href="{{ route('cursos.edit', $curso) }}" 
       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs !text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        ✏️ Editar
    </a>
    @endcan
    <a href="{{ route('cursos.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs !text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
        ← Volver
    </a>
</div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes de éxito/error -->
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Encabezado con Estadísticas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $curso->nombre }}</h3>
                            <p class="text-sm text-gray-600 mt-1">Código: {{ $curso->codigo }}</p>
                        </div>
                        <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full 
                            @if($curso->estado == 'en_curso') bg-green-100 text-green-800
                            @elseif($curso->estado == 'convocatoria') bg-blue-100 text-blue-800
                            @elseif($curso->estado == 'finalizado') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $curso->estado)) }}
                        </span>
                    </div>

                    <!-- Estadísticas Rápidas -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <p class="text-3xl font-bold text-indigo-600">{{ $curso->inscripciones->count() }}</p>
                            <p class="text-xs text-gray-600 mt-1">Inscritos</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-3xl font-bold text-green-600">{{ $curso->horas_academicas }}</p>
                            <p class="text-xs text-gray-600 mt-1">Horas</p>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-3xl font-bold text-blue-600">{{ $curso->cupo_maximo }}</p>
                            <p class="text-xs text-gray-600 mt-1">Cupo Máximo</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-3xl font-bold text-purple-600">S/ {{ number_format($curso->costo_inscripcion, 2) }}</p>
                            <p class="text-xs text-gray-600 mt-1">Costo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información General -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">📋 Información General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Categoría:</p>
                            <p class="font-semibold text-gray-900">{{ $curso->categoria->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Modalidad:</p>
                            <p class="font-semibold text-gray-900">{{ $curso->modalidad->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Inicio:</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Fin:</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Cupo (Mín/Máx):</p>
                            <p class="font-semibold text-gray-900">{{ $curso->cupo_minimo }} / {{ $curso->cupo_maximo }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nota Mínima de Aprobación:</p>
                            <p class="font-semibold text-gray-900">{{ number_format($curso->nota_minima_aprobacion, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Asistencia Mínima:</p>
                            <p class="font-semibold text-gray-900">{{ $curso->asistencia_minima_porcentaje }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nivel:</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst($curso->nivel) ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    @if($curso->descripcion)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Descripción:</p>
                        <p class="text-gray-900">{{ $curso->descripcion }}</p>
                    </div>
                    @endif

                    @if($curso->objetivos)
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Objetivos:</p>
                        <p class="text-gray-900 whitespace-pre-line">{{ $curso->objetivos }}</p>
                    </div>
                    @endif

                    @if($curso->temario)
                    <div class="mt-4 p-4 bg-green-50 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Temario:</p>
                        <pre class="whitespace-pre-wrap text-gray-900 text-sm font-sans">{{ $curso->temario }}</pre>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Docentes Asignados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">👨‍🏫 Docentes Asignados</h3>
                        <button onclick="document.getElementById('modalAsignarDocente').classList.remove('hidden')" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Asignar Docente
                        </button>
                    </div>

                    @if($curso->asignacionesDocentes && $curso->asignacionesDocentes->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($curso->asignacionesDocentes as $asignacion)
                            <div class="border-2 border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mr-3">
                                                {{ substr($asignacion->docente->nombres, 0, 1) }}{{ substr($asignacion->docente->apellidos, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $asignacion->docente->nombres }} {{ $asignacion->docente->apellidos }}</p>
                                                <p class="text-xs text-gray-600">{{ $asignacion->docente->correo_institucional }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-semibold">Tipo:</span> 
                                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                    {{ ucfirst($asignacion->tipo_asignacion) }}
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-semibold">Carga Horaria:</span> {{ $asignacion->carga_horaria }} horas
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Asignado: {{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <form action="{{ route('cursos.desasignar-docente', ['curso' => $curso->id, 'asignacion' => $asignacion->id]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Está seguro de desasignar este docente del curso?')"
                                          class="ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 font-semibold text-sm">
                                            ❌
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500 font-semibold">No hay docentes asignados a este curso</p>
                            <p class="text-sm text-gray-400">Haz clic en "Asignar Docente" para comenzar</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Inscripciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">📚 Inscripciones ({{ $curso->inscripciones->count() }})</h3>
                    @if($curso->inscripciones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($curso->inscripciones as $inscripcion)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $inscripcion->estudiante->nombres }} {{ $inscripcion->estudiante->apellidos }}</div>
                                            <div class="text-sm text-gray-500">{{ $inscripcion->estudiante->correo_institucional }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $inscripcion->estudiante->dni }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $inscripcion->codigo_inscripcion ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($inscripcion->estado == 'confirmada') bg-green-100 text-green-800
                                                @elseif($inscripcion->estado == 'provisional') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($inscripcion->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('inscripciones.show', $inscripcion) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Ver detalles →</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500 font-semibold">No hay inscripciones en este curso</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Modal para Asignar Docente -->
    <div id="modalAsignarDocente" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4 pb-3 border-b">
                <h3 class="text-lg font-bold text-gray-900">➕ Asignar Docente al Curso</h3>
                <button onclick="document.getElementById('modalAsignarDocente').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600 text-2xl font-bold">
                    ×
                </button>
            </div>

            <form action="{{ route('cursos.asignar-docente', $curso->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Seleccionar Docente *
                    </label>
                    <select name="docente_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Seleccione un docente --</option>
                        @foreach(\App\Models\Docente::where('activo', true)->get() as $docente)
                            <option value="{{ $docente->id }}">
                                {{ $docente->nombres }} {{ $docente->apellidos }} ({{ $docente->correo_institucional }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Tipo de Asignación *
                    </label>
                    <select name="tipo_asignacion" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="titular">Titular</option>
                        <option value="asistente">Asistente</option>
                        <option value="invitado">Invitado</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Carga Horaria *
                    </label>
                    <input type="number" name="carga_horaria" value="{{ $curso->horas_academicas }}" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Horas totales del curso: {{ $curso->horas_academicas }}h</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" 
                            onclick="document.getElementById('modalAsignarDocente').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-semibold">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold">
                        ✅ Asignar Docente
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>