<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    ✏️ Editar Estudiante
                </h2>
                <p class="text-sm text-gray-600 mt-1">Modifica la información del estudiante: <span class="font-semibold">{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</span></p>
            </div>
            <a href="{{ route('estudiantes.show', $estudiante) }}" 
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

            <form action="{{ route('estudiantes.update', $estudiante) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Datos Personales -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #15803d, #166534) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Datos Personales
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- DNI -->
                            <div>
                                <label for="dni" class="block text-sm font-bold text-gray-700 mb-2">
                                    DNI <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="dni" id="dni" value="{{ old('dni', $estudiante->dni) }}" required maxlength="8"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                            </div>

                            <!-- Código Universitario -->
                            <div>
                                <label for="codigo_estudiante" class="block text-sm font-bold text-gray-700 mb-2">
                                    Código Universitario
                                </label>
                                <input type="text" name="codigo_estudiante" id="codigo_estudiante" value="{{ old('codigo_estudiante', $estudiante->codigo_estudiante) }}" maxlength="20"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                            </div>

                            <!-- Nombres -->
                            <div>
                                <label for="nombres" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nombres <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nombres" id="nombres" value="{{ old('nombres', $estudiante->nombres) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                            </div>

                            <!-- Apellidos -->
                            <div>
                                <label for="apellidos" class="block text-sm font-bold text-gray-700 mb-2">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', $estudiante->apellidos) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div>
                                <label for="fecha_nacimiento" class="block text-sm font-bold text-gray-700 mb-2">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                    value="{{ old('fecha_nacimiento', $estudiante->fecha_nacimiento ? \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('Y-m-d') : '') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                            </div>

                            <!-- Sexo -->
                            <div>
                                <label for="sexo" class="block text-sm font-bold text-gray-700 mb-2">
                                    Sexo
                                </label>
                                <select name="sexo" id="sexo"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                                    <option value="">Seleccione</option>
                                    <option value="M" {{ old('sexo', $estudiante->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo', $estudiante->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #2563eb, #1e40af) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Información de Contacto
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Correo Institucional -->
                            <div>
                                <label for="correo_institucional" class="block text-sm font-bold text-gray-700 mb-2">
                                    Correo Institucional <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="correo_institucional" id="correo_institucional" value="{{ old('correo_institucional', $estudiante->correo_institucional) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Correo Personal -->
                            <div>
                                <label for="correo_personal" class="block text-sm font-bold text-gray-700 mb-2">
                                    Correo Personal
                                </label>
                                <input type="email" name="correo_personal" id="correo_personal" value="{{ old('correo_personal', $estudiante->correo_personal) }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label for="telefono" class="block text-sm font-bold text-gray-700 mb-2">
                                    Teléfono
                                </label>
                                <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $estudiante->telefono) }}" maxlength="15"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Teléfono de Emergencia -->
                            <div>
                                <label for="telefono_emergencia" class="block text-sm font-bold text-gray-700 mb-2">
                                    Teléfono de Emergencia
                                </label>
                                <input type="text" name="telefono_emergencia" id="telefono_emergencia" value="{{ old('telefono_emergencia', $estudiante->telefono_emergencia) }}" maxlength="15"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Dirección -->
                            <div class="md:col-span-2">
                                <label for="direccion" class="block text-sm font-bold text-gray-700 mb-2">
                                    Dirección
                                </label>
                                <textarea name="direccion" id="direccion" rows="2"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">{{ old('direccion', $estudiante->direccion) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Información Académica -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #9333ea, #7e22ce) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Información Académica
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Pertenece a EISC -->
                            <div>
                                <label for="pertenece_eisc" class="block text-sm font-bold text-gray-700 mb-2">
                                    ¿Pertenece a EISC? <span class="text-red-500">*</span>
                                </label>
                                <select name="pertenece_eisc" id="pertenece_eisc" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="1" {{ old('pertenece_eisc', $estudiante->pertenece_eisc) == '1' ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ old('pertenece_eisc', $estudiante->pertenece_eisc) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <!-- Ciclo Académico -->
                            <div>
                                <label for="ciclo_academico" class="block text-sm font-bold text-gray-700 mb-2">
                                    Ciclo Académico
                                </label>
                                <input type="text" name="ciclo_academico" id="ciclo_academico" value="{{ old('ciclo_academico', $estudiante->ciclo_academico) }}" maxlength="10"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="activo" class="block text-sm font-bold text-gray-700 mb-2">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="activo" id="activo" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="1" {{ old('activo', $estudiante->activo) == '1' ? 'selected' : '' }}>✅ Activo</option>
                                    <option value="0" {{ old('activo', $estudiante->activo) == '0' ? 'selected' : '' }}>❌ Inactivo</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Foto de Perfil -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Foto de Perfil
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($estudiante->foto_url)
                        <div class="mb-4 flex items-center space-x-4">
                            <img src="{{ asset('storage/' . $estudiante->foto_url) }}" alt="Foto actual" class="h-24 w-24 rounded-full object-cover border-4 border-indigo-200 shadow-lg">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Foto Actual</p>
                                <p class="text-xs text-gray-500">Puedes cambiarla subiendo una nueva imagen</p>
                            </div>
                        </div>
                        @endif
                        <label for="foto" class="block text-sm font-bold text-gray-700 mb-2">
                            Cambiar Foto de Perfil
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                        <p class="mt-2 text-xs text-gray-500">JPG, JPEG o PNG. Máximo 2MB</p>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="p-6 flex justify-between items-center">
                        <a href="{{ route('estudiantes.show', $estudiante) }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-lg font-bold text-sm text-gray-800 uppercase tracking-wide hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 transition shadow-lg transform hover:scale-105"
                                style="background: linear-gradient(to right, #15803d, #166534) !important; color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Actualizar Estudiante
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>