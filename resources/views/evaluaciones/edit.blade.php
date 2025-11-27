<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Evaluación
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('evaluaciones.update', $evaluacion->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Sección: Información Básica -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                                Información Básica
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Curso -->
                                <div class="md:col-span-2">
                                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Curso <span class="text-red-500">*</span>
                                    </label>
                                    <select name="curso_id" id="curso_id" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('curso_id') border-red-500 @enderror">
                                        <option value="">Seleccione un curso</option>
                                        @foreach($cursos as $curso)
                                            <option value="{{ $curso->id }}" 
                                                    {{ old('curso_id', $evaluacion->curso_id) == $curso->id ? 'selected' : '' }}>
                                                {{ $curso->nombre }} - {{ $curso->codigo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('curso_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Título -->
                                <div class="md:col-span-2">
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                        Título de la Evaluación <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nombre" id="nombre" required
                                           value="{{ old('nombre', $evaluacion->nombre) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nombre') border-red-500 @enderror"
                                           placeholder="Ej: Examen Parcial de Laravel">
                                    @error('nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tipo -->
                                <div>
                                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Evaluación <span class="text-red-500">*</span>
                                    </label>
                                    <select name="tipo" id="tipo" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tipo') border-red-500 @enderror">
                                        <option value="parcial" {{ old('tipo', $evaluacion->tipo) == 'parcial' ? 'selected' : '' }}>Examen Parcial</option>
                                        <option value="final" {{ old('tipo', $evaluacion->tipo) == 'final' ? 'selected' : '' }}>Examen Final</option>
                                        <option value="trabajo" {{ old('tipo', $evaluacion->tipo) == 'trabajo' ? 'selected' : '' }}>Trabajo</option>
                                        <option value="practica" {{ old('tipo', $evaluacion->tipo) == 'practica' ? 'selected' : '' }}>Práctica</option>
                                        <option value="proyecto" {{ old('tipo', $evaluacion->tipo) == 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                                    </select>
                                    @error('tipo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Peso Porcentaje -->
                                <div>
                                    <label for="peso_porcentaje" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ponderación (%) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="peso_porcentaje" id="peso_porcentaje" required
                                           min="1" max="100" step="1"
                                           value="{{ old('peso_porcentaje', $evaluacion->peso_porcentaje) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('peso_porcentaje') border-red-500 @enderror">
                                    @error('peso_porcentaje')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Peso disponible: {{ $pesoDisponible }}%</p>
                                </div>

                                <!-- Descripción -->
                                <div class="md:col-span-2">
                                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descripción
                                    </label>
                                    <textarea name="descripcion" id="descripcion" rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('descripcion') border-red-500 @enderror"
                                              placeholder="Descripción breve de la evaluación">{{ old('descripcion', $evaluacion->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Configuración de Fechas -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                                Configuración de Fechas y Tiempo
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Fecha Disponible -->
                                <div>
                                    <label for="fecha_disponible" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha Disponible <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="fecha_disponible" id="fecha_disponible" required
                                           value="{{ old('fecha_disponible', $evaluacion->fecha_disponible ? date('Y-m-d\TH:i', strtotime($evaluacion->fecha_disponible)) : '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_disponible') border-red-500 @enderror">
                                    @error('fecha_disponible')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Desde cuándo los estudiantes pueden iniciar</p>
                                </div>

                                <!-- Fecha Límite -->
                                <div>
                                    <label for="fecha_limite" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha Límite <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="fecha_limite" id="fecha_limite" required
                                           value="{{ old('fecha_limite', $evaluacion->fecha_limite ? date('Y-m-d\TH:i', strtotime($evaluacion->fecha_limite)) : '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_limite') border-red-500 @enderror">
                                    @error('fecha_limite')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Fecha máxima para completar la evaluación</p>
                                </div>

                                <!-- Duración en Minutos -->
                                <div>
                                    <label for="duracion_minutos" class="block text-sm font-medium text-gray-700 mb-2">
                                        Duración (minutos) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="duracion_minutos" id="duracion_minutos" required
                                           min="5" max="300" step="5"
                                           value="{{ old('duracion_minutos', $evaluacion->duracion_minutos) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('duracion_minutos') border-red-500 @enderror">
                                    @error('duracion_minutos')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Tiempo límite para resolver</p>
                                </div>

                                <!-- Número de Intentos -->
                                <div>
                                    <label for="numero_intentos_permitidos" class="block text-sm font-medium text-gray-700 mb-2">
                                        Intentos Permitidos <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="numero_intentos_permitidos" id="numero_intentos_permitidos" required
                                           min="1" max="5" step="1"
                                           value="{{ old('numero_intentos_permitidos', $evaluacion->numero_intentos_permitidos) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('numero_intentos_permitidos') border-red-500 @enderror">
                                    @error('numero_intentos_permitidos')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Número máximo de intentos</p>
                                </div>

                                <!-- Fecha de Evaluación (opcional) -->
                                <div>
                                    <label for="fecha_evaluacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha de Evaluación (opcional)
                                    </label>
                                    <input type="date" name="fecha_evaluacion" id="fecha_evaluacion"
                                           value="{{ old('fecha_evaluacion', $evaluacion->fecha_evaluacion) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_evaluacion') border-red-500 @enderror">
                                    @error('fecha_evaluacion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Calificación -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                                Configuración de Calificación
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nota Máxima -->
                                <div>
                                    <label for="nota_maxima" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nota Máxima <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="nota_maxima" id="nota_maxima" required
                                           min="1" max="20" step="0.5"
                                           value="{{ old('nota_maxima', $evaluacion->nota_maxima) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nota_maxima') border-red-500 @enderror">
                                    @error('nota_maxima')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nota Mínima de Aprobación -->
                                <div>
                                    <label for="nota_minima_aprobacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nota Mínima de Aprobación
                                    </label>
                                    <input type="number" name="nota_minima_aprobacion" id="nota_minima_aprobacion"
                                           min="0" max="20" step="0.5"
                                           value="{{ old('nota_minima_aprobacion', $evaluacion->nota_minima_aprobacion) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nota_minima_aprobacion') border-red-500 @enderror">
                                    @error('nota_minima_aprobacion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Criterios de Evaluación -->
                                <div class="md:col-span-2">
                                    <label for="criterios_evaluacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Criterios de Evaluación
                                    </label>
                                    <textarea name="criterios_evaluacion" id="criterios_evaluacion" rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Especifique los criterios de evaluación">{{ old('criterios_evaluacion', $evaluacion->criterios_evaluacion) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Opciones Adicionales -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                                Opciones Adicionales
                            </h3>

                            <div class="space-y-4">
                                <!-- Mostrar Respuestas Correctas -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="mostrar_respuestas_correctas" id="mostrar_respuestas_correctas" 
                                           value="1"
                                           {{ old('mostrar_respuestas_correctas', $evaluacion->mostrar_respuestas_correctas) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <label for="mostrar_respuestas_correctas" class="ml-2 text-sm text-gray-700">
                                        Mostrar respuestas correctas después de finalizar
                                    </label>
                                </div>

                                <!-- Aleatorizar Preguntas -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="aleatorizar_preguntas" id="aleatorizar_preguntas" 
                                           value="1"
                                           {{ old('aleatorizar_preguntas', $evaluacion->aleatorizar_preguntas) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <label for="aleatorizar_preguntas" class="ml-2 text-sm text-gray-700">
                                        Aleatorizar orden de las preguntas
                                    </label>
                                </div>

                                <!-- Requiere Aprobar -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="requiere_aprobar" id="requiere_aprobar" 
                                           value="1"
                                           {{ old('requiere_aprobar', $evaluacion->requiere_aprobar) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <label for="requiere_aprobar" class="ml-2 text-sm text-gray-700">
                                        Es requisito aprobar esta evaluación
                                    </label>
                                </div>

                                <!-- Activo -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="activo" id="activo" 
                                           value="1"
                                           {{ old('activo', $evaluacion->activo) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <label for="activo" class="ml-2 text-sm text-gray-700">
                                        Evaluación activa
                                    </label>
                                </div>

                                <!-- Instrucciones -->
                                <div>
                                    <label for="instrucciones" class="block text-sm font-medium text-gray-700 mb-2">
                                        Instrucciones para el estudiante
                                    </label>
                                    <textarea name="instrucciones" id="instrucciones" rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Instrucciones especiales que verá el estudiante antes de iniciar">{{ old('instrucciones', $evaluacion->instrucciones) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('evaluaciones.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-150">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150">
                                💾 Actualizar Evaluación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>