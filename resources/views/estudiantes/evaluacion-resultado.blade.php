<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Resultado de Evaluación
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- 🎯 Resumen de Resultados --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold mb-2">{{ $intento->evaluacion->nombre }}</h3>
                        <p class="text-gray-600">{{ $intento->inscripcion->curso->nombre }}</p>
                    </div>

                    {{-- 📊 Tarjetas de Estadísticas --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        {{-- Nota Final --}}
                        <div class="bg-blue-50 p-6 rounded-lg text-center">
                            <div class="text-4xl font-bold text-blue-600">
                                {{ number_format($intento->nota_obtenida, 2) }}
                            </div>
                            <div class="text-sm text-gray-600 mt-2">Nota Final (sobre 20)</div>
                        </div>

                        {{-- Puntos --}}
                        <div class="bg-green-50 p-6 rounded-lg text-center">
                            <div class="text-4xl font-bold text-green-600">
                                {{ $intento->puntos_obtenidos }}/{{ $intento->puntos_totales }}
                            </div>
                            <div class="text-sm text-gray-600 mt-2">Puntos Obtenidos</div>
                        </div>

                        {{-- Aciertos --}}
                        <div class="bg-purple-50 p-6 rounded-lg text-center">
                            <div class="text-4xl font-bold text-purple-600">
                                {{ $preguntasCorrectas }}/{{ $totalPreguntas }}
                            </div>
                            <div class="text-sm text-gray-600 mt-2">Respuestas Correctas</div>
                        </div>

                        {{-- Tiempo --}}
                        <div class="bg-orange-50 p-6 rounded-lg text-center">
                            <div class="text-4xl font-bold text-orange-600">
                                {{ gmdate('H:i:s', $intento->tiempo_total_segundos) }}
                            </div>
                            <div class="text-sm text-gray-600 mt-2">Tiempo Total</div>
                        </div>
                    </div>

                    {{-- 📈 Barra de Progreso --}}
                    <div class="mb-6">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium">Porcentaje de Aciertos</span>
                            <span class="text-sm font-medium">{{ $porcentajeAciertos }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-gradient-to-r from-green-400 to-blue-500 h-4 rounded-full" 
                                 style="width: {{ $porcentajeAciertos }}%"></div>
                        </div>
                    </div>

                    {{-- ✅ Estado de Aprobación --}}
                    @if($intento->nota_obtenida >= $intento->evaluacion->nota_minima_aprobacion)
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">🎉 ¡Felicitaciones! Has aprobado la evaluación</p>
                            <p>Nota mínima requerida: {{ $intento->evaluacion->nota_minima_aprobacion }}</p>
                        </div>
                    @else
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">❌ No has alcanzado la nota mínima</p>
                            <p>Nota mínima requerida: {{ $intento->evaluacion->nota_minima_aprobacion }}</p>
                            @if($intento->numero_intento < $intento->evaluacion->numero_intentos_permitidos)
                                <p class="mt-2">Puedes realizar otro intento ({{ $intento->numero_intento }}/{{ $intento->evaluacion->numero_intentos_permitidos }})</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- 📝 Revisión de Preguntas (si está habilitado) --}}
            @if($intento->evaluacion->mostrar_respuestas_correctas)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-bold mb-4">📝 Revisión de Respuestas</h4>
                        
                        @foreach($intento->evaluacion->preguntas as $index => $pregunta)
                            @php
                                $respuesta = $intento->respuestas->where('pregunta_id', $pregunta->id)->first();
                                $esCorrecta = $respuesta && $respuesta->es_correcta;
                            @endphp
                            
                            <div class="mb-6 p-4 border rounded-lg {{ $esCorrecta ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                {{-- Encabezado --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <span class="font-bold">Pregunta {{ $index + 1 }}</span>
                                        <span class="ml-2 text-sm text-gray-600">({{ $pregunta->puntos }} pts)</span>
                                    </div>
                                    <div>
                                        @if($esCorrecta)
                                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                                                ✓ Correcta
                                            </span>
                                        @else
                                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm">
                                                ✗ Incorrecta
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Enunciado --}}
                                <p class="mb-3">{{ $pregunta->enunciado }}</p>

                                {{-- Respuestas según tipo --}}
                                @if($pregunta->tipo_pregunta === 'respuesta_corta')
                                    <div class="space-y-2">
                                        <div class="bg-white p-2 rounded">
                                            <strong>Tu respuesta:</strong> {{ $respuesta->respuesta_texto ?? 'Sin responder' }}
                                        </div>
                                        <div class="bg-green-100 p-2 rounded">
                                            <strong>Respuesta correcta:</strong> {{ $pregunta->respuesta_correcta }}
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        @foreach($pregunta->opciones as $opcion)
                                            @php
                                                $fueSeleccionada = $respuesta && $respuesta->opcion_id === $opcion->id;
                                            @endphp
                                            <div class="p-2 rounded {{ $opcion->es_correcta ? 'bg-green-100' : ($fueSeleccionada ? 'bg-red-100' : 'bg-gray-50') }}">
                                                <label class="flex items-center">
                                                    <input type="radio" disabled {{ $fueSeleccionada ? 'checked' : '' }}>
                                                    <span class="ml-2">{{ $opcion->texto_opcion }}</span>
                                                    @if($opcion->es_correcta)
                                                        <span class="ml-2 text-green-600">✓ Correcta</span>
                                                    @endif
                                                    @if($fueSeleccionada && !$opcion->es_correcta)
                                                        <span class="ml-2 text-red-600">✗ Tu respuesta</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 🔙 Botones de Acción --}}
            <div class="flex justify-between">
                <a href="{{ route('estudiantes.curso.detalle', $intento->inscripcion->curso_id) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    ← Volver al Curso
                </a>
                
                @if($intento->numero_intento < $intento->evaluacion->numero_intentos_permitidos && $intento->nota_obtenida < $intento->evaluacion->nota_minima_aprobacion)
                    <form action="{{ route('estudiantes.evaluacion.iniciar', $intento->evaluacion_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                            🔄 Realizar Nuevo Intento
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
