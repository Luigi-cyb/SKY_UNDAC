<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $evaluacion->nombre }}
                </h2>
                <p class="text-sm text-gray-600">Intento {{ $intento->numero_intento }} de {{ $evaluacion->numero_intentos_permitidos }}</p>
            </div>
            
            <!-- Timer -->
            <div id="timer-container" class="bg-red-100 border-2 border-red-500 rounded-lg px-6 py-3">
                <div class="text-center">
                    <p class="text-xs text-red-600 font-semibold mb-1">⏰ TIEMPO RESTANTE</p>
                    <div id="timer" class="text-3xl font-bold text-red-700">
                        --:--
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alertas -->
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

            <!-- Información de la Evaluación -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-blue-800 mb-2">Instrucciones:</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Responde todas las preguntas antes de que se agote el tiempo</li>
                            <li>• Puedes guardar respuestas individuales con el botón "Guardar Respuesta"</li>
                            <li>• Al finalizar, presiona "Enviar Evaluación" para obtener tu calificación</li>
                            <li>• Si el tiempo se agota, la evaluación se enviará automáticamente</li>
                            @if($evaluacion->descripcion)
                            <li class="mt-2 font-semibold">{{ $evaluacion->descripcion }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Formulario de Evaluación -->
            <form id="formEvaluacion" method="POST" action="{{ route('estudiantes.evaluacion.finalizar', $intento->id) }}">
                @csrf

                <div class="space-y-6">
                    @foreach($preguntas as $index => $pregunta)
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                        <!-- Header de la Pregunta -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="bg-blue-600 text-white font-bold px-3 py-1 rounded-full text-sm mr-3">
                                        Pregunta {{ $index + 1 }}
                                    </span>
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $pregunta->puntos }} punto(s)
                                    </span>
                                    @if($pregunta->obligatoria)
                                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold ml-2">
                                        Obligatoria
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                    {{ $pregunta->enunciado }}
                                </h3>
                            </div>
                        </div>

                        <!-- Respuestas según tipo -->
                        <div class="ml-4">
                            @if($pregunta->tipo_pregunta === 'multiple')
                                <!-- Opción Múltiple -->
                                <div class="space-y-3">
                                    @foreach($pregunta->opciones()->orderBy('orden')->get() as $opcion)
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                        <input type="radio" 
                                               name="respuestas[{{ $pregunta->id }}][opcion_id]" 
                                               value="{{ $opcion->id }}"
                                               {{ isset($respuestasGuardadas[$pregunta->id]) && $respuestasGuardadas[$pregunta->id]->opcion_id == $opcion->id ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-gray-700">{{ $opcion->texto_opcion }}</span>
                                    </label>
                                    @endforeach
                                </div>

                            @elseif($pregunta->tipo_pregunta === 'verdadero_falso')
                                <!-- Verdadero / Falso -->
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach($pregunta->opciones as $opcion)
                                    <label class="flex items-center justify-center p-4 border-2 rounded-lg hover:bg-gray-50 cursor-pointer transition {{ isset($respuestasGuardadas[$pregunta->id]) && $respuestasGuardadas[$pregunta->id]->opcion_id == $opcion->id ? 'border-blue-500 bg-blue-50' : '' }}">
                                        <input type="radio" 
                                               name="respuestas[{{ $pregunta->id }}][opcion_id]" 
                                               value="{{ $opcion->id }}"
                                               {{ isset($respuestasGuardadas[$pregunta->id]) && $respuestasGuardadas[$pregunta->id]->opcion_id == $opcion->id ? 'checked' : '' }}
                                               class="sr-only">
                                        <span class="text-lg font-bold {{ isset($respuestasGuardadas[$pregunta->id]) && $respuestasGuardadas[$pregunta->id]->opcion_id == $opcion->id ? 'text-blue-600' : 'text-gray-700' }}">
                                            {{ strtoupper($opcion->texto_opcion) }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>

                            @elseif(in_array($pregunta->tipo_pregunta, ['corta', 'respuesta_corta']))
                                <!-- Respuesta Corta -->
                                <div>
                                    <input type="text" 
                                           name="respuestas[{{ $pregunta->id }}][respuesta_texto]" 
                                           value="{{ $respuestasGuardadas[$pregunta->id]->respuesta_texto ?? '' }}"
                                           placeholder="Escribe tu respuesta aquí..."
                                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <p class="text-xs text-gray-500 mt-2">Escribe tu respuesta de forma clara y concisa</p>
                                </div>
                            @endif

                            <!-- Input hidden para el ID de la pregunta -->
                            <input type="hidden" name="respuestas[{{ $pregunta->id }}][pregunta_id]" value="{{ $pregunta->id }}">
                        </div>

                        <!-- Botón Guardar Individual -->
                        <div class="mt-4 pt-4 border-t flex justify-end">
                            <button type="button" 
                                    onclick="guardarRespuestaIndividual({{ $pregunta->id }})"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Guardar Esta Respuesta
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Botón Enviar Evaluación -->
                <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <p class="font-semibold">Total de preguntas: {{ $preguntas->count() }}</p>
                            <p>Puntos totales: {{ $preguntas->sum('puntos') }}</p>
                        </div>
                        <button type="submit" 
                                id="btnEnviar"
                                onclick="return confirm('¿Estás seguro de enviar la evaluación? Una vez enviada no podrás modificar tus respuestas.')"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-4 rounded-lg text-lg inline-flex items-center shadow-lg">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Enviar Evaluación
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Configuración inicial
        let tiempoRestanteSegundos = {{ $tiempoRestanteSegundos }};
        let intentoId = {{ $intento->id }};
        let timerInterval;

        // Función para formatear tiempo
        function formatearTiempo(segundos) {
            const minutos = Math.floor(segundos / 60);
            const segs = segundos % 60;
            return `${minutos.toString().padStart(2, '0')}:${segs.toString().padStart(2, '0')}`;
        }

        // Actualizar timer
        function actualizarTimer() {
            const timerElement = document.getElementById('timer');
            const timerContainer = document.getElementById('timer-container');
            
            timerElement.textContent = formatearTiempo(tiempoRestanteSegundos);
            
            // Cambiar color según tiempo restante
            if (tiempoRestanteSegundos <= 60) {
                timerContainer.classList.remove('bg-red-100', 'border-red-500');
                timerContainer.classList.add('bg-red-600', 'border-red-800', 'animate-pulse');
                timerElement.classList.remove('text-red-700');
                timerElement.classList.add('text-white');
            } else if (tiempoRestanteSegundos <= 300) {
                timerContainer.classList.remove('bg-red-100');
                timerContainer.classList.add('bg-yellow-100', 'border-yellow-500');
            }
            
            tiempoRestanteSegundos--;
            
            // Si el tiempo se agotó, auto-enviar
            if (tiempoRestanteSegundos < 0) {
                clearInterval(timerInterval);
                alert('⏰ El tiempo ha terminado. La evaluación se enviará automáticamente.');
                document.getElementById('formEvaluacion').submit();
            }
        }

        // Iniciar timer
        timerInterval = setInterval(actualizarTimer, 1000);
        actualizarTimer();

        // Guardar respuesta individual (AJAX)
        function guardarRespuestaIndividual(preguntaId) {
            const form = document.getElementById('formEvaluacion');
            const formData = new FormData(form);
            
            // Extraer solo la respuesta de esta pregunta
            const respuestaData = {
                pregunta_id: preguntaId,
                opcion_id: formData.get(`respuestas[${preguntaId}][opcion_id]`),
                respuesta_texto: formData.get(`respuestas[${preguntaId}][respuesta_texto]`)
            };

            fetch(`/estudiantes/evaluaciones/intento/${intentoId}/guardar-respuesta`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(respuestaData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar feedback visual
                    const btn = event.target.closest('button');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Guardado';
                    btn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                    btn.classList.add('bg-green-500');
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('bg-green-500');
                        btn.classList.add('bg-gray-500', 'hover:bg-gray-600');
                    }, 2000);
                } else {
                    alert('Error al guardar la respuesta');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar la respuesta');
            });
        }

        // Advertencia al salir de la página
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '¿Estás seguro de salir? Tu progreso se guardará pero el tiempo seguirá corriendo.';
        });
    </script>
</x-app-layout>