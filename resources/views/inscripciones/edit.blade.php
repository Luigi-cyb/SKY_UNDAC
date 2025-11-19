<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    ✏️ Editar Inscripción
                </h2>
                <p class="text-sm text-gray-600 mt-1">Modifica la información de la inscripción: <span class="font-semibold">{{ $inscripcion->codigo_inscripcion }}</span></p>
            </div>
            <a href="{{ route('inscripciones.show', $inscripcion) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes de error -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-md">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-red-800 font-bold mb-2">¡Atención! Hay errores en el formulario:</h3>
                            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('inscripciones.update', $inscripcion) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Información de Referencia -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información de la Inscripción
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
                                <p class="text-xs text-gray-600 font-semibold mb-1">Código de Inscripción</p>
                                <p class="text-lg font-bold text-purple-900">{{ $inscripcion->codigo_inscripcion }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                                <p class="text-xs text-gray-600 font-semibold mb-1">Estudiante</p>
                                <p class="text-sm font-bold text-green-900">{{ $inscripcion->estudiante->nombres }} {{ $inscripcion->estudiante->apellidos }}</p>
                                <p class="text-xs text-gray-600 mt-1">DNI: {{ $inscripcion->estudiante->dni }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                                <p class="text-xs text-gray-600 font-semibold mb-1">Curso</p>
                                <p class="text-sm font-bold text-blue-900">{{ $inscripcion->curso->nombre }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $inscripcion->curso->codigo }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado y Pago -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #9333ea, #7e22ce) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Estado y Pago
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-bold text-gray-700 mb-2">
                                    Estado de la Inscripción <span class="text-red-500">*</span>
                                </label>
                                <select name="estado" id="estado" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="provisional" {{ old('estado', $inscripcion->estado) == 'provisional' ? 'selected' : '' }}>⏳ Provisional</option>
                                    <option value="confirmada" {{ old('estado', $inscripcion->estado) == 'confirmada' ? 'selected' : '' }}>✅ Confirmada</option>
                                    <option value="cancelada" {{ old('estado', $inscripcion->estado) == 'cancelada' ? 'selected' : '' }}>❌ Cancelada</option>
                                    <option value="rechazada" {{ old('estado', $inscripcion->estado) == 'rechazada' ? 'selected' : '' }}>🚫 Rechazada</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Cambia el estado según el proceso de inscripción</p>
                            </div>

                            <!-- Pago Confirmado -->
                            <div>
                                <label for="pago_confirmado" class="block text-sm font-bold text-gray-700 mb-2">
                                    Pago Confirmado <span class="text-red-500">*</span>
                                </label>
                                <select name="pago_confirmado" id="pago_confirmado" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="0" {{ old('pago_confirmado', $inscripcion->pago_confirmado) == '0' ? 'selected' : '' }}>⏳ No - Pago Pendiente</option>
                                    <option value="1" {{ old('pago_confirmado', $inscripcion->pago_confirmado) == '1' ? 'selected' : '' }}>✅ Sí - Pago Confirmado</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Indica si el pago de la inscripción fue confirmado</p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #10b981, #059669) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Observaciones
                        </h3>
                    </div>
                    <div class="p-6">
                        <label for="observaciones" class="block text-sm font-bold text-gray-700 mb-2">
                            Notas Adicionales
                        </label>
                        <textarea name="observaciones" id="observaciones" rows="4"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition"
                            placeholder="Escribe cualquier observación o nota importante sobre esta inscripción...">{{ old('observaciones', $inscripcion->observaciones) }}</textarea>
                        <p class="mt-2 text-xs text-gray-500">Puedes agregar notas sobre pagos, requisitos pendientes, o cualquier información relevante</p>
                    </div>
                </div>

                <!-- Advertencia sobre cambios de estado -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #f59e0b, #d97706) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Consideraciones Importantes
                        </h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">Cambiar el estado a <strong>Confirmada</strong> requiere que el pago esté confirmado</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">El estudiante recibirá una <strong>notificación por correo</strong> sobre cualquier cambio de estado</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">Cancelar una inscripción <strong>liberará un cupo</strong> en el curso</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="p-6 flex justify-between items-center">
                        <a href="{{ route('inscripciones.show', $inscripcion) }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-lg font-bold text-sm text-gray-800 uppercase tracking-wide hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2 transition shadow-lg transform hover:scale-105"
                                style="background: linear-gradient(to right, #9333ea, #7e22ce) !important; color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Actualizar Inscripción
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>