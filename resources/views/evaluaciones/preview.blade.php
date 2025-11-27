<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Vista Previa - {{ $evaluacion->nombre }}
            </h2>
            <a href="{{ route('evaluaciones.preguntas', $evaluacion->id) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver a Editar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Encabezado de la evaluación -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <h1 class="text-3xl font-bold mb-2">{{ $evaluacion->nombre }}</h1>
                    <p class="text-blue-100">{{ $evaluacion->curso->nombre }}</p>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white bg-opacity-20 rounded p-3">
                            <p class="text-sm">Tipo</p>
                            <p class="font-bold">{{ ucfirst($evaluacion->tipo) }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded p-3">
                            <p class="text-sm">Total Preguntas</p>
                            <p class="font-bold">{{ $preguntas->count() }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded p-3">
                            <p class="text-sm">Puntaje Total</p>
                            <p class="font-bold">{{ $puntajeTotal }} puntos</p>
                        </div>
                    </div>
                    
                    @if($evaluacion->duracion_minutos)
                        <div class="mt-4 bg-yellow-400 text-yellow-900 rounded p-3">
                            <p class="font-semibold">⏱️ Tiempo límite: {{ $evaluacion->duracion_minutos }} minutos</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Instrucciones -->
            @if($evaluacion->instrucciones)
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                    <h3 class="font-bold text-blue-900 mb-2">📋 Instrucciones</h3>
                    <p class="text-blue-800">{{ $evaluacion->instrucciones }}</p>
                </div>
            @endif

            <!-- Preguntas -->
            <div class="space-y-6">
                @foreach($preguntas as $index => $pregunta)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Encabezado de pregunta -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                                        Pregunta {{ $index + 1 }}
                                    </span>
                                    <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded ml-2">
                                        {{ $pregunta->puntos }} {{ $pregunta->puntos == 1 ? 'punto' : 'puntos' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Texto de la pregunta -->
                            <p class="text-lg text-gray-800 font-medium mb-4">
                                {{ $pregunta->enunciado }}
                            </p>

                            <!-- Opciones según tipo de pregunta -->
                            @if($pregunta->tipo_pregunta == 'multiple')
                                <div class="space-y-2">
                                    @foreach($pregunta->opciones as $opcion)
                                        <label class="flex items-center p-3 border rounded hover:bg-gray-50 cursor-pointer transition">
                                            <input type="radio" 
                                                   name="pregunta_{{ $pregunta->id }}" 
                                                   class="mr-3"
                                                   disabled>
                                            <span class="text-gray-700">{{ $opcion->texto_opcion }}</span>
                                            @if($opcion->es_correcta)
                                                <span class="ml-auto text-green-600 font-semibold">(Correcta)</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($pregunta->tipo_pregunta == 'verdadero_falso')
                                <div class="space-y-2">
                                    @foreach($pregunta->opciones as $opcion)
                                        <label class="flex items-center p-3 border rounded hover:bg-gray-50 cursor-pointer transition">
                                            <input type="radio" 
                                                   name="pregunta_{{ $pregunta->id }}" 
                                                   class="mr-3"
                                                   disabled>
                                            <span class="text-gray-700 font-medium">{{ $opcion->texto_opcion }}</span>
                                            @if($opcion->es_correcta)
                                                <span class="ml-auto text-green-600 font-semibold">(Correcta)</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($pregunta->tipo_pregunta == 'corta' || $pregunta->tipo_pregunta == 'respuesta_corta')
                                <div>
                                    <input type="text" 
                                           class="w-full border rounded p-3 text-gray-700"
                                           placeholder="Escribe tu respuesta aquí..."
                                           disabled>
                                    <p class="mt-2 text-sm text-green-600">
                                        <span class="font-semibold">Respuesta correcta:</span> {{ $pregunta->respuesta_correcta }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Resumen final -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-gray-50">
                    <h3 class="font-bold text-lg mb-4">📊 Resumen de la Evaluación</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-white rounded shadow">
                            <p class="text-3xl font-bold text-blue-600">{{ $preguntas->count() }}</p>
                            <p class="text-sm text-gray-600">Preguntas</p>
                        </div>
                        <div class="text-center p-4 bg-white rounded shadow">
                            <p class="text-3xl font-bold text-green-600">{{ $puntajeTotal }}</p>
                            <p class="text-sm text-gray-600">Puntos Totales</p>
                        </div>
                        <div class="text-center p-4 bg-white rounded shadow">
                            <p class="text-3xl font-bold text-purple-600">
                                {{ $preguntas->where('tipo_pregunta', 'multiple')->count() }}
                            </p>
                            <p class="text-sm text-gray-600">Opción Múltiple</p>
                        </div>
                        <div class="text-center p-4 bg-white rounded shadow">
                            <p class="text-3xl font-bold text-orange-600">
                                {{ $preguntas->whereIn('tipo_pregunta', ['corta', 'respuesta_corta', 'verdadero_falso'])->count() }}
                            </p>
                            <p class="text-sm text-gray-600">Otras</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones finales -->
            <div class="mt-6 flex justify-center space-x-4">
                <a href="{{ route('evaluaciones.preguntas', $evaluacion->id) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                    ✏️ Editar Preguntas
                </a>
                <a href="{{ route('evaluaciones.show', $evaluacion->id) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                    ✅ Finalizar y Volver
                </a>
            </div>

        </div>
    </div>
</x-app-layout>