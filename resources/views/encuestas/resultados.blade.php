<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Resultados de Encuesta') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('encuestas.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
                <button onclick="window.print()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir
                </button>
                <a href="{{ route('encuestas.resultados.pdf', $encuesta) }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información de la Encuesta -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                {{ $encuesta->titulo }}
                            </h3>
                            <p class="text-gray-600 mb-4">{{ $encuesta->descripcion }}</p>
                            
                            <div class="space-y-2">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span class="text-gray-700"><strong>Curso:</strong> {{ $encuesta->curso->nombre }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-700">
                                        <strong>Período:</strong> 
                                        {{ \Carbon\Carbon::parse($encuesta->fecha_inicio)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($encuesta->fecha_fin)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Estadísticas Generales</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600">{{ $totalRespuestas }}</div>
                                    <div class="text-xs text-gray-600">Respuestas</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600">{{ number_format($tasaParticipacion, 1) }}%</div>
                                    <div class="text-xs text-gray-600">Participación</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-purple-600">{{ $totalInscritos }}</div>
                                    <div class="text-xs text-gray-600">Inscritos</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-orange-600">{{ number_format($promedioSatisfaccion, 1) }}</div>
                                    <div class="text-xs text-gray-600">Satisfacción (1-5)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados por Pregunta -->
            <div class="space-y-6">
                @foreach($encuesta->preguntas as $index => $pregunta)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">
                            {{ $index + 1 }}. {{ $pregunta['texto'] }}
                        </h4>

                        @if($pregunta['tipo'] === 'escala')
                            <!-- Resultados de Escala (1-5) -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">Promedio:</span>
                                    <span class="text-lg font-bold text-blue-600">
                                        {{ number_format($resultados[$index]['promedio'] ?? 0, 2) }} / 5
                                    </span>
                                </div>

                                @foreach([5,4,3,2,1] as $valor)
                                    @php
                                        $cantidad = $resultados[$index]['respuestas'][$valor] ?? 0;
                                        $porcentaje = $totalRespuestas > 0 ? ($cantidad / $totalRespuestas * 100) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-700 font-medium">
                                                {{ $valor }} 
                                                @if($valor == 5) ⭐⭐⭐⭐⭐
                                                @elseif($valor == 4) ⭐⭐⭐⭐
                                                @elseif($valor == 3) ⭐⭐⭐
                                                @elseif($valor == 2) ⭐⭐
                                                @else ⭐
                                                @endif
                                            </span>
                                            <span class="text-gray-600">
                                                {{ $cantidad }} ({{ number_format($porcentaje, 1) }}%)
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500" 
                                                 style="width: {{ $porcentaje }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Gráfico de Barras -->
                                <div class="mt-6">
                                    <canvas id="chart-{{ $index }}" height="80"></canvas>
                                </div>
                            </div>

                        @elseif($pregunta['tipo'] === 'opcion_multiple')
                            <!-- Resultados de Opción Múltiple -->
                            <div class="space-y-3">
                                @foreach($pregunta['opciones'] as $opcionIndex => $opcion)
                                    @php
                                        $cantidad = $resultados[$index]['respuestas'][$opcionIndex] ?? 0;
                                        $porcentaje = $totalRespuestas > 0 ? ($cantidad / $totalRespuestas * 100) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-700">{{ $opcion }}</span>
                                            <span class="text-gray-600 font-medium">
                                                {{ $cantidad }} ({{ number_format($porcentaje, 1) }}%)
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all duration-500" 
                                                 style="width: {{ $porcentaje }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Gráfico de Dona -->
                                <div class="mt-6">
                                    <canvas id="chart-multiple-{{ $index }}" height="200"></canvas>
                                </div>
                            </div>

                        @elseif($pregunta['tipo'] === 'texto')
                            <!-- Respuestas de Texto Libre -->
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @forelse($resultados[$index]['respuestas'] ?? [] as $respuesta)
                                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                                        <p class="text-sm text-gray-700">{{ $respuesta['texto'] }}</p>
                                        <div class="flex items-center mt-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $respuesta['estudiante'] }} • 
                                            {{ \Carbon\Carbon::parse($respuesta['fecha'])->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm italic">No hay respuestas de texto para esta pregunta.</p>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Resumen de Satisfacción Global -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen de Satisfacción Global</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Nivel de Satisfacción -->
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg">
                            <div class="text-4xl font-bold mb-2
                                @if($promedioSatisfaccion >= 4.5) text-green-600
                                @elseif($promedioSatisfaccion >= 3.5) text-blue-600
                                @elseif($promedioSatisfaccion >= 2.5) text-yellow-600
                                @else text-red-600
                                @endif">
                                {{ number_format($promedioSatisfaccion, 2) }}
                            </div>
                            <div class="text-sm text-gray-600">Satisfacción Promedio</div>
                            <div class="mt-2">
                                @if($promedioSatisfaccion >= 4.5)
                                    <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-xs font-semibold">Excelente</span>
                                @elseif($promedioSatisfaccion >= 3.5)
                                    <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-xs font-semibold">Bueno</span>
                                @elseif($promedioSatisfaccion >= 2.5)
                                    <span class="px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs font-semibold">Regular</span>
                                @else
                                    <span class="px-3 py-1 bg-red-200 text-red-800 rounded-full text-xs font-semibold">Necesita Mejora</span>
                                @endif
                            </div>
                        </div>

                        <!-- Gráfico de Satisfacción -->
                        <div class="md:col-span-2">
                            <canvas id="chartSatisfaccionGlobal" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comentarios Destacados -->
            @if(isset($comentariosDestacados) && count($comentariosDestacados) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Comentarios Destacados</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($comentariosDestacados as $comentario)
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border-l-4 border-purple-500">
                            <p class="text-sm text-gray-700 italic">"{{ $comentario['texto'] }}"</p>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-gray-600">{{ $comentario['estudiante'] }}</span>
                                <span class="text-xs text-gray-500">{{ $comentario['fecha'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuración global de Chart.js
        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
        Chart.defaults.color = '#6B7280';

        // Gráficos de escala (barras)
        @foreach($encuesta->preguntas as $index => $pregunta)
            @if($pregunta['tipo'] === 'escala')
                new Chart(document.getElementById('chart-{{ $index }}'), {
                    type: 'bar',
                    data: {
                        labels: ['1 ⭐', '2 ⭐⭐', '3 ⭐⭐⭐', '4 ⭐⭐⭐⭐', '5 ⭐⭐⭐⭐⭐'],
                        datasets: [{
                            label: 'Respuestas',
                            data: [
                                {{ $resultados[$index]['respuestas'][1] ?? 0 }},
                                {{ $resultados[$index]['respuestas'][2] ?? 0 }},
                                {{ $resultados[$index]['respuestas'][3] ?? 0 }},
                                {{ $resultados[$index]['respuestas'][4] ?? 0 }},
                                {{ $resultados[$index]['respuestas'][5] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(251, 146, 60, 0.8)',
                                'rgba(234, 179, 8, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(59, 130, 246, 0.8)'
                            ],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            @elseif($pregunta['tipo'] === 'opcion_multiple')
                new Chart(document.getElementById('chart-multiple-{{ $index }}'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($pregunta['opciones']) !!},
                        datasets: [{
                            data: [
                                @foreach($pregunta['opciones'] as $opcionIndex => $opcion)
                                    {{ $resultados[$index]['respuestas'][$opcionIndex] ?? 0 }},
                                @endforeach
                            ],
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(251, 146, 60, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(236, 72, 153, 0.8)',
                                'rgba(234, 179, 8, 0.8)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { padding: 15 }
                            }
                        }
                    }
                });
            @endif
        @endforeach

        // Gráfico de satisfacción global
        new Chart(document.getElementById('chartSatisfaccionGlobal'), {
            type: 'line',
            data: {
                labels: {!! json_encode($datosEvolucion['labels'] ?? ['Inicio', 'Medio', 'Final']) !!},
                datasets: [{
                    label: 'Satisfacción',
                    data: {!! json_encode($datosEvolucion['datos'] ?? [3.5, 4.0, $promedioSatisfaccion]) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>