<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registrar Asistencia
            </h2>
            <a href="{{ route('asistencias.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">¡Error!</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                {{ session('warning') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('asistencias.store') }}">
                        @csrf

                        <!-- Información del Curso -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-blue-800">
                                        {{ $curso->nombre }} ({{ $curso->codigo }})
                                    </p>
                                    <p class="text-xs text-blue-600">
                                        {{ $curso->modalidad->nombre ?? 'N/A' }} | {{ $inscripciones->count() }} estudiantes inscritos
                                    </p>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">

                        <!-- Fecha y Sesión -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="fecha_sesion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_sesion" id="fecha_sesion" 
                                       value="{{ old('fecha_sesion', date('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}"
                                       required 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="numero_sesion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Número de Sesión <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="numero_sesion" id="numero_sesion" 
                                       min="1" 
                                       value="{{ old('numero_sesion', $numeroSesion) }}" 
                                       required 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="tema_sesion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tema de la Sesión
                                </label>
                                <input type="text" name="tema_sesion" id="tema_sesion" 
                                       value="{{ old('tema_sesion') }}" 
                                       placeholder="Ej: Introducción a Laravel"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Lista de Estudiantes -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    📋 Lista de Estudiantes ({{ $inscripciones->count() }})
                                </h3>
                                <div class="flex gap-2">
                                    <button type="button" onclick="marcarTodos('presente')" 
                                            class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 px-4 rounded">
                                        ✅ Todos Presentes
                                    </button>
                                    <button type="button" onclick="marcarTodos('ausente')" 
                                            class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded">
                                        ❌ Todos Ausentes
                                    </button>
                                </div>
                            </div>

                            @if($inscripciones && $inscripciones->count() > 0)
                                <div class="space-y-3">
                                    @foreach($inscripciones as $inscripcion)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-white hover:shadow-md transition">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center flex-1">
                                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                    {{ substr($inscripcion->estudiante->nombres, 0, 1) }}{{ substr($inscripcion->estudiante->apellidos, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <p class="font-semibold text-gray-900">
                                                        {{ $inscripcion->estudiante->nombres }} {{ $inscripcion->estudiante->apellidos }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        DNI: {{ $inscripcion->estudiante->dni }} 
                                                        @if($inscripcion->estudiante->codigo_estudiante)
                                                            | Código: {{ $inscripcion->estudiante->codigo_estudiante }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-3">
                                                <input type="hidden" name="inscripcion_ids[]" value="{{ $inscripcion->id }}">
                                                
                                                <div>
                                                    <select name="estados[]" 
                                                            class="estado-select rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-semibold">
                                                        <option value="presente" class="text-green-700">✅ Presente</option>
                                                        <option value="ausente" class="text-red-700">❌ Ausente</option>
                                                        <option value="tardanza" class="text-yellow-700">⏰ Tardanza</option>
                                                        <option value="justificado" class="text-blue-700">📝 Justificado</option>
                                                    </select>
                                                </div>
                                                
                                                <div>
                                                    <input type="time" name="horas_registro[]" 
                                                           value="{{ date('H:i') }}"
                                                           class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="mt-2 text-yellow-800 font-semibold">No hay estudiantes inscritos en este curso</p>
                                    <p class="text-sm text-yellow-600">Verifica que haya inscripciones confirmadas</p>
                                </div>
                            @endif
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-6">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                Observaciones (Opcional)
                            </label>
                            <textarea name="observaciones" id="observaciones" rows="3" 
                                      placeholder="Notas adicionales sobre la sesión..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('observaciones') }}</textarea>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('asistencias.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                💾 Guardar Asistencia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function marcarTodos(estado) {
        document.querySelectorAll('.estado-select').forEach(select => {
            select.value = estado;
        });
    }
    </script>
</x-app-layout>