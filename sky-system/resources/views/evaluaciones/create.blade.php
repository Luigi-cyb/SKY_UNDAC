<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nueva Evaluación
            </h2>
            <a href="{{ route('evaluaciones.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong class="font-bold">¡Hay errores en el formulario!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                <!-- ✅ Panel de Peso Disponible -->
                @if($curso_id)
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-blue-700">
                            <strong>Peso disponible para este curso:</strong> {{ $pesoDisponible }}%
                        </p>
                    </div>
                </div>
                @endif

                <form action="{{ route('evaluaciones.store') }}" method="POST" id="formEvaluacion">
                    @csrf

                    <!-- Información Básica -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                            📋 Información Básica
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Curso -->
                            <div>
                                <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Curso <span class="text-red-500">*</span>
                                </label>
                                <select name="curso_id" id="curso_id" required
                                        class="w-full rounded-md border-gray-300">
                                    <option value="">Seleccione un curso</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ old('curso_id', $curso_id) == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }} - {{ $curso->codigo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo <span class="text-red-500">*</span>
                                </label>
                                <select name="tipo" id="tipo" required
                                        class="w-full rounded-md border-gray-300">
                                    <option value="">Seleccione</option>
                                    <option value="parcial" {{ old('tipo') == 'parcial' ? 'selected' : '' }}>Examen Parcial</option>
                                    <option value="final" {{ old('tipo') == 'final' ? 'selected' : '' }}>Examen Final</option>
                                    <option value="trabajo" {{ old('tipo') == 'trabajo' ? 'selected' : '' }}>Trabajo</option>
                                    <option value="practica" {{ old('tipo') == 'practica' ? 'selected' : '' }}>Práctica</option>
                                    <option value="proyecto" {{ old('tipo') == 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                                </select>
                            </div>

                            <!-- Nombre/Título -->
                            <div class="md:col-span-2">
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Título de la Evaluación <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" required
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej: Examen Parcial 1"
                                       class="w-full rounded-md border-gray-300">
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>
                                <textarea name="descripcion" id="descripcion" rows="3"
                                          placeholder="Descripción de la evaluación..."
                                          class="w-full rounded-md border-gray-300">{{ old('descripcion') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ NUEVO: Configuración de Fechas y Tiempo -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                            📅 Fechas y Duración
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Fecha Disponible -->
                            <div>
                                <label for="fecha_disponible" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Disponible <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="fecha_disponible" id="fecha_disponible" required
                                       value="{{ old('fecha_disponible') }}"
                                       class="w-full rounded-md border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">Cuándo estará disponible la evaluación</p>
                            </div>

                            <!-- Fecha Límite -->
                            <div>
                                <label for="fecha_limite" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Límite <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="fecha_limite" id="fecha_limite" required
                                       value="{{ old('fecha_limite') }}"
                                       class="w-full rounded-md border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">Fecha límite para completar</p>
                            </div>

                            <!-- Duración -->
                            <div>
                                <label for="duracion_minutos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Duración (minutos) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="duracion_minutos" id="duracion_minutos" required
                                       min="5" max="300" step="5"
                                       value="{{ old('duracion_minutos', 60) }}"
                                       placeholder="60"
                                       class="w-full rounded-md border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">Tiempo para completar la evaluación</p>
                            </div>
                        </div>
                    </div>

                    <!-- Calificación -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                            📊 Calificación
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Peso -->
                            <div>
                                <label for="peso_porcentaje" class="block text-sm font-medium text-gray-700 mb-2">
                                    Peso (%) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="peso_porcentaje" id="peso_porcentaje" required
                                       min="1" max="{{ $pesoDisponible }}" step="1"
                                       value="{{ old('peso_porcentaje') }}"
                                       placeholder="30"
                                       class="w-full rounded-md border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">Máximo: {{ $pesoDisponible }}%</p>
                            </div>

                            <!-- Nota Máxima -->
                            <div>
                                <label for="nota_maxima" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nota Máxima <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="nota_maxima" id="nota_maxima" required
                                       min="1" max="20" step="0.01"
                                       value="{{ old('nota_maxima', '20') }}"
                                       class="w-full rounded-md border-gray-300">
                            </div>

                            <!-- Nota Mínima -->
                            <div>
                                <label for="nota_minima_aprobacion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nota Mínima Aprobación
                                </label>
                                <input type="number" name="nota_minima_aprobacion" id="nota_minima_aprobacion"
                                       min="1" max="20" step="0.01"
                                       value="{{ old('nota_minima_aprobacion', '10.5') }}"
                                       class="w-full rounded-md border-gray-300">
                            </div>
                        </div>
                    </div>

                    <!-- ✅ NUEVO: Configuración Avanzada -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                            ⚙️ Configuración Avanzada
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Número de Intentos -->
                            <div>
                                <label for="numero_intentos_permitidos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Número de Intentos Permitidos <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="numero_intentos_permitidos" id="numero_intentos_permitidos" required
                                       min="1" max="5" step="1"
                                       value="{{ old('numero_intentos_permitidos', 1) }}"
                                       class="w-full rounded-md border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">Cuántas veces puede intentar el estudiante</p>
                            </div>

                            <!-- Opciones -->
                            <div class="space-y-3">
                                <!-- Mostrar Respuestas Correctas -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="mostrar_respuestas_correctas" id="mostrar_respuestas_correctas" 
                                           value="1" {{ old('mostrar_respuestas_correctas') ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="mostrar_respuestas_correctas" class="ml-2 block text-sm text-gray-700">
                                        Mostrar respuestas correctas después de finalizar
                                    </label>
                                </div>

                                <!-- Aleatorizar Preguntas -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="aleatorizar_preguntas" id="aleatorizar_preguntas" 
                                           value="1" {{ old('aleatorizar_preguntas') ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="aleatorizar_preguntas" class="ml-2 block text-sm text-gray-700">
                                        Aleatorizar orden de preguntas
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Criterios de Evaluación -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                            📝 Criterios de Evaluación (Opcional)
                        </h3>
                        <textarea name="criterios_evaluacion" id="criterios_evaluacion" rows="4"
                                  placeholder="Describe los criterios de evaluación..."
                                  class="w-full rounded-md border-gray-300">{{ old('criterios_evaluacion') }}</textarea>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-4 pt-6 border-t">
                        <a href="{{ route('evaluaciones.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Crear Evaluación
                        </button>
                    </div>
                </form>

                <!-- ✅ Script de Validación -->
                <script>
                    document.getElementById('formEvaluacion').addEventListener('submit', function(e) {
                        const fechaDisponible = new Date(document.getElementById('fecha_disponible').value);
                        const fechaLimite = new Date(document.getElementById('fecha_limite').value);
                        
                        if (fechaLimite <= fechaDisponible) {
                            e.preventDefault();
                            alert('⚠️ La fecha límite debe ser posterior a la fecha disponible');
                            return false;
                        }
                    });

                    // Actualizar peso disponible al cambiar curso
                    document.getElementById('curso_id').addEventListener('change', function() {
                        const cursoId = this.value;
                        if (cursoId) {
                            window.location.href = `{{ route('evaluaciones.create') }}?curso_id=${cursoId}`;
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>