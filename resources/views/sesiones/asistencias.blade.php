<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Encabezado -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            📋 Asistencias - Sesión {{ $sesion->numero_sesion }}
                        </h1>
                        <p class="text-gray-600 mt-2">{{ $sesion->titulo }}</p>
                        <p class="text-sm text-gray-500">
                            📅 {{ $sesion->fecha_sesion->format('d/m/Y') }} | 
                            🕐 {{ $sesion->getHoraInicioFormateada() }} - {{ $sesion->getHoraFinFormateada() }}
                        </p>
                        <p class="text-sm font-semibold mt-2">
                            Curso: {{ $sesion->curso->nombre }}
                        </p>
                    </div>
                    <a href="{{ auth()->user()->hasRole('Docente') 
                        ? route('docente.sesiones.index', $sesion->curso_id) 
                        : route('sesiones.index', $sesion->curso_id) }}" 
                       class="text-blue-600 hover:text-blue-800">
                        ← Volver a Sesiones
                    </a>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-blue-600 text-sm font-medium">Total Inscritos</div>
                    <div class="text-3xl font-bold text-blue-700">{{ $estadisticas['total'] }}</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-green-600 text-sm font-medium">Presentes</div>
                    <div class="text-3xl font-bold text-green-700">{{ $estadisticas['presentes'] }}</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="text-red-600 text-sm font-medium">Ausentes</div>
                    <div class="text-3xl font-bold text-red-700">{{ $estadisticas['ausentes'] }}</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="text-yellow-600 text-sm font-medium">% Asistencia</div>
                    <div class="text-3xl font-bold text-yellow-700">
                        {{ number_format($estadisticas['porcentaje_asistencia'], 1) }}%
                    </div>
                </div>
            </div>

            <!-- Estado de la Sesión -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">Estado de la sesión:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($sesion->estado === 'en_vivo') bg-green-100 text-green-800
                            @elseif($sesion->estado === 'finalizada') bg-blue-100 text-blue-800
                            @elseif($sesion->estado === 'programada') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $sesion->getEstadoTexto() }}
                        </span>
                        
                        @if($sesion->permite_asistencia)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                ✅ Asistencia Habilitada
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                                ❌ Asistencia Cerrada
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Lista de Asistencias -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Lista de Estudiantes</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Estudiante
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    DNI
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Hora Registro
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
    @forelse($asistencias as $index => $item)
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $index + 1 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">
                    {{ $item['estudiante']->nombres }} {{ $item['estudiante']->apellidos }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ $item['estudiante']->correo_institucional }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $item['estudiante']->dni }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                @if($item['asistencia'])
                    {{ \Carbon\Carbon::parse($item['asistencia']->hora_registro)->format('H:i:s') }}
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($item['asistencia'] && $item['asistencia']->estado === 'presente')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        ✅ Presente
                    </span>
                @elseif($item['asistencia'] && $item['asistencia']->estado === 'tardanza')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        ⏰ Tardanza
                    </span>
                @elseif($item['asistencia'] && $item['asistencia']->estado === 'justificado')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        📝 Justificado
                    </span>
                @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        ❌ Ausente
                    </span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                No hay estudiantes inscritos en este curso
            </td>
        </tr>
    @endforelse
</tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>