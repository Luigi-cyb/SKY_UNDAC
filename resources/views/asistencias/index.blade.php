<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Asistencias
            </h2>
            <div class="flex gap-2">
                <button onclick="document.getElementById('modalReporte').classList.remove('hidden')" 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    📊 Ver Reporte
                </button>
                <button onclick="document.getElementById('modalSeleccionarCurso').classList.remove('hidden')" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Registrar Asistencia
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Filtros de Búsqueda</h3>
                    <form method="GET" action="{{ route('asistencias.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Curso</label>
                            <select name="curso_id" class="w-full rounded-md border-gray-300">
                                <option value="">Todos los cursos</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select name="estado" class="w-full rounded-md border-gray-300">
                                <option value="">Todos</option>
                                <option value="presente" {{ request('estado') == 'presente' ? 'selected' : '' }}>Presente</option>
                                <option value="ausente" {{ request('estado') == 'ausente' ? 'selected' : '' }}>Ausente</option>
                                <option value="tardanza" {{ request('estado') == 'tardanza' ? 'selected' : '' }}>Tardanza</option>
                                <option value="justificado" {{ request('estado') == 'justificado' ? 'selected' : '' }}>Justificado</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filtrar
                            </button>
                            <a href="{{ route('asistencias.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-500 rounded-full mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Registros</p>
                            <p class="text-2xl font-bold">{{ $totalRegistros }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-500 rounded-full mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Presentes</p>
                            <p class="text-2xl font-bold text-green-600">{{ $presentes }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-500 rounded-full mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ausentes</p>
                            <p class="text-2xl font-bold text-red-600">{{ $ausentes }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-500 rounded-full mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tardanzas</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ $tardanzas }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Lista de Asistencias</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sesión</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($asistencias as $asistencia)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $asistencia->curso->nombre }}</div>
                                        <div class="text-sm text-gray-500">{{ $asistencia->curso->codigo }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $asistencia->inscripcion->estudiante->nombres }} {{ $asistencia->inscripcion->estudiante->apellidos }}
                                        </div>
                                        <div class="text-sm text-gray-500">DNI: {{ $asistencia->inscripcion->estudiante->dni }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($asistencia->fecha_sesion)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        Sesión {{ $asistencia->numero_sesion }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($asistencia->estado === 'presente') bg-green-100 text-green-800
                                            @elseif($asistencia->estado === 'ausente') bg-red-100 text-red-800
                                            @elseif($asistencia->estado === 'tardanza') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst($asistencia->estado) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No hay registros de asistencia
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $asistencias->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Asistencia -->
    <div id="modalSeleccionarCurso" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Seleccionar Curso</h3>
                <button onclick="document.getElementById('modalSeleccionarCurso').classList.add('hidden')" 
                        class="text-2xl">×</button>
            </div>
            <form action="{{ route('asistencias.create') }}" method="GET">
                <div class="mb-4">
                    <label class="block font-bold mb-2">Curso:</label>
                    <select name="curso_id" required class="w-full px-3 py-2 border rounded-md">
                        <option value="">-- Seleccione --</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modalSeleccionarCurso').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Continuar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ver Reporte -->
    <div id="modalReporte" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">📊 Seleccionar Curso para Reporte</h3>
                <button onclick="document.getElementById('modalReporte').classList.add('hidden')" 
                        class="text-2xl">×</button>
            </div>
            <form action="{{ url('asistencias/reporte') }}" method="GET">
                <div class="mb-4">
                    <label class="block font-bold mb-2">Curso:</label>
                    <select name="curso_id" required class="w-full px-3 py-2 border rounded-md">
                        <option value="">-- Seleccione un curso --</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modalReporte').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Ver Reporte</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>