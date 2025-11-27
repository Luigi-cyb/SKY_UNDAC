<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte Académico - ') }}{{ $curso->nombre }}
            </h2>
            <a href="{{ route('reportes.rendimiento') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información del Curso -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Información del Curso</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Código:</p>
                            <p class="font-semibold">{{ $curso->codigo }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nota Mínima:</p>
                            <p class="font-semibold">{{ $curso->nota_minima_aprobacion }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Asistencia Mínima:</p>
                            <p class="font-semibold">{{ $curso->asistencia_minima_porcentaje }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Estudiantes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Rendimiento por Estudiante</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Promedio</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Asistencia</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($datosAcademicos as $dato)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $dato['estudiante']->nombres }} {{ $dato['estudiante']->apellidos }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $dato['estudiante']->dni }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold">
                                        {{ $dato['promedio'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        {{ $dato['asistencia'] }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ str_contains($dato['estado_academico'], 'Aprobado') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $dato['estado_academico'] }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No hay estudiantes inscritos en este curso.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>