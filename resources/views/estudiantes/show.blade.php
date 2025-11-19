<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles del Estudiante') }}
            </h2>
            <div class="space-x-2">
                @can('estudiantes.editar')
                <a href="{{ route('estudiantes.edit', $estudiante) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs !text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Editar
                </a>
                @endcan
                <a href="{{ route('estudiantes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs !text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tarjeta con Foto y Resumen -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        <!-- Foto del estudiante -->
                        <div class="flex-shrink-0">
                            @if($estudiante->foto_url)
                                <img src="{{ asset('storage/' . $estudiante->foto_url) }}" 
                                    alt="Foto de {{ $estudiante->nombres }}" 
                                    class="h-32 w-32 rounded-full object-cover border-4 border-indigo-100">
                            @else
                                <div class="h-32 w-32 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-indigo-200">
                                    <span class="text-4xl font-bold text-indigo-600">
                                        {{ substr($estudiante->nombres, 0, 1) }}{{ substr($estudiante->apellidos, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Información Principal -->
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $estudiante->nombres }} {{ $estudiante->apellidos }}
                            </h3>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $estudiante->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $estudiante->activo ? '✓ Activo' : '✗ Inactivo' }}
                                </span>
                                @if($estudiante->pertenece_eisc)
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    🎓 EISC
                                </span>
                                @endif
                            </div>
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <p class="text-2xl font-bold text-indigo-600">{{ $totalInscripciones }}</p>
                                    <p class="text-xs text-gray-600">Inscripciones</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <p class="text-2xl font-bold text-green-600">{{ $cursosActivos }}</p>
                                    <p class="text-xs text-gray-600">Cursos Activos</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <p class="text-2xl font-bold text-blue-600">{{ $certificadosObtenidos }}</p>
                                    <p class="text-xs text-gray-600">Certificados</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <p class="text-2xl font-bold text-purple-600">
                                        {{ $estudiante->ciclo_academico ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-600">Ciclo Actual</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Personal -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">📋 Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">DNI:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->dni }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Código Universitario:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->codigo_estudiante ?? 'No asignado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Nacimiento:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->fecha_nacimiento ? \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Sexo:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->sexo == 'M' ? 'Masculino' : ($estudiante->sexo == 'F' ? 'Femenino' : 'N/A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Teléfono:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->telefono ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Teléfono de Emergencia:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->telefono_emergencia ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($estudiante->direccion)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Dirección:</p>
                        <p class="mt-1 text-gray-900">{{ $estudiante->direccion }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Información Académica -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">🎓 Información Académica</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Correo Institucional:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->correo_institucional }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Correo Personal:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->correo_personal ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pertenece a EISC:</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estudiante->pertenece_eisc ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $estudiante->pertenece_eisc ? 'Sí' : 'No' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ciclo Académico:</p>
                            <p class="font-semibold text-gray-900">{{ $estudiante->ciclo_academico ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inscripciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">📚 Inscripciones ({{ $estudiante->inscripciones->count() }})</h3>
                    @if($estudiante->inscripciones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modalidad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($estudiante->inscripciones as $inscripcion)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $inscripcion->curso->nombre }}</div>
                                            <div class="text-sm text-gray-500">{{ $inscripcion->curso->categoria->nombre ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $inscripcion->codigo_inscripcion }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $inscripcion->curso->modalidad->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($inscripcion->estado == 'confirmada') bg-green-100 text-green-800
                                                @elseif($inscripcion->estado == 'provisional') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($inscripcion->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('inscripciones.show', $inscripcion) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Ver detalles →</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">📭 No hay inscripciones registradas.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>