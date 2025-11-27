<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles del Docente') }}
            </h2>
            <div class="space-x-2">
                @can('docentes.editar')
                <a href="{{ route('docentes.edit', $docente) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs !text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ✏️ Editar
                </a>
                @endcan
                <a href="{{ route('docentes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs !text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- ✅ Tarjeta Principal con Nombre y Estado -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold">{{ $docente->nombres }} {{ $docente->apellidos }}</h1>
                            <p class="text-indigo-100 mt-2">
                                <span class="inline-flex items-center">
                                    📧 {{ $docente->correo_institucional }}
                                </span>
                            </p>
                            <p class="text-indigo-100 mt-1">
                                <span class="inline-flex items-center">
                                    🆔 DNI: {{ $docente->dni }}
                                </span>
                            </p>
                        </div>
                        <div class="text-center">
                            <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $docente->activo ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $docente->activo ? '✅ Activo' : '❌ Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ Estadísticas Rápidas -->
            @if(isset($stats))
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center border-l-4 border-blue-500">
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_cursos'] ?? 0 }}</p>
                        <p class="text-xs text-gray-600 mt-2">Total Cursos</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center border-l-4 border-green-500">
                        <p class="text-3xl font-bold text-green-600">{{ $stats['cursos_activos'] ?? 0 }}</p>
                        <p class="text-xs text-gray-600 mt-2">Cursos Activos</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center border-l-4 border-red-500">
                        <p class="text-3xl font-bold text-red-600">{{ $stats['cursos_finalizados'] ?? 0 }}</p>
                        <p class="text-xs text-gray-600 mt-2">Cursos Finalizados</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center border-l-4 border-purple-500">
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['total_estudiantes'] ?? 0 }}</p>
                        <p class="text-xs text-gray-600 mt-2">Total Estudiantes</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                
                <!-- ✅ Información Personal -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-t-4 border-indigo-500">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <span class="text-2xl mr-2">👤</span>
                            Información Personal
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-600">DNI:</span>
                                <span class="font-semibold">{{ $docente->dni }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-600">Nombres:</span>
                                <span class="font-semibold">{{ $docente->nombres }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-600">Apellidos:</span>
                                <span class="font-semibold">{{ $docente->apellidos }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-600">Fecha de Nacimiento:</span>
                                <span class="font-semibold">
                                    {{ $docente->fecha_nacimiento ? \Carbon\Carbon::parse($docente->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                                    @if($docente->fecha_nacimiento)
                                        <span class="text-xs text-gray-500">
                                            ({{ \Carbon\Carbon::parse($docente->fecha_nacimiento)->age }} años)
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-600">Sexo:</span>
                                <span class="font-semibold">
                                    {{ $docente->sexo == 'M' ? '🚹 Masculino' : ($docente->sexo == 'F' ? '🚺 Femenino' : 'N/A') }}
                                </span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-600">Teléfono:</span>
                                <span class="font-semibold">📞 {{ $docente->telefono ?? 'N/A' }}</span>
                            </div>
                            @if($docente->direccion)
                            <div class="pt-2">
                                <span class="text-sm text-gray-600">📍 Dirección:</span>
                                <p class="mt-1 font-semibold">{{ $docente->direccion }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ✅ Información de Contacto -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-t-4 border-green-500">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <span class="text-2xl mr-2">📧</span>
                            Información de Contacto
                        </h3>
                        <div class="space-y-3">
                            <div class="p-4 bg-indigo-50 rounded-lg">
                                <p class="text-sm text-gray-600">Correo Institucional:</p>
                                <p class="font-semibold text-indigo-700 mt-1">{{ $docente->correo_institucional }}</p>
                            </div>
                            @if($docente->correo_personal)
                            <div class="p-4 bg-green-50 rounded-lg">
                                <p class="text-sm text-gray-600">Correo Personal:</p>
                                <p class="font-semibold text-green-700 mt-1">{{ $docente->correo_personal }}</p>
                            </div>
                            @endif
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Usuario del Sistema:</p>
                                <p class="font-semibold mt-1">{{ $docente->user->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ✅ Información Académica y Profesional -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-t-4 border-yellow-500">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <span class="text-2xl mr-2">🎓</span>
                        Información Académica y Profesional
                    </h3>
                    
                    @if($docente->formacion_academica)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold mb-2">📚 Formación Académica:</p>
                        <p class="mt-2 whitespace-pre-wrap text-gray-800">{{ $docente->formacion_academica }}</p>
                    </div>
                    @endif

                    @if($docente->experiencia_profesional)
                    <div class="mb-6 p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold mb-2">💼 Experiencia Profesional:</p>
                        <p class="mt-2 whitespace-pre-wrap text-gray-800">{{ $docente->experiencia_profesional }}</p>
                    </div>
                    @endif

                    @if($docente->especialidades)
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold mb-2">⭐ Especialidades:</p>
                        <p class="mt-2 whitespace-pre-wrap text-gray-800">{{ $docente->especialidades }}</p>
                    </div>
                    @endif

                    @if(!$docente->formacion_academica && !$docente->experiencia_profesional && !$docente->especialidades)
                    <p class="text-gray-500 text-center py-8">No hay información académica o profesional registrada.</p>
                    @endif
                </div>
            </div>

            <!-- ✅ Cursos Asignados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-t-4 border-red-500">
                    <h3 class="text-lg font-semibold mb-4 flex items-center justify-between">
                        <span class="flex items-center">
                            <span class="text-2xl mr-2">📚</span>
                            Cursos Asignados
                        </span>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">
                            {{ $docente->asignaciones->count() }} cursos
                        </span>
                    </h3>
                    
                    @if($docente->asignaciones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado Curso</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado Asignación</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($docente->asignaciones as $asignacion)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('cursos.show', $asignacion->curso) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                                {{ $asignacion->curso->nombre }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">
                                                {{ ucfirst($asignacion->tipo_asignacion) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($asignacion->curso->estado == 'en_curso') bg-green-100 text-green-800
                                                @elseif($asignacion->curso->estado == 'convocatoria') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $asignacion->curso->estado)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asignacion->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $asignacion->activo ? '✅ Activo' : '❌ Inactivo' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p class="text-gray-500 mt-4 text-lg">No hay cursos asignados a este docente</p>
                            <p class="text-gray-400 mt-2 text-sm">Los cursos aparecerán aquí cuando sean asignados</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>