<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte de Rendimiento Académico') }}
            </h2>
            <a href="{{ route('reportes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Estadísticas por Curso</h3>
                    
                    @if(count($estadisticas) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Estudiantes</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aprobados</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Desaprobados</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tasa Aprobación</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Promedio General</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($estadisticas as $stat)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $stat['curso']->nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            {{ $stat['total_estudiantes'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="text-green-600 font-semibold">{{ $stat['aprobados'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="text-red-600 font-semibold">{{ $stat['desaprobados'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <div class="flex items-center justify-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stat['tasa_aprobacion'] }}%"></div>
                                                </div>
                                                <span class="font-semibold">{{ $stat['tasa_aprobacion'] }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold">
                                            {{ $stat['promedio_general'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('reportes.academico-curso', $stat['curso']->id) }}" class="text-blue-600 hover:text-blue-900">
                                                Ver detalle
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No hay datos de rendimiento académico disponibles.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>