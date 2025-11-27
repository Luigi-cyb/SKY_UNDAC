<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cursos Disponibles
            </h2>
            <a href="{{ route('estudiantes.mis-cursos') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Mis Cursos
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                {{ session('warning') }}
            </div>
            @endif

            <!-- Información -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>¡Matrícula Libre!</strong> Puedes inscribirte en cualquier curso sin necesidad de pago. 
                            Si deseas obtener un certificado al finalizar, deberás realizar el pago correspondiente, aprobar el curso y cumplir con la asistencia mínima requerida.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Lista de Cursos -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Cursos en Convocatoria ({{ $cursos->count() }})
                    </h3>

                    @if($cursos->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($cursos as $curso)
                            <div class="border rounded-lg overflow-hidden hover:shadow-xl transition">
                                <!-- Header del curso -->
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                                    <h4 class="text-lg font-bold text-white">{{ $curso->nombre }}</h4>
                                    <p class="text-sm text-blue-100">{{ $curso->codigo }}</p>
                                </div>

                                <!-- Contenido -->
                                <div class="p-4 space-y-3">
                                    <!-- Categoría -->
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $curso->categoria->nombre ?? 'Sin categoría' }}
                                    </div>

                                    <!-- Fechas -->
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}
                                    </div>

                                    <!-- Duración -->
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $curso->horas_academicas ?? 'N/A' }} horas
                                    </div>

                                    <!-- Cupos -->
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        @php
    $cuposDisponibles = $curso->cupo_maximo - $curso->inscripciones_count;
    $cuposDisponibles = max(0, $cuposDisponibles);
@endphp
<span class="font-semibold {{ $cuposDisponibles > 0 ? 'text-green-600' : 'text-red-600' }}">
    {{ $cuposDisponibles }} cupos disponibles
</span>
                                    </div>

                                    <!-- Costo -->
                                    <div class="bg-gray-50 rounded p-2">
                                        <p class="text-xs text-gray-600">Costo del certificado:</p>
                                        <p class="text-lg font-bold text-gray-900">S/ {{ number_format($curso->costo_inscripcion, 2) }}</p>
                                        <p class="text-xs text-green-600 italic">* Solo si deseas certificado</p>
                                    </div>
                                </div>

                                <!-- Footer con botón -->
                                <div class="p-4 bg-gray-50 border-t">
                                    @if(in_array($curso->id, $cursosInscritos))
                                        <button disabled class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                            Ya Inscrito
                                        </button>
                                    @elseif($cuposDisponibles <= 0)
                                        <button disabled class="w-full bg-red-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                            Sin Cupos
                                        </button>
                                    @else
                                        <a href="{{ route('estudiantes.mostrar-inscripcion', $curso->id) }}" 
                                           class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                            Inscribirme Ahora
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cursos disponibles</h3>
                            <p class="mt-1 text-sm text-gray-500">Por el momento no hay cursos en convocatoria</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>