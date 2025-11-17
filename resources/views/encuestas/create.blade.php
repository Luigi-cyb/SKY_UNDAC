<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Nueva Encuesta') }}
            </h2>
            <a href="{{ route('encuestas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('encuestas.store') }}" id="encuestaForm">
                        @csrf

                        <!-- Información Básica -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Título -->
                                <div class="col-span-2">
                                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Título de la Encuesta <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('titulo') border-red-500 @enderror">
                                    @error('titulo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div class="col-span-2">
                                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descripción
                                    </label>
                                    <textarea name="descripcion" id="descripcion" rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Curso -->
                                <div>
                                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Curso
                                    </label>
                                    <select name="curso_id" id="curso_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('curso_id') border-red-500 @enderror">
                                        <option value="">General (Sin curso específico)</option>
                                        @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('curso_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tipo -->
                                <div>
                                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Encuesta <span class="text-red-500">*</span>
                                    </label>
                                    <select name="tipo" id="tipo" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tipo') border-red-500 @enderror">
                                        <option value="">Seleccionar...</option>
                                        <option value="curso" {{ old('tipo') == 'curso' ? 'selected' : '' }}>Curso</option>
                                        <option value="docente" {{ old('tipo') == 'docente' ? 'selected' : '' }}>Docente</option>
                                        <option value="servicios" {{ old('tipo') == 'servicios' ? 'selected' : '' }}>Servicios</option>
                                        <option value="general" {{ old('tipo') == 'general' ? 'selected' : '' }}>General</option>
                                    </select>
                                    @error('tipo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha Inicio -->
                                <div>
                                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha de Inicio
                                    </label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_inicio') border-red-500 @enderror">
                                    @error('fecha_inicio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha Fin -->
                                <div>
                                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha de Fin
                                    </label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_fin') border-red-500 @enderror">
                                    @error('fecha_fin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                        Estado <span class="text-red-500">*</span>
                                    </label>
                                    <select name="estado" id="estado" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('estado') border-red-500 @enderror">
                                        <option value="borrador" {{ old('estado') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                        <option value="activa" {{ old('estado') == 'activa' ? 'selected' : '' }}>Activa</option>
                                        <option value="finalizada" {{ old('estado') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                    </select>
                                    @error('estado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Anónima -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="anonima" id="anonima" value="1" {{ old('anonima') ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="anonima" class="ml-2 block text-sm text-gray-900">
                                        Encuesta Anónima
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Preguntas -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Preguntas</h3>
                                <button type="button" onclick="agregarPregunta()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Agregar Pregunta
                                </button>
                            </div>

                            <div id="preguntasContainer" class="space-y-4">
                                <!-- Las preguntas se agregarán dinámicamente aquí -->
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('encuestas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Crear Encuesta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let preguntaIndex = 0;

        function agregarPregunta() {
            const container = document.getElementById('preguntasContainer');
            const preguntaHtml = `
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50" id="pregunta_${preguntaIndex}">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-md font-semibold text-gray-900">Pregunta ${preguntaIndex + 1}</h4>
                        <button type="button" onclick="eliminarPregunta(${preguntaIndex})" class="text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Texto de la pregunta -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Texto de la Pregunta <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="preguntas[${preguntaIndex}][texto]" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Tipo de pregunta -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Pregunta <span class="text-red-500">*</span>
                            </label>
                            <select name="preguntas[${preguntaIndex}][tipo]" onchange="cambiarTipoPregunta(${preguntaIndex}, this.value)" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccionar...</option>
                                <option value="escala">Escala (1-5)</option>
                                <option value="opcion_multiple">Opción Múltiple</option>
                                <option value="texto_corto">Texto Corto</option>
                                <option value="texto_largo">Texto Largo</option>
                                <option value="si_no">Sí/No</option>
                            </select>
                        </div>

                        <!-- Opciones (solo para opción múltiple) -->
                        <div id="opciones_${preguntaIndex}" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Opciones (separadas por coma)
                            </label>
                            <textarea name="preguntas[${preguntaIndex}][opciones]" rows="2" 
                                placeholder="Opción 1, Opción 2, Opción 3..."
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <!-- Obligatoria -->
                        <div class="flex items-center">
                            <input type="checkbox" name="preguntas[${preguntaIndex}][obligatoria]" value="1"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900">
                                Pregunta Obligatoria
                            </label>
                        </div>

                        <!-- Orden -->
                        <input type="hidden" name="preguntas[${preguntaIndex}][orden]" value="${preguntaIndex + 1}">
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', preguntaHtml);
            preguntaIndex++;
        }

        function eliminarPregunta(index) {
            const pregunta = document.getElementById(`pregunta_${index}`);
            if (pregunta) {
                pregunta.remove();
            }
        }

        function cambiarTipoPregunta(index, tipo) {
            const opcionesDiv = document.getElementById(`opciones_${index}`);
            if (tipo === 'opcion_multiple') {
                opcionesDiv.classList.remove('hidden');
            } else {
                opcionesDiv.classList.add('hidden');
            }
        }

        // Agregar al menos una pregunta al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            agregarPregunta();
        });
    </script>
    @endpush
</x-app-layout>