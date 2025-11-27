<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    ➕ Registrar Nuevo Estudiante
                </h2>
                <p class="text-sm text-gray-600 mt-1">Completa la información del nuevo estudiante</p>
            </div>
            <a href="{{ route('estudiantes.index') }}" 
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

            <form action="{{ route('estudiantes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Datos Personales -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
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
                                <input type="text" name="dni" id="dni" value="{{ old('dni') }}" required maxlength="8"
                                    placeholder="Ej: 72556477"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                                <p class="mt-1 text-xs text-gray-500">8 dígitos</p>
                            </div>

                            <!-- Código Universitario -->
                            <div>
                                <label for="codigo_estudiante" class="block text-sm font-bold text-gray-700 mb-2">
                                    Código Universitario
                                </label>
                                <input type="text" name="codigo_estudiante" id="codigo_estudiante" value="{{ old('codigo_estudiante') }}" maxlength="20"
                                    placeholder="Ej: 2021100145"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                            </div>

                            <!-- Nombres -->
                            <div>
                                <label for="nombres" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nombres <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}" required
                                    placeholder="Ej: Juan Carlos"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                            </div>

                            <!-- Apellidos -->
                            <div>
                                <label for="apellidos" class="block text-sm font-bold text-gray-700 mb-2">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}" required
                                    placeholder="Ej: Pérez García"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition">
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div>
                                <label for="fecha_nacimiento" class="block text-sm font-bold text-gray-700 mb-2">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
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
                                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
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
                                <div class="relative">
                                    <input type="email" name="correo_institucional" id="correo_institucional" value="{{ old('correo_institucional') }}" required
                                        placeholder="estudiante@undac.edu.pe"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition pl-10">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Este será el correo para iniciar sesión</p>
                            </div>

                            <!-- Correo Personal -->
                            <div>
                                <label for="correo_personal" class="block text-sm font-bold text-gray-700 mb-2">
                                    Correo Personal
                                </label>
                                <input type="email" name="correo_personal" id="correo_personal" value="{{ old('correo_personal') }}"
                                    placeholder="ejemplo@gmail.com"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label for="telefono" class="block text-sm font-bold text-gray-700 mb-2">
                                    Teléfono
                                </label>
                                <div class="relative">
                                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" maxlength="15"
                                        placeholder="987654321"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition pl-10">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Teléfono de Emergencia -->
                            <div>
                                <label for="telefono_emergencia" class="block text-sm font-bold text-gray-700 mb-2">
                                    Teléfono de Emergencia
                                </label>
                                <input type="text" name="telefono_emergencia" id="telefono_emergencia" value="{{ old('telefono_emergencia') }}" maxlength="15"
                                    placeholder="999888777"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Dirección -->
                            <div class="md:col-span-2">
                                <label for="direccion" class="block text-sm font-bold text-gray-700 mb-2">
                                    Dirección
                                </label>
                                <textarea name="direccion" id="direccion" rows="2"
                                    placeholder="Av. Daniel Alcides Carrión 123, Pasco"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">{{ old('direccion') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Información Académica -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Información Académica
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Pertenece a EISC -->
                            <div>
                                <label for="pertenece_eisc" class="block text-sm font-bold text-gray-700 mb-2">
                                    ¿Pertenece a EISC? <span class="text-red-500">*</span>
                                </label>
                                <select name="pertenece_eisc" id="pertenece_eisc" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="1" {{ old('pertenece_eisc', '1') == '1' ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ old('pertenece_eisc') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <!-- Ciclo Académico -->
                            <div>
                                <label for="ciclo_academico" class="block text-sm font-bold text-gray-700 mb-2">
                                    Ciclo Académico
                                </label>
                                <input type="text" name="ciclo_academico" id="ciclo_academico" value="{{ old('ciclo_academico') }}" maxlength="10"
                                    placeholder="Ej: VIII"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 transition">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Foto de Perfil -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Foto de Perfil
                        </h3>
                    </div>
                    <div class="p-6">
                        <label for="foto" class="block text-sm font-bold text-gray-700 mb-2">
                            Subir Foto (Opcional)
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                        <p class="mt-2 text-xs text-gray-500">JPG, JPEG o PNG. Máximo 2MB</p>
                    </div>
                </div>

                <!-- Credenciales de Acceso -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4" style="background: linear-gradient(to right, #dc2626, #b91c1c) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Credenciales de Acceso
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Contraseña -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">
                                    Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password" required minlength="8"
                                    placeholder="Mínimo 8 caracteres"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500 transition">
                                <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">
                                    Confirmar Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                                    placeholder="Repite la contraseña"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500 transition">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="p-6 flex justify-between items-center">
                        <a href="{{ route('estudiantes.index') }}" 
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Registrar Estudiante
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>