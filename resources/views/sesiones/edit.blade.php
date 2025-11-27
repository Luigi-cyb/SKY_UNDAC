<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Sesión: {{ $sesion->titulo }}
            </h2>
            <a href="{{ route('sesiones.index', $curso->id) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver a Sesiones
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('sesiones.update', $sesion->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Información del Curso -->
                    <div class="mb-6 p-4 bg-blue-50 rounded">
                        <p class="text-sm text-gray-600">Curso: <strong>{{ $curso->nombre }}</strong></p>
                        <p class="text-sm text-gray-600">Código: <strong>{{ $curso->codigo }}</strong></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Número de Sesión (readonly) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Sesión
                            </label>
                            <input type="text" 
                                   value="Sesión #{{ $sesion->numero_sesion }}" 
                                   readonly
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>

                        <!-- Título -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Título de la Sesión <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="titulo" 
                                   value="{{ old('titulo', $sesion->titulo) }}" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea name="descripcion" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $sesion->descripcion) }}</textarea>
                    </div>

                    <!-- Objetivos -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Objetivos de la Sesión
                        </label>
                        <textarea name="objetivos" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('objetivos', $sesion->objetivos) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <!-- Fecha -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Sesión <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_sesion" 
                                   value="{{ old('fecha_sesion', $sesion->fecha_sesion->format('Y-m-d')) }}" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Hora Inicio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hora de Inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="hora_inicio" 
                                   value="{{ old('hora_inicio', $sesion->hora_inicio) }}" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Hora Fin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hora de Fin <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="hora_fin" 
                                   value="{{ old('hora_fin', $sesion->hora_fin) }}" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Plataforma -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Plataforma <span class="text-red-500">*</span>
                            </label>
                            <select name="plataforma_vivo" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="youtube" {{ $sesion->plataforma_vivo === 'youtube' ? 'selected' : '' }}>YouTube</option>
                                <option value="google_meet" {{ $sesion->plataforma_vivo === 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                <option value="zoom" {{ $sesion->plataforma_vivo === 'zoom' ? 'selected' : '' }}>Zoom</option>
                                <option value="otro" {{ $sesion->plataforma_vivo === 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select name="estado" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="programada" {{ $sesion->estado === 'programada' ? 'selected' : '' }}>Programada</option>
                                <option value="en_vivo" {{ $sesion->estado === 'en_vivo' ? 'selected' : '' }}>En Vivo</option>
                                <option value="finalizada" {{ $sesion->estado === 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                <option value="cancelada" {{ $sesion->estado === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                    </div>

                    <!-- Enlaces -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Enlace de Clase en Vivo
                            </label>
                            <input type="url" 
                                   name="enlace_clase_vivo" 
                                   value="{{ old('enlace_clase_vivo', $sesion->enlace_clase_vivo) }}" 
                                   placeholder="https://meet.google.com/xxx-xxxx-xxx"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Enlace de Grabación
                            </label>
                            <input type="url" 
                                   name="enlace_grabacion" 
                                   value="{{ old('enlace_grabacion', $sesion->enlace_grabacion) }}" 
                                   placeholder="https://youtube.com/watch?v=xxxxx"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="visible" 
                                   id="visible"
                                   {{ $sesion->visible ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="visible" class="ml-2 text-sm text-gray-700">
                                Visible para estudiantes
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="permite_asistencia" 
                                   id="permite_asistencia"
                                   {{ $sesion->permite_asistencia ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="permite_asistencia" class="ml-2 text-sm text-gray-700">
                                Permite registro de asistencia
                            </label>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('sesiones.index', $curso->id) }}" 
                           class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                            💾 Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>