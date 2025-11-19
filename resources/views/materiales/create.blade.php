<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Subir Nuevo Material') }}
            </h2>
            <a href="{{ route('materiales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('materiales.store') }}" method="POST" enctype="multipart/form-data" id="formMaterial">
                        @csrf

                        <!-- Información del Curso -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Información del Curso
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Curso -->
                                <div class="md:col-span-2">
                                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Curso <span class="text-red-500">*</span>
                                    </label>
                                    <select name="curso_id" id="curso_id" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Seleccione un curso</option>
                                        @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }} - {{ $curso->modalidad->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('curso_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sesión/Unidad -->
                                <div>
                                    <label for="sesion_numero" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sesión/Unidad
                                    </label>
                                    <input type="number" name="sesion_numero" id="sesion_numero" min="1"
                                           value="{{ old('sesion_numero') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Ej: 1, 2, 3...">
                                    @error('sesion_numero')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">
                                        Número de sesión o unidad temática
                                    </p>
                                </div>

                                <!-- Orden -->
                                <div>
                                    <label for="orden" class="block text-sm font-medium text-gray-700 mb-2">
                                        Orden de Visualización
                                    </label>
                                    <input type="number" name="orden" id="orden" min="1"
                                           value="{{ old('orden', 1) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="1">
                                    @error('orden')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información del Material -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Datos del Material
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Título -->
                                <div class="md:col-span-2">
                                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Título del Material <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="titulo" id="titulo" required
                                           value="{{ old('titulo') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Ej: Introducción a Laravel">
                                    @error('titulo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div class="md:col-span-2">
                                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descripción
                                    </label>
                                    <textarea name="descripcion" id="descripcion" rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Descripción breve del contenido del material...">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tipo de Material -->
                                <div>
                                    <label for="tipo_material" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Material <span class="text-red-500">*</span></label>
                                    <select name="tipo_material" id="tipo_material" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Seleccione un tipo</option>
                                        <option value="documento" {{ old('tipo_material') == 'documento' ? 'selected' : '' }}>Documento (PDF, Word, etc.)</option>
                                        <option value="presentacion" {{ old('tipo_material') == 'presentacion' ? 'selected' : '' }}>Presentación (PPT, Slides)</option>
                                        <option value="video" {{ old('tipo_material') == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="enlace" {{ old('tipo_material') == 'enlace' ? 'selected' : '' }}>Enlace Externo</option>
                                        <option value="otro" {{ old('tipo_material') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('tipo_material')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Visibilidad -->
                            <div>
                                <label for="visibilidad" class="block text-sm font-medium text-gray-700 mb-2">
                                    Visibilidad <span class="text-red-500">*</span>
                                </label>
                                <select name="visibilidad" id="visibilidad" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="publico" {{ old('visibilidad', 'publico') == 'publico' ? 'selected' : '' }}>Público (Todos los inscritos)</option>
                                    <option value="programado" {{ old('visibilidad') == 'programado' ? 'selected' : '' }}>Programado</option>
                                    <option value="restringido" {{ old('visibilidad') == 'restringido' ? 'selected' : '' }}>Restringido</option>
                                </select>
                                @error('visibilidad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fecha de Publicación (solo si es programado) -->
                            <div id="fecha_publicacion_container" class="md:col-span-2 hidden">
                                <label for="fecha_publicacion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Publicación
                                </label>
                                <input type="datetime-local" name="fecha_publicacion" id="fecha_publicacion"
                                       value="{{ old('fecha_publicacion') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('fecha_publicacion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    El material estará disponible a partir de esta fecha
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Subir Archivo o Enlace -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Contenido del Material
                        </h3>

                        <div class="space-y-6">
                            <!-- Subir Archivo -->
                            <div id="archivo_container">
                                <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subir Archivo
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="archivo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Subir un archivo</span>
                                                <input id="archivo" name="archivo" type="file" class="sr-only" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar">
                                            </label>
                                            <p class="pl-1">o arrastra y suelta</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PDF, DOC, PPT, XLS, ZIP hasta 50MB
                                        </p>
                                    </div>
                                </div>
                                @error('archivo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="archivo_preview" class="mt-4 hidden">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900" id="archivo_nombre">-</p>
                                                    <p class="text-xs text-gray-500" id="archivo_tamano">-</p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="limpiarArchivo()" class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enlace Externo -->
                            <div id="enlace_container" class="hidden">
                                <label for="enlace_externo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Enlace Externo (URL)
                                </label>
                                <input type="url" name="enlace_externo" id="enlace_externo"
                                       value="{{ old('enlace_externo') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="https://ejemplo.com/recurso">
                                @error('enlace_externo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    Ingrese la URL completa del recurso externo (YouTube, Google Drive, etc.)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Configuraciones Adicionales -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Configuraciones Adicionales
                        </h3>

                        <div class="space-y-4">
                            <!-- Permitir Descarga -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="permite_descarga" id="permite_descarga" 
                                           value="1" {{ old('permite_descarga', true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="ml-3">
                                    <label for="permite_descarga" class="font-medium text-gray-700">
                                        Permitir Descarga
                                    </label>
                                    <p class="text-sm text-gray-500">
                                        Los estudiantes podrán descargar este material
                                    </p>
                                </div>
                            </div>

                            <!-- Es Obligatorio -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="es_obligatorio" id="es_obligatorio" 
                                           value="1" {{ old('es_obligatorio') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="ml-3">
                                    <label for="es_obligatorio" class="font-medium text-gray-700">
                                        Material Obligatorio
                                    </label>
                                    <p class="text-sm text-gray-500">
                                        Marcar como material de lectura/visualización obligatoria
                                    </p>
                                </div>
                            </div>

                            <!-- Notificar a Estudiantes -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="notificar_estudiantes" id="notificar_estudiantes" 
                                           value="1" {{ old('notificar_estudiantes', true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="ml-3">
                                    <label for="notificar_estudiantes" class="font-medium text-gray-700">
                                        Notificar a Estudiantes
                                    </label>
                                    <p class="text-sm text-gray-500">
                                        Enviar notificación por correo sobre este nuevo material
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('materiales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Subir Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoMaterialSelect = document.getElementById('tipo_material');
        const archivoContainer = document.getElementById('archivo_container');
        const enlaceContainer = document.getElementById('enlace_container');
        const visibilidadSelect = document.getElementById('visibilidad');
        const fechaPublicacionContainer = document.getElementById('fecha_publicacion_container');

        // Manejar cambio de tipo de material
        tipoMaterialSelect.addEventListener('change', function() {
            if (this.value === 'enlace') {
                archivoContainer.classList.add('hidden');
                enlaceContainer.classList.remove('hidden');
            } else {
                archivoContainer.classList.remove('hidden');
                enlaceContainer.classList.add('hidden');
            }
        });

        // Manejar cambio de visibilidad
        visibilidadSelect.addEventListener('change', function() {
            if (this.value === 'programado') {
                fechaPublicacionContainer.classList.remove('hidden');
            } else {
                fechaPublicacionContainer.classList.add('hidden');
            }
        });

        // Vista previa del archivo
        const archivoInput = document.getElementById('archivo');
        const archivoPreview = document.getElementById('archivo_preview');
        const archivoNombre = document.getElementById('archivo_nombre');
        const archivoTamano = document.getElementById('archivo_tamano');

        archivoInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                
                archivoNombre.textContent = file.name;
                archivoTamano.textContent = sizeMB + ' MB';
                archivoPreview.classList.remove('hidden');
            }
        });

        // Validación del formulario
        const form = document.getElementById('formMaterial');
        form.addEventListener('submit', function(e) {
            const tipoMaterial = tipoMaterialSelect.value;
            const archivo = archivoInput.files.length > 0;
            const enlace = document.getElementById('enlace_externo').value.trim();

            if (tipoMaterial === 'enlace' && !enlace) {
                e.preventDefault();
                alert('Por favor, ingrese un enlace externo');
                return false;
            }

            if (tipoMaterial !== 'enlace' && !archivo) {
                e.preventDefault();
                alert('Por favor, seleccione un archivo para subir');
                return false;
            }

            return true;
        });
    });

    function limpiarArchivo() {
        document.getElementById('archivo').value = '';
        document.getElementById('archivo_preview').classList.add('hidden');
    }
</script>
@endpush
</x-app-layout>
