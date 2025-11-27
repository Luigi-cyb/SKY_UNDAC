<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Estadísticas Generales del Sistema') }}
            </h2>
            <a href="{{ route('reportes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Estadísticas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Cursos</p>
                            <p class="text-2xl font-bold">{{ $estadisticas['total_cursos'] }}</p>
                            <p class="text-xs text-gray-500">{{ $estadisticas['cursos_activos'] }} activos</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Estudiantes</p>
                            <p class="text-2xl font-bold">{{ $estadisticas['total_estudiantes'] }}</p>
                            <p class="text-xs text-gray-500">Activos</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Inscripciones</p>
                            <p class="text-2xl font-bold">{{ $estadisticas['total_inscripciones'] }}</p>
                            <p class="text-xs text-gray-500">{{ $estadisticas['inscripciones_confirmadas'] }} confirmadas</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Certificados</p>
                            <p class="text-2xl font-bold">{{ $estadisticas['total_certificados'] }}</p>
                            <p class="text-xs text-gray-500">Emitidos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ingresos y Docentes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">Ingresos Totales</h3>
                    <p class="text-4xl font-bold text-green-600">S/ {{ number_format($estadisticas['ingresos_totales'], 2) }}</p>
                    <p class="text-sm text-gray-600 mt-2">Por concepto de inscripciones</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">Docentes Activos</h3>
                    <p class="text-4xl font-bold text-indigo-600">{{ $estadisticas['total_docentes'] }}</p>
                    <p class="text-sm text-gray-600 mt-2">Registrados en el sistema</p>
                </div>
            </div>

            <!-- Cursos más Populares -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Top 10 - Cursos Más Populares</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Inscripciones</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cursosMasPopulares as $index => $curso)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $curso->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold">
                                        {{ $curso->inscripciones_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($curso->estado == 'en_curso') bg-green-100 text-green-800
                                            @elseif($curso->estado == 'convocatoria') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $curso->estado)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Inscripciones por Mes (Gráfico simple) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Inscripciones por Mes ({{ date('Y') }})</h3>
                    <div class="space-y-3">
                        @php
                            $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                            $maxInscripciones = $inscripcionesPorMes->max('total') ?: 1;
                        @endphp
                        @foreach($meses as $index => $nombreMes)
                            @php
                                $mesData = $inscripcionesPorMes->firstWhere('mes', $index + 1);
                                $total = $mesData ? $mesData->total : 0;
                                $porcentaje = ($total / $maxInscripciones) * 100;
                            @endphp
                            <div class="flex items-center">
                                <span class="w-12 text-sm text-gray-600">{{ $nombreMes }}</span>
                                <div class="flex-grow bg-gray-200 rounded-full h-6 mx-4">
                                    <div class="bg-blue-500 h-6 rounded-full flex items-center justify-end pr-2" style="width: {{ $porcentaje }}%">
                                        @if($total > 0)
                                            <span class="text-xs text-white font-semibold">{{ $total }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>