<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte de Asistencia por Curso') }}
            </h2>
            <a href="{{ route('asistencias.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtro por Curso -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Seleccionar Curso</h3>
                    <form method="GET" action="{{ route('asistencias.reporte') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-2">Curso</label>
                            <select name="curso_id" id="curso_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Seleccione un curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Generar Reporte
    </button>
    {{-- Exportaciones desactivadas temporalmente
    @if(request('curso_id'))
    <button type="button" onclick="exportarPDF()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
        Exportar PDF
    </button>
    <button type="button" onclick="exportarExcel()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Exportar Excel
    </button>
    @endif
    --}}
</div>
                    </form>
                </div>
            </div>

            @if(request('curso_id') && $cursoSeleccionado)
            <!-- Información del Curso -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Información del Curso</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nombre</p>
                            <p class="font-semibold">{{ $cursoSeleccionado->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Código</p>
                            <p class="font-semibold">{{ $cursoSeleccionado->codigo }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Docente</p>
                            <p class="font-semibold">{{ $cursoSeleccionado->docente->nombres ?? 'No asignado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Sesiones</p>
                            <p class="font-semibold">{{ $totalSesiones }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                 <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Estudiantes</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalEstudiantes }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">% Asistencia Promedio</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($porcentajeAsistenciaPromedio, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Estudiantes en Riesgo</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $estudiantesEnRiesgo }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Tardanzas</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalTardanzas }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Asistencia -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Gráfico de Asistencia por Sesión</h3>
                    <canvas id="asistenciaChart" height="80"></canvas>
                </div>
            </div>

            <!-- Tabla Detallada de Asistencia -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Detalle de Asistencia por Estudiante</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante
                                    </th>
                                    @for($i = 1; $i <= $totalSesiones; $i++)
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        S{{ $i }}
                                    </th>
                                    @endfor
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Presentes
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ausentes
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tardanzas
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        % Asistencia
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reporteDetallado as $reporte)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $reporte['estudiante']->nombres }} {{ $reporte['estudiante']->apellidos }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            DNI: {{ $reporte['estudiante']->dni }}
                                        </div>
                                    </td>
                                    @for($i = 1; $i <= $totalSesiones; $i++)
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        @if(isset($reporte['sesiones'][$i]))
                                            @if($reporte['sesiones'][$i] === 'presente')
                                                <span class="text-green-600 font-bold" title="Presente">✓</span>
                                            @elseif($reporte['sesiones'][$i] === 'ausente')
                                                <span class="text-red-600 font-bold" title="Ausente">✗</span>
                                            @elseif($reporte['sesiones'][$i] === 'tardanza')
                                                <span class="text-yellow-600 font-bold" title="Tardanza">T</span>
                                            @else
                                                <span class="text-blue-600 font-bold" title="Justificado">J</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    @endfor
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ $reporte['presentes'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ $reporte['ausentes'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ $reporte['tardanzas'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $reporte['porcentaje'] >= 80 ? 'bg-green-100 text-green-800' : 
                                               ($reporte['porcentaje'] >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ number_format($reporte['porcentaje'], 1) }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($reporte['porcentaje'] >= 80)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aprobado
                                            </span>
                                        @elseif($reporte['porcentaje'] >= 70)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En Observación
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                En Riesgo
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Leyenda -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Leyenda</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="flex items-center">
                            <span class="text-green-600 font-bold mr-2">✓</span>
                            <span class="text-sm">Presente</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-red-600 font-bold mr-2">✗</span>
                            <span class="text-sm">Ausente</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-600 font-bold mr-2">T</span>
                            <span class="text-sm">Tardanza</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-blue-600 font-bold mr-2">J</span>
                            <span class="text-sm">Justificado</span>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <p><strong>Nota:</strong> Un estudiante está en riesgo si su porcentaje de asistencia es menor al 70%.</p>
                        <p><strong>Requisito mínimo:</strong> 80% de asistencia para aprobar el curso.</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    Seleccione un curso para generar el reporte de asistencia
                </div>
            </div>
            @endif

        </div>
    </div>

    @if(request('curso_id'))
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos para el gráfico
        const sesiones = @json($datosSesiones['sesiones']);
        const presentes = @json($datosSesiones['presentes']);
        const ausentes = @json($datosSesiones['ausentes']);
        const tardanzas = @json($datosSesiones['tardanzas']);

        // Crear gráfico
        const ctx = document.getElementById('asistenciaChart').getContext('2d');
        const asistenciaChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: sesiones,
                datasets: [
                    {
                        label: 'Presentes',
                        data: presentes,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Ausentes',
                        data: ausentes,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Tardanzas',
                        data: tardanzas,
                        borderColor: 'rgb(245, 158, 11)',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Función para exportar a PDF
        function exportarPDF() {
            const cursoId = document.getElementById('curso_id').value;
            window.location.href = `/reportes/asistencia/pdf?curso_id=${cursoId}`;
        }

        // Función para exportar a Excel
        function exportarExcel() {
            const cursoId = document.getElementById('curso_id').value;
            window.location.href = `/reportes/asistencia/excel?curso_id=${cursoId}`;
        }
    </script>
    @endpush
    @endif
</x-app-layout>