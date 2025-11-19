<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    ➕ Crear Nuevo Curso
                </h2>
                <p class="text-sm text-gray-600 mt-1">Completa la información del nuevo curso</p>
            </div>
            <a href="{{ route('cursos.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            <form action="{{ route('cursos.store') }}" method="POST">
                @csrf

                <!-- Información Básica -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información Básica
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Código -->
                            <div>
                                <label for="codigo" class="block text-sm font-bold text-gray-700 mb-2">
                                    Código del Curso <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="codigo" id="codigo" value="{{ old('codigo') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                <p class="mt-1 text-xs text-gray-500">Código único identificador del curso</p>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nombre del Curso <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label for="categoria_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Categoría <span class="text-red-500">*</span>
                                </label>
                                <select name="categoria_id" id="categoria_id" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Modalidad -->
                            <div>
                                <label for="modalidad_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Modalidad <span class="text-red-500">*</span>
                                </label>
                                <select name="modalidad_id" id="modalidad_id" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">Seleccione una modalidad</option>
                                    @foreach($modalidades as $modalidad)
                                        <option value="{{ $modalidad->id }}" {{ old('modalidad_id') == $modalidad->id ? 'selected' : '' }}>
                                            {{ $modalidad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nivel -->
                            <div>
                                <label for="nivel" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nivel
                                </label>
                                <input type="text" name="nivel" id="nivel" value="{{ old('nivel') }}"
                                    placeholder="Ej: Básico, Intermedio, Avanzado"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-bold text-gray-700 mb-2">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="estado" id="estado" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="borrador" {{ old('estado') == 'borrador' ? 'selected' : '' }}>📝 Borrador</option>
                                    <option value="convocatoria" {{ old('estado', 'convocatoria') == 'convocatoria' ? 'selected' : '' }}>📢 En Convocatoria</option>
                                    <option value="en_curso" {{ old('estado') == 'en_curso' ? 'selected' : '' }}>✅ En Curso</option>
                                    <option value="finalizado" {{ old('estado') == 'finalizado' ? 'selected' : '' }}>🏁 Finalizado</option>
                                    <option value="archivado" {{ old('estado') == 'archivado' ? 'selected' : '' }}>📦 Archivado</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Configuración Académica -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Configuración Académica
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Horas Académicas -->
                            <div>
                                <label for="duracion_horas" class="block text-sm font-bold text-gray-700 mb-2">
                                    Horas Académicas <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="duracion_horas" id="duracion_horas" value="{{ old('duracion_horas') }}" required min="1"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition pl-10">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Nota Mínima -->
                            <div>
                                <label for="nota_minima_aprobacion" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nota Mínima (0-20) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" name="nota_minima_aprobacion" id="nota_minima_aprobacion" value="{{ old('nota_minima_aprobacion', 11) }}" required min="0" max="20"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Asistencia Mínima -->
                            <div>
                                <label for="porcentaje_asistencia_minima" class="block text-sm font-bold text-gray-700 mb-2">
                                    Asistencia Mínima (%) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="porcentaje_asistencia_minima" id="porcentaje_asistencia_minima" value="{{ old('porcentaje_asistencia_minima', 70) }}" required min="0" max="100"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Fechas y Cupos -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Fechas y Cupos
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            
                            <!-- Fecha Inicio -->
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-bold text-gray-700 mb-2">
                                    Fecha de Inicio <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Fecha Fin -->
                            <div>
                                <label for="fecha_fin" class="block text-sm font-bold text-gray-700 mb-2">
                                    Fecha de Fin <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Cupo Mínimo -->
                            <div>
                                <label for="cupo_minimo" class="block text-sm font-bold text-gray-700 mb-2">
                                    Cupo Mínimo <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="cupo_minimo" id="cupo_minimo" value="{{ old('cupo_minimo') }}" required min="1"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Cupo Máximo -->
                            <div>
                                <label for="cupo_maximo" class="block text-sm font-bold text-gray-700 mb-2">
                                    Cupo Máximo <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="cupo_maximo" id="cupo_maximo" value="{{ old('cupo_maximo') }}" required min="1"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Información Económica -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información Económica
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="max-w-md">
                            <label for="costo" class="block text-sm font-bold text-gray-700 mb-2">
                                Costo de Inscripción (S/) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">
                                    S/
                                </span>
                                <input type="number" step="0.01" name="costo" id="costo" value="{{ old('costo') }}" required min="0"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition pl-12 text-lg font-semibold">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Monto que pagarán los estudiantes por inscribirse</p>
                        </div>
                    </div>
                </div>

                <!-- Descripción y Contenido -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Descripción y Contenido del Curso
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-bold text-gray-700 mb-2">
                                Descripción General
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="3"
                                placeholder="Describe brevemente de qué trata el curso..."
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">{{ old('descripcion') }}</textarea>
                        </div>

                        <!-- Objetivos -->
                        <div>
                            <label for="objetivos" class="block text-sm font-bold text-gray-700 mb-2">
                                Objetivos del Curso
                            </label>
                            <textarea name="objetivos" id="objetivos" rows="3"
                                placeholder="¿Qué aprenderán los estudiantes?"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">{{ old('objetivos') }}</textarea>
                        </div>

                        <!-- Competencias -->
                        <div>
                            <label for="competencias" class="block text-sm font-bold text-gray-700 mb-2">
                                Competencias a Desarrollar
                            </label>
                            <textarea name="competencias" id="competencias" rows="3"
                                placeholder="Lista las competencias que adquirirán..."
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">{{ old('competencias') }}</textarea>
                        </div>

                        <!-- Temario -->
                        <div>
                            <label for="temario" class="block text-sm font-bold text-gray-700 mb-2">
                                Temario Completo
                            </label>
                            <textarea name="temario" id="temario" rows="6"
                                placeholder="Detalla los temas que se verán en el curso..."
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition font-mono text-sm">{{ old('temario') }}</textarea>
                        </div>

                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="p-6 flex justify-between items-center">
                        <a href="{{ route('cursos.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-lg font-bold text-sm text-gray-800 uppercase tracking-wide hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-blue-700 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition shadow-lg transform hover:scale-105"
                                style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Curso
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>