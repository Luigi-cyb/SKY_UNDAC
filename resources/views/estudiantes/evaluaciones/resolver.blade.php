<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $evaluacion->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Timer y Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $evaluacion->nombre }}</h3>
                            <p class="text-sm opacity-90">{{ $evaluacion->curso->nombre }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold" id="timer">--:--</p>
                            <p class="text-sm opacity-90">Tiempo restante</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preguntas -->
            <form id="form-evaluacion" method="POST" action="{{ route('estudiantes.evaluacion.finalizar', $intento->id) }}">
                @csrf
                
                <div class="space-y-6">
                    @foreach($preguntas as $index => $pregunta)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <!-- Número y Puntos -->
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        Pregunta {{ $index + 1 }}
                                    </h4>
                                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                                        {{ $pregunta->puntos }} puntos
                                    </span>
                                </div>

                                <!-- Enunciado -->
                                <p class="text-gray-800 mb-4">{{ $pregunta->enunciado }}</p>

                                <!-- Opciones según tipo -->
                                @if($pregunta->tipo_pregunta == 'multiple' || $pregunta->tipo_pregunta == 'verdadero_falso')
                                    <div class="space-y-3">
                                        @foreach($pregunta->opciones as $opcion)
                                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                                <input type="radio" 
                                                       name="respuestas[{{ $pregunta->id }}]" 
                                                       value="{{ $opcion->id }}"
                                                       {{ isset($respuestasGuardadas[$pregunta->id]) && $respuestasGuardadas[$pregunta->id]->respuesta_seleccionada == $opcion->id ? 'checked' : '' }}
                                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                       onchange="guardarRespuestaAuto({{ $pregunta->id }}, {{ $opcion->id }})">
                                                <span class="ml-3 text-gray-700">{{ $opcion->texto_opcion }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($pregunta->tipo_pregunta == 'corta')
                                    <input type="text" 
                                           name="respuestas[{{ $pregunta->id }}]" 
                                           value="{{ $respuestasGuardadas[$pregunta->id]->respuesta_texto ?? '' }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Escribe tu respuesta aquí"
                                           onblur="guardarRespuestaTexto({{ $pregunta->id }}, this.value)">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Botón Finalizar -->
                <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <button type="button" 
        onclick="finalizarEvaluacion()"
        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition">
    ✅ Finalizar Evaluación
</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
        // Timer
        const tiempoRestante = {{ $tiempoRestanteSegundos }};
        let segundosRestantes = tiempoRestante;

        function actualizarTimer() {
            const minutos = Math.floor(segundosRestantes / 60);
            const segundos = segundosRestantes % 60;
            document.getElementById('timer').textContent = 
                `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
            
            if (segundosRestantes <= 0) {
                alert('⏰ El tiempo ha finalizado. La evaluación se enviará automáticamente.');
                document.getElementById('form-evaluacion').submit();
            }
            
            segundosRestantes--;
        }

        // Iniciar timer
        actualizarTimer();
        setInterval(actualizarTimer, 1000);

        // Guardar respuesta automáticamente (opcional)
        function guardarRespuestaAuto(preguntaId, opcionId) {
            fetch('{{ route("estudiantes.evaluacion.guardar-respuesta", $intento->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pregunta_id: preguntaId,
                    respuesta_seleccionada: opcionId
                })
            });
        }

        function guardarRespuestaTexto(preguntaId, texto) {
            if (texto.trim() === '') return;
            
            fetch('{{ route("estudiantes.evaluacion.guardar-respuesta", $intento->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pregunta_id: preguntaId,
                    respuesta_texto: texto
                })
            });
        }

        // Prevenir salida accidental
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });

        // Finalizar y redirigir
function finalizarEvaluacion() {
    if (!confirm('¿Estás seguro de finalizar la evaluación? No podrás modificar tus respuestas después.')) {
        return;
    }

    const formData = new FormData(document.getElementById('form-evaluacion'));

    fetch('{{ route("estudiantes.evaluacion.finalizar", $intento->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.data.redirect;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error al finalizar la evaluación');
        console.error(error);
    });
}
    </script>
</x-app-layout>