<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Nueva Sesión
                </h2>
                <p class="text-sm text-gray-600">Curso: {{ $curso->nombre }}</p>
            </div>
            <a href="{{ route('sesiones.index', $curso->id) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <!-- ✅ NUEVO: Indicador de Horas Disponibles -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                📊 Control de Horas Académicas
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Horas Totales</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $horasInfo['horas_totales'] }}h</p>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Horas Usadas</p>
                    <p class="text-2xl font-bold text-green-600">{{ $horasInfo['horas_usadas'] }}h</p>
                </div>
                
                <div class="bg-orange-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Horas Disponibles</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $horasInfo['horas_disponibles'] }}h</p>
                </div>
            </div>

            <!-- Barra de Progreso -->
            <div class="mb-2">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Progreso de programación</span>
                    <span class="font-semibold">{{ $horasInfo['porcentaje_usado'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300 
                                {{ $horasInfo['porcentaje_usado'] >= 90 ? 'bg-red-500' : 
                                   ($horasInfo['porcentaje_usado'] >= 70 ? 'bg-yellow-500' : 'bg-green-500') }}"
                         style="width: {{ min($horasInfo['porcentaje_usado'], 100) }}%">
                    </div>
                </div>
            </div>

            <!-- Mensaje de Alerta -->
            @if($horasInfo['porcentaje_usado'] >= 90)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-3">
                    <p class="text-sm">
                        ⚠️ <strong>Atención:</strong> Has utilizado el {{ $horasInfo['porcentaje_usado'] }}% de las horas disponibles.
                    </p>
                </div>
            @elseif($horasInfo['porcentaje_usado'] >= 70)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mt-3">
                    <p class="text-sm">
                        ℹ️ <strong>Aviso:</strong> Has utilizado el {{ $horasInfo['porcentaje_usado'] }}% de las horas disponibles.
                    </p>
                </div>
            @endif

            @if($horasInfo['minutos_disponibles'] <= 0)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-3">
                    <p class="text-sm">
                        🚫 <strong>Sin horas disponibles:</strong> No puedes crear más sesiones. Has alcanzado el límite de {{ $horasInfo['horas_totales'] }} horas del curso.
                    </p>
                </div>
            @endif
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('sesiones.store', $curso->id) }}" method="POST" class="bg-white rounded-lg shadow p-6">
                @csrf

                <!-- Información Básica -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Número de Sesión -->
                        <div>
                            <label for="numero_sesion" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Sesión <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="numero_sesion" 
                                   id="numero_sesion" 
                                   value="{{ old('numero_sesion', $siguienteNumero) }}"
                                   min="1"
                                   required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select name="estado" 
                                    id="estado" 
                                    required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="programada" {{ old('estado') == 'programada' ? 'selected' : '' }}>Programada</option>
                                <option value="en_vivo" {{ old('estado') == 'en_vivo' ? 'selected' : '' }}>En Vivo</option>
                                <option value="finalizada" {{ old('estado') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                <option value="cancelada" {{ old('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                    </div>

                    <!-- Título -->
                    <div class="mt-4">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                            Título de la Sesión <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="titulo" 
                               id="titulo" 
                               value="{{ old('titulo') }}"
                               placeholder="Ejemplo: Sesión 1: Introducción a Laravel"
                               required
                               maxlength="200"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Descripción -->
                    <div class="mt-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea name="descripcion" 
                                  id="descripcion" 
                                  rows="3"
                                  placeholder="Breve descripción de lo que se verá en esta sesión"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('descripcion') }}</textarea>
                    </div>

                    <!-- Objetivos -->
                    <div class="mt-4">
                        <label for="objetivos" class="block text-sm font-medium text-gray-700 mb-2">
                            Objetivos de la Sesión
                        </label>
                        <textarea name="objetivos" 
                                  id="objetivos" 
                                  rows="3"
                                  placeholder="- Objetivo 1&#10;- Objetivo 2&#10;- Objetivo 3"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('objetivos') }}</textarea>
                    </div>
                </div>

                <!-- Horario -->
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Horario</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Fecha -->
                        <div>
                            <label for="fecha_sesion" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_sesion" 
                                   id="fecha_sesion" 
                                   value="{{ old('fecha_sesion') }}"
                                   required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Hora Inicio -->
                        <div>
                            <label for="hora_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                Hora Inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="hora_inicio" 
                                   id="hora_inicio" 
                                   value="{{ old('hora_inicio', '09:00') }}"
                                   required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Hora Fin -->
                        <div>
                            <label for="hora_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                Hora Fin <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="hora_fin" 
                                   id="hora_fin" 
                                   value="{{ old('hora_fin', '11:00') }}"
                                   required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Enlaces -->
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Enlaces de Clase</h3>
                    
                    <!-- Plataforma -->
                    <div class="mb-4">
                        <label for="plataforma_vivo" class="block text-sm font-medium text-gray-700 mb-2">
                            Plataforma <span class="text-red-500">*</span>
                        </label>
                        <select name="plataforma_vivo" 
                                id="plataforma_vivo" 
                                required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="youtube" {{ old('plataforma_vivo') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                            <option value="google_meet" {{ old('plataforma_vivo') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                            <option value="zoom" {{ old('plataforma_vivo') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                            <option value="otro" {{ old('plataforma_vivo') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <!-- Enlace Clase en Vivo -->
                    <div class="mb-4">
                        <label for="enlace_clase_vivo" class="block text-sm font-medium text-gray-700 mb-2">
                            Enlace de Clase en Vivo
                        </label>
                        <input type="url" 
                               name="enlace_clase_vivo" 
                               id="enlace_clase_vivo" 
                               value="{{ old('enlace_clase_vivo') }}"
                               placeholder="https://meet.google.com/xxx-xxxx-xxx o https://youtube.com/live/xxxxx"
                               maxlength="500"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Enlace de YouTube Live, Google Meet, Zoom, etc.</p>
                    </div>

                    <!-- Enlace Grabación -->
                    <div>
                        <label for="enlace_grabacion" class="block text-sm font-medium text-gray-700 mb-2">
                            Enlace de Grabación
                        </label>
                        <input type="url" 
                               name="enlace_grabacion" 
                               id="enlace_grabacion" 
                               value="{{ old('enlace_grabacion') }}"
                               placeholder="https://youtube.com/watch?v=xxxxx"
                               maxlength="500"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Video grabado en YouTube o similar</p>
                    </div>
                </div>

                <!-- Opciones -->
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Opciones</h3>
                    
                    <div class="space-y-3">
                        <!-- Visible -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="visible" 
                                   id="visible" 
                                   value="1"
                                   {{ old('visible', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="visible" class="ml-2 block text-sm text-gray-700">
                                Visible para estudiantes
                            </label>
                        </div>

                        <!-- Permite Asistencia -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="permite_asistencia" 
                                   id="permite_asistencia" 
                                   value="1"
                                   {{ old('permite_asistencia', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="permite_asistencia" class="ml-2 block text-sm text-gray-700">
                                Permitir que estudiantes marquen asistencia
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    <a href="{{ route('sesiones.index', $curso->id) }}" 
                       class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg">
                        Crear Sesión
                    </button>
                </div>

                <!-- ✅ NUEVO: Script de Validación en Tiempo Real -->
                <script>
                    const horaInicio = document.getElementById('hora_inicio');
                    const horaFin = document.getElementById('hora_fin');
                    const minutosDisponibles = {{ $horasInfo['minutos_disponibles'] }};

                    function validarDuracion() {
                        if (horaInicio.value && horaFin.value) {
                            const inicio = new Date('2000-01-01 ' + horaInicio.value);
                            const fin = new Date('2000-01-01 ' + horaFin.value);
                            
                            const duracionMinutos = (fin - inicio) / 1000 / 60;
                            
                            if (duracionMinutos > minutosDisponibles) {
                                const horasIntento = (duracionMinutos / 60).toFixed(2);
                                const horasDisponibles = (minutosDisponibles / 60).toFixed(2);
                                
                                alert(`⚠️ La duración de la sesión (${horasIntento}h) excede las horas disponibles (${horasDisponibles}h).`);
                                horaFin.value = '';
                                return false;
                            }
                        }
                        return true;
                    }

                    horaInicio.addEventListener('change', validarDuracion);
                    horaFin.addEventListener('change', validarDuracion);
                </script>
            </form>

        </div>
    </div>
</x-app-layout>