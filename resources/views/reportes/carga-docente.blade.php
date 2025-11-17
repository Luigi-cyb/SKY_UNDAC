<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte de Carga Docente') }}
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
                    <h3 class="text-lg font-semibold mb-4">Carga Académica por Docente</h3>
                    
                    @if(count($cargaDocente) > 0)
                        <div class="space-y-6">
                            @foreach($cargaDocente as $item)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="text-lg font-semibold">
                                            {{ $item['docente']->nombres }} {{ $item['docente']->apellidos }}
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $item['docente']->correo_institucional }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Cursos asignados</p>
                                        <p class="text-2xl font-bold text-blue-600">{{ $item['cursos_asignados'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $item['horas_totales'] }} horas</p>
                                    </div>
                                </div>

                                @if($item['cursos_asignados'] > 0)
                                    <div class="mt-4">
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Cursos:</p>
                                        <div class="space-y-2">
                                            @foreach($item['asignaciones'] as $asignacion)
                                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                                <div>
                                                    <p class="text-sm font-medium">{{ $asignacion->curso->nombre }}</p>
                                                    <p class="text-xs text-gray-600">{{ ucfirst($asignacion->tipo_asignacion) }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm">{{ $asignacion->curso->horas_academicas }} horas</p>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($asignacion->curso->estado == 'en_curso') bg-green-100 text-green-800
                                                        @elseif($asignacion->curso->estado == 'convocatoria') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $asignacion->curso->estado)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">Sin cursos asignados actualmente.</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No hay docentes activos registrados.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>