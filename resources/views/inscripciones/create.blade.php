<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    ➕ Nueva Inscripción
                </h2>
                <p class="text-sm text-gray-600 mt-1">Registra una nueva inscripción al sistema</p>
            </div>
            <a href="{{ route('inscripciones.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes de error -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-md">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-red-800 font-bold mb-2">¡Atención! Hay errores en el formulario:</h3>
                            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('inscripciones.store') }}" method="POST">
                @csrf

                <!-- Datos de Inscripción -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #9333ea, #7e22ce) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Datos de Inscripción
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            
                            <!-- Estudiante -->
                            <div>
                                <label for="estudiante_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Estudiante <span class="text-red-500">*</span>
                                </label>
                                <select name="estudiante_id" id="estudiante_id" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="">Seleccione un estudiante</option>
                                    @foreach($estudiantes as $estudiante)
                                        <option value="{{ $estudiante->id }}" {{ old('estudiante_id') == $estudiante->id ? 'selected' : '' }}>
                                            {{ $estudiante->dni }} - {{ $estudiante->nombres }} {{ $estudiante->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Seleccione el estudiante que desea inscribir</p>
                            </div>

                            <!-- Curso con información adicional -->
                            <div>
                                <label for="curso_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Curso <span class="text-red-500">*</span>
                                </label>
                                <select name="curso_id" id="curso_id" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition"
                                    onchange="actualizarInfoCurso()">
                                    <option value="">Seleccione un curso</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" 
                                                data-cupos="{{ $curso->cupo_maximo }}"
                                                data-inscritos="{{ $curso->inscripciones_count }}"
                                                data-estado="{{ $curso->estado }}"
                                                data-modalidad="{{ $curso->modalidad->nombre ?? 'N/A' }}"
                                                {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->codigo }} - {{ $curso->nombre }} 
                                            ({{ $curso->inscripciones_count ?? 0 }}/{{ $curso->cupo_maximo }} cupos)
                                        </option>
                                    @endforeach
                                </select>
                                
                                <!-- Información del curso seleccionado -->
                                <div id="info-curso" class="mt-4 hidden">
                                    <div class="rounded-xl shadow-md p-5" style="background: linear-gradient(to bottom right, #ddd6fe, #e9d5ff) !important; border: 2px solid #a855f7;">
                                        <h4 class="text-sm font-bold text-purple-900 mb-3 flex items-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Información del Curso Seleccionado
                                        </h4>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                                <span class="text-gray-600 text-xs font-semibold">Estado:</span>
                                                <div id="curso-estado" class="text-purple-900 font-bold mt-1"></div>
                                            </div>
                                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                                <span class="text-gray-600 text-xs font-semibold">Modalidad:</span>
                                                <div id="curso-modalidad" class="text-purple-900 font-bold mt-1"></div>
                                            </div>
                                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                                <span class="text-gray-600 text-xs font-semibold">Cupos disponibles:</span>
                                                <div id="curso-cupos" class="text-green-700 font-bold text-xl mt-1"></div>
                                            </div>
                                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                                <span class="text-gray-600 text-xs font-semibold">Inscritos:</span>
                                                <div id="curso-inscritos" class="text-purple-900 font-bold mt-1"></div>
                                            </div>
                                        </div>
                                        <div id="alerta-cupos" class="mt-4 hidden">
                                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-lg">
                                                <div class="flex items-start">
                                                    <svg class="h-5 w-5 text-yellow-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                    <div class="text-yellow-800 text-xs">
                                                        <strong>⚠️ Atención:</strong> Este curso tiene pocos cupos disponibles.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="alerta-sin-cupos" class="mt-4 hidden">
                                            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded-lg">
                                                <div class="flex items-start">
                                                    <svg class="h-5 w-5 text-red-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <div class="text-red-800 text-xs">
                                                        <strong>❌ Sin cupos:</strong> El estudiante será agregado a la lista de espera.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha de Inscripción -->
                            <div>
                                <label for="fecha_inscripcion" class="block text-sm font-bold text-gray-700 mb-2">
                                    Fecha de Inscripción
                                </label>
                                <input type="date" name="fecha_inscripcion" id="fecha_inscripcion" 
                                       value="{{ old('fecha_inscripcion', date('Y-m-d')) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                                <p class="mt-1 text-xs text-gray-500">Por defecto se usará la fecha actual</p>
                            </div>

                            <!-- Observaciones -->
                            <div>
                                <label for="observaciones" class="block text-sm font-bold text-gray-700 mb-2">
                                    Observaciones
                                </label>
                                <textarea name="observaciones" id="observaciones" rows="3"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition"
                                    placeholder="Notas adicionales sobre esta inscripción (opcional)">{{ old('observaciones') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Información Importante -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #3b82f6, #2563eb) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información Importante
                        </h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">La inscripción se creará en estado <strong class="text-yellow-700">Provisional</strong></span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">Se generará un <strong>código único</strong> de inscripción automáticamente</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">El estudiante recibirá una <strong>notificación por correo</strong></span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">Si no hay cupos disponibles, se agregará a la <strong>lista de espera</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="p-6 flex justify-between items-center">
                        <a href="{{ route('inscripciones.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-lg font-bold text-sm text-gray-800 uppercase tracking-wide hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2 transition shadow-lg transform hover:scale-105"
                                style="background: linear-gradient(to right, #9333ea, #7e22ce) !important; color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Crear Inscripción
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <!-- JavaScript para mostrar información del curso -->
    <script>
        function actualizarInfoCurso() {
            const select = document.getElementById('curso_id');
            const option = select.options[select.selectedIndex];
            const infoCurso = document.getElementById('info-curso');
            
            if (option.value) {
                // Obtener datos del curso
                const cupos = parseInt(option.dataset.cupos);
                const inscritos = parseInt(option.dataset.inscritos);
                const disponibles = cupos - inscritos;
                const estado = option.dataset.estado;
                const modalidad = option.dataset.modalidad;
                
                // Actualizar información
                document.getElementById('curso-estado').textContent = estado.replace('_', ' ').toUpperCase();
                document.getElementById('curso-modalidad').textContent = modalidad;
                document.getElementById('curso-cupos').textContent = disponibles;
                document.getElementById('curso-inscritos').textContent = inscritos + ' / ' + cupos;
                
                // Mostrar alertas según disponibilidad
                const alertaCupos = document.getElementById('alerta-cupos');
                const alertaSinCupos = document.getElementById('alerta-sin-cupos');
                
                alertaCupos.classList.add('hidden');
                alertaSinCupos.classList.add('hidden');
                
                if (disponibles === 0) {
                    alertaSinCupos.classList.remove('hidden');
                } else if (disponibles <= 3) {
                    alertaCupos.classList.remove('hidden');
                }
                
                // Mostrar panel de información
                infoCurso.classList.remove('hidden');
            } else {
                infoCurso.classList.add('hidden');
            }
        }
        
        // Ejecutar al cargar si hay un curso pre-seleccionado
        document.addEventListener('DOMContentLoaded', function() {
            const cursoSelect = document.getElementById('curso_id');
            if (cursoSelect.value) {
                actualizarInfoCurso();
            }
        });
    </script>
</x-app-layout>