<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Calificar Evaluación
            </h2>
            <a href="{{ route('evaluaciones.index') }}" 
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
            
            <!-- Información de la Evaluación -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Curso -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Curso</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $evaluacion->curso->nombre ?? 'N/A' }}</p>
                        </div>

                        <!-- Título -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Evaluación</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $evaluacion->nombre }}</p>
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tipo</label>
                            <p class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($evaluacion->tipo == 'Final') bg-red-100 text-red-800
                                    @elseif($evaluacion->tipo == 'Parcial') bg-blue-100 text-blue-800
                                    @elseif($evaluacion->tipo == 'Proyecto') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $evaluacion->tipo }}
                                </span>
                            </p>
                        </div>

                        <!-- Ponderación -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Ponderación</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $evaluacion->peso_porcentaje }}%</p>
                        </div>

                        <!-- Fecha -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Fecha</label>
                            <p class="mt-1 text-base text-gray-900">
                                {{ \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') }}
                            </p>
                        </div>

                        <!-- Nota Máxima -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nota Máxima</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $evaluacion->nota_maxima }}</p>
                        </div>

                        <!-- Total Estudiantes -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Total Estudiantes</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $inscripciones->count() }}</p>
                        </div>

                        <!-- Progreso -->
                        <div>
                            <label class="text-sm font-medium text-gray-500">Progreso de Calificación</label>
                            <p class="mt-1 text-lg font-semibold text-blue-600">
                                {{ $calificadas ?? 0 }} / {{ $inscripciones->count() }}
                            </p>
                        </div>
                    </div>

                    @if($evaluacion->descripcion)
                    <div class="mt-4 pt-4 border-t">
                        <label class="text-sm font-medium text-gray-500">Descripción</label>
                        <p class="mt-1 text-gray-700">{{ $evaluacion->descripcion }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Formulario de Calificaciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('evaluaciones.guardar-calificaciones', $evaluacion) }}" method="POST" id="formCalificaciones">
                        @csrf

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        <!-- Barra de Acciones Rápidas -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <strong>Nota máxima:</strong> {{ $evaluacion->nota_maxima }} puntos
                            </div>
                            <div class="space-x-2">
                                <button type="button" onclick="aplicarNotaATodos({{ $evaluacion->nota_maxima }})" 
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition duration-150">
                                    Aprobar Todos ({{ $evaluacion->nota_maxima }})
                                </button>
                                <button type="button" onclick="limpiarNotas()" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition duration-150">
                                    Limpiar Todas
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Estudiantes -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                            Nro
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estudiante
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            DNI
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                            Nota (0 - {{ $evaluacion->nota_maxima }})
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Observaciones
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($inscripciones as $index => $inscripcion)
                                        @php
    $calificacion = $inscripcion->calificacion_actual ?? null;
    $notaActual = $calificacion->nota ?? '';
    $observacionActual = $calificacion->observaciones ?? '';
@endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                            <span class="text-blue-600 font-semibold text-sm">
                                                                {{ substr($inscripcion->estudiante->nombres ?? 'N', 0, 1) }}{{ substr($inscripcion->estudiante->apellidos ?? 'A', 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $inscripcion->estudiante->nombres ?? 'N/A' }} {{ $inscripcion->estudiante->apellidos ?? '' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $inscripcion->estudiante->email ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $inscripcion->estudiante->dni ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="hidden" name="inscripciones[{{ $inscripcion->id }}][inscripcion_id]" value="{{ $inscripcion->id }}">
                                                <input type="hidden" name="inscripciones[{{ $inscripcion->id }}][estudiante_id]" value="{{ $inscripcion->estudiante_id }}">
                                                
                                                <input type="number" 
                                                       name="inscripciones[{{ $inscripcion->id }}][nota]" 
                                                       class="nota-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       min="0" 
                                                       max="{{ $evaluacion->nota_maxima }}" 
                                                       step="0.5"
                                                       value="{{ $notaActual }}"
                                                       placeholder="0.0"
                                                       data-max="{{ $evaluacion->nota_maxima }}"
                                                       onchange="validarNota(this)">
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="text" 
                                                       name="inscripciones[{{ $inscripcion->id }}][observaciones]" 
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       value="{{ $observacionActual }}"
                                                       placeholder="Opcional">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($calificacion)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Calificado
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pendiente
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

                        <!-- Información Adicional -->
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Instrucciones
                            </h4>
                            <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                                <li>Las notas deben estar entre 0 y {{ $evaluacion->nota_maxima }}</li>
                                <li>Puede usar decimales (ej: 15.5)</li>
                                <li>Las observaciones son opcionales</li>
                                <li>Puede calificar de forma parcial y guardar varias veces</li>
                                <li>Los estudiantes verán sus notas cuando la evaluación esté en estado "Calificada"</li>
                            </ul>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-between mt-6 pt-6 border-t">
                            <a href="{{ route('evaluaciones.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-150">
                                Cancelar
                            </a>
                            
                            <div class="space-x-4">
                                <button type="submit" name="accion" value="guardar"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                    </svg>
                                    Guardar Calificaciones
                                </button>
                                
                                <button type="submit" name="accion" value="guardar_finalizar"
                                        onclick="return confirm('¿Está seguro de guardar y finalizar? Los estudiantes podrán ver sus notas.')"
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Guardar y Finalizar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Validar que la nota no exceda el máximo
        function validarNota(input) {
            const max = parseFloat(input.dataset.max);
            const valor = parseFloat(input.value);
            
            if (valor > max) {
                alert(`La nota no puede ser mayor a ${max}`);
                input.value = max;
            }
            
            if (valor < 0) {
                alert('La nota no puede ser negativa');
                input.value = 0;
            }
        }

        // Aplicar nota máxima a todos
        function aplicarNotaATodos(notaMaxima) {
            if (confirm(`¿Desea asignar la nota ${notaMaxima} a todos los estudiantes?`)) {
                const inputs = document.querySelectorAll('.nota-input');
                inputs.forEach(input => {
                    input.value = notaMaxima;
                });
            }
        }

        // Limpiar todas las notas
        function limpiarNotas() {
            if (confirm('¿Está seguro de limpiar todas las notas?')) {
                const inputs = document.querySelectorAll('.nota-input');
                inputs.forEach(input => {
                    input.value = '';
                });
            }
        }

        // Confirmación antes de enviar
        document.getElementById('formCalificaciones').addEventListener('submit', function(e) {
            const notasVacias = document.querySelectorAll('.nota-input[value=""]').length;
            const totalNotas = document.querySelectorAll('.nota-input').length;
            
            if (notasVacias > 0 && notasVacias < totalNotas) {
                const accion = e.submitter.value;
                if (accion === 'guardar_finalizar') {
                    if (!confirm(`Hay ${notasVacias} estudiante(s) sin calificar. ¿Desea continuar?`)) {
                        e.preventDefault();
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>