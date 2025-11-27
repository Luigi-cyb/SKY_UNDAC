<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar Preguntas - {{ $evaluacion->nombre }}
            </h2>
            <a href="{{ route('evaluaciones.index', $evaluacion->curso_id) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver a Evaluaciones
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información de la evaluación -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Curso</p>
                            <p class="font-semibold">{{ $evaluacion->curso->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tipo de Evaluación</p>
                            <p class="font-semibold">{{ ucfirst($evaluacion->tipo) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Peso</p>
                            <p class="font-semibold">{{ $evaluacion->peso_porcentaje }}%</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Total de Preguntas</p>
                            <p class="font-semibold text-2xl text-blue-600">{{ $preguntas->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Puntaje Total</p>
                            <p class="font-semibold text-2xl text-green-600">{{ $preguntas->sum('puntos') }} puntos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Botón para agregar nueva pregunta -->
            <div class="mb-6">
                <button onclick="toggleFormulario()" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                    ➕ Agregar Nueva Pregunta
                </button>
            </div>

            <!-- Formulario para crear/editar pregunta -->
            <div id="formulario-pregunta" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4" id="form-title">Nueva Pregunta</h3>
                    
                    <form id="form-pregunta" method="POST" action="{{ route('evaluaciones.preguntas.store', $evaluacion->id) }}">
                        @csrf
                        <input type="hidden" id="pregunta_id" name="pregunta_id" value="">
                        <input type="hidden" id="form_method" name="_method" value="">

                        <!-- Texto de la pregunta -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Texto de la Pregunta *
                            </label>
                            <textarea name="texto_pregunta" 
                                      id="texto_pregunta"
                                      rows="3" 
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                      required></textarea>
                            @error('texto_pregunta')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo de pregunta -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Tipo de Pregunta *
                            </label>
                            <select name="tipo_pregunta" 
                                    id="tipo_pregunta"
                                    onchange="cambiarTipoPregunta()"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="multiple">Opción Múltiple</option>
                                <option value="verdadero_falso">Verdadero / Falso</option>
                                <option value="corta">Respuesta Corta</option>
                            </select>
                            @error('tipo_pregunta')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Puntos -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Puntos *
                                </label>
                                <input type="number" 
                                       name="puntos" 
                                       id="puntos"
                                       step="0.5" 
                                       min="0.5" 
                                       max="20"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       required>
                                @error('puntos')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Orden -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Orden (opcional)
                                </label>
                                <input type="number" 
                                       name="orden" 
                                       id="orden"
                                       min="1"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                        </div>

                        <!-- Opciones para pregunta múltiple -->
                        <div id="opciones-container" class="hidden mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Opciones de Respuesta
                            </label>
                            
                            <div id="opciones-list">
                                <!-- Las opciones se agregarán dinámicamente con JavaScript -->
                            </div>

                            <button type="button" 
                                    onclick="agregarOpcion()"
                                    class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                ➕ Agregar Opción
                            </button>
                        </div>

                        <!-- Respuesta correcta para pregunta corta -->
                        <div id="respuesta-corta-container" class="hidden mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Respuesta Correcta *
                            </label>
                            <input type="text" 
                                   name="respuesta_corta" 
                                   id="respuesta_corta"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-xs text-gray-500 mt-1">
                                Se comparará sin distinguir mayúsculas/minúsculas
                            </p>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-between">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                💾 Guardar Pregunta
                            </button>
                            <button type="button" 
                                    onclick="cancelarFormulario()"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de preguntas existentes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Preguntas de la Evaluación</h3>

                    @if($preguntas->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p class="text-xl mb-2">📝</p>
                            <p>No hay preguntas agregadas aún</p>
                            <p class="text-sm">Haz clic en "Agregar Nueva Pregunta" para comenzar</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($preguntas as $index => $pregunta)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded mr-2">
                                                    Pregunta {{ $index + 1 }}
                                                </span>
                                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded mr-2">
                                                    {{ ucfirst(str_replace('_', ' ', $pregunta->tipo_pregunta)) }}
                                                </span>
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">
                                                    {{ $pregunta->puntos }} puntos
                                                </span>
                                            </div>
                                            <p class="text-gray-800 font-medium mb-3">
                                                {{ $pregunta->enunciado }}
                                            </p>

                                            <!-- Mostrar opciones si es pregunta múltiple o verdadero/falso -->
                                            @if($pregunta->tipo_pregunta == 'multiple' || $pregunta->tipo_pregunta == 'verdadero_falso')
                                                <div class="ml-4 space-y-1">
                                                    @foreach($pregunta->opciones as $opcion)
                                                        <div class="flex items-center">
                                                            @if($opcion->es_correcta)
                                                                <span class="text-green-600 mr-2">✓</span>
                                                            @else
                                                                <span class="text-gray-400 mr-2">○</span>
                                                            @endif
                                                            <span class="{{ $opcion->es_correcta ? 'text-green-700 font-semibold' : 'text-gray-600' }}">
                                                                {{ $opcion->texto_opcion }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Mostrar respuesta correcta si es pregunta corta -->
                                            @if($pregunta->tipo_pregunta == 'corta')
                                                <div class="ml-4 text-sm text-gray-600">
                                                    <span class="font-semibold">Respuesta correcta:</span> 
                                                    {{ $pregunta->respuesta_correcta }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="flex space-x-2 ml-4">
                                            <button onclick="editarPregunta({{ $pregunta->id }})" 
                                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                ✏️ Editar
                                            </button>
                                            <form method="POST" 
                                                  action="{{ route('preguntas.destroy', $pregunta->id) }}"
                                                  onsubmit="return confirm('¿Está seguro de eliminar esta pregunta?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Botón para previsualizar evaluación -->
                        <div class="mt-6 text-center">
                            <a href="{{ route('evaluaciones.preview', $evaluacion->id) }}" 
                               class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg">
                                👁️ Previsualizar Evaluación
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript -->
    <script>
        let contadorOpciones = 0;
        let preguntaEditando = null;

        function toggleFormulario() {
            const formulario = document.getElementById('formulario-pregunta');
            formulario.classList.toggle('hidden');
            if (!formulario.classList.contains('hidden')) {
                limpiarFormulario();
            }
        }

        function cambiarTipoPregunta() {
            const tipo = document.getElementById('tipo_pregunta').value;
            const opcionesContainer = document.getElementById('opciones-container');
            const respuestaContainer = document.getElementById('respuesta-corta-container');

            // Ocultar todos
            opcionesContainer.classList.add('hidden');
            respuestaContainer.classList.add('hidden');

            // Mostrar según tipo
            if (tipo === 'multiple') {
                opcionesContainer.classList.remove('hidden');
                if (document.getElementById('opciones-list').children.length === 0) {
                    agregarOpcion();
                    agregarOpcion();
                }
            } else if (tipo === 'verdadero_falso') {
                opcionesContainer.classList.remove('hidden');
                crearOpcionesVerdaderoFalso();
            } else if (tipo === 'corta') {
                respuestaContainer.classList.remove('hidden');
            }
        }

        function crearOpcionesVerdaderoFalso() {
            const opcionesList = document.getElementById('opciones-list');
            opcionesList.innerHTML = `
                <div class="flex items-center mb-2">
                    <input type="radio" name="correcta" value="0" required class="mr-2">
                    <input type="text" name="opciones[0][texto]" value="Verdadero" readonly 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                </div>
                <div class="flex items-center mb-2">
                    <input type="radio" name="correcta" value="1" required class="mr-2">
                    <input type="text" name="opciones[1][texto]" value="Falso" readonly 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                </div>
            `;
            contadorOpciones = 2;
        }

        function agregarOpcion() {
            const opcionesList = document.getElementById('opciones-list');
            const index = contadorOpciones;
            
            const opcionDiv = document.createElement('div');
            opcionDiv.className = 'flex items-center mb-2';
            opcionDiv.id = `opcion-${index}`;
            opcionDiv.innerHTML = `
                <input type="radio" name="correcta" value="${index}" required class="mr-2">
                <input type="text" 
                       name="opciones[${index}][texto]" 
                       placeholder="Texto de la opción ${index + 1}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mr-2"
                       required>
                <button type="button" 
                        onclick="eliminarOpcion(${index})"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded">
                    ✕
                </button>
            `;
            
            opcionesList.appendChild(opcionDiv);
            contadorOpciones++;
        }

        function eliminarOpcion(index) {
            const opcion = document.getElementById(`opcion-${index}`);
            if (opcion) {
                opcion.remove();
            }
        }

        function limpiarFormulario() {
            document.getElementById('form-pregunta').reset();
            document.getElementById('pregunta_id').value = '';
            document.getElementById('form_method').value = '';
            document.getElementById('form-title').textContent = 'Nueva Pregunta';
            document.getElementById('opciones-list').innerHTML = '';
            document.getElementById('opciones-container').classList.add('hidden');
            document.getElementById('respuesta-corta-container').classList.add('hidden');
            contadorOpciones = 0;
            preguntaEditando = null;
        }

        function cancelarFormulario() {
            document.getElementById('formulario-pregunta').classList.add('hidden');
            limpiarFormulario();
        }

        function editarPregunta(preguntaId) {
            // Aquí implementarías la lógica para cargar los datos de la pregunta
            // Por ahora, solo mostraremos el formulario
            alert('Función de edición en desarrollo. ID: ' + preguntaId);
        }
    </script>
</x-app-layout>