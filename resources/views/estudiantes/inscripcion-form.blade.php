<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inscripción al Curso
            </h2>
            <a href="{{ route('estudiantes.cursos-disponibles') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Información del Curso -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
                    <h3 class="text-2xl font-bold text-white">{{ $curso->nombre }}</h3>
                    <p class="text-blue-100 mt-1">{{ $curso->codigo }}</p>
                </div>

                <!-- Detalles del Curso -->
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Categoría -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Categoría</p>
                                <p class="text-base text-gray-900">{{ $curso->categoria->nombre ?? 'Sin categoría' }}</p>
                            </div>
                        </div>

                        <!-- Modalidad -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Modalidad</p>
                                <p class="text-base text-gray-900">{{ $curso->modalidad->nombre ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Fecha Inicio -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Fecha de Inicio</p>
                                <p class="text-base text-gray-900">{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <!-- Fecha Fin -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Fecha de Fin</p>
                                <p class="text-base text-gray-900">{{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <!-- Duración -->
<div class="flex items-start">
    <div class="flex-shrink-0">
        <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <div class="ml-3">
        <p class="text-sm font-medium text-gray-500">Duración</p>
        <p class="text-base text-gray-900">{{ $curso->horas_academicas }} horas</p>
    </div>
</div>

                        <!-- Cupos Disponibles -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Cupos Disponibles</p>
                                <p class="text-base font-bold {{ $cuposDisponibles > 0 ? 'text-green-600' : 'text-red-600' }}">
    {{ $cuposDisponibles }} de {{ $curso->cupo_maximo }}
</p>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    @if($curso->descripcion)
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Descripción del Curso</h4>
                        <p class="text-gray-700 leading-relaxed">{{ $curso->descripcion }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Información Importante -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Información Importante</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Acceso Inmediato:</strong> Podrás acceder a todas las clases del curso inmediatamente después de inscribirte.</li>
                                <li><strong>Sin Pago Inicial:</strong> No necesitas pagar para matricularte y asistir a las clases.</li>
                                <li><strong>Certificado Opcional:</strong> Si deseas obtener un certificado al finalizar, deberás:
                                    <ul class="list-circle list-inside ml-6 mt-1">
                                        <li>Realizar el pago de <strong>S/ {{ number_format($curso->costo_inscripcion, 2) }}</strong></li>
                                        <li>Aprobar el curso con nota mínima de <strong>10.5</strong></li>
                                        <li>Cumplir con asistencia mínima de <strong>75%</strong></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Inscripción -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirmar Inscripción</h3>
                    
                    <form method="POST" action="{{ route('estudiantes.inscribirse', $curso->id) }}">
                        @csrf

                        <!-- Datos del Estudiante -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Tus Datos</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Nombre:</span>
                                    <span class="font-medium text-gray-900">{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">DNI:</span>
                                    <span class="font-medium text-gray-900">{{ $estudiante->dni }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Correo:</span>
                                    <span class="font-medium text-gray-900">{{ $estudiante->correo_institucional }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Código:</span>
                                    <span class="font-medium text-gray-900">{{ $estudiante->codigo_estudiante ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Términos y Condiciones -->
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input type="checkbox" 
                                       name="acepta_terminos" 
                                       value="1"
                                       required
                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm text-gray-700">
                                    He leído y acepto los términos y condiciones. Entiendo que:
                                    <ul class="list-disc list-inside mt-2 ml-4 space-y-1 text-gray-600">
                                        <li>Puedo acceder libremente a todas las clases del curso</li>
                                        <li>Si deseo certificado, debo pagar, aprobar y cumplir asistencia mínima</li>
                                        <li>Me comprometo a cumplir con las normas del curso</li>
                                    </ul>
                                </span>
                            </label>
                            @error('acepta_terminos')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="flex gap-4">
                            <a href="{{ route('estudiantes.cursos-disponibles') }}" 
                               class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
                                Confirmar Inscripción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>