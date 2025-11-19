<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>SKY-UNDAC | Registro</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://extranet.undac.edu.pe/img/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="https://extranet.undac.edu.pe/img/favicon.ico">
    <link rel="apple-touch-icon" href="https://extranet.undac.edu.pe/img/favicon.ico">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .bg-login {
            background-image: url('images/undac1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .bg-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.4) 0%, rgba(37, 99, 235, 0.3) 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
        }
        .input-with-icon {
            padding-left: 2.75rem;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center bg-login p-4 py-8">
        
        <!-- Contenedor principal -->
        <div class="relative z-10 w-full" style="max-width: 500px;">
            <div class="glass-card rounded-2xl shadow-2xl p-8">
                
                <!-- Logo y Encabezado -->
                <div class="text-center mb-6">
                    <!-- Logo UNDAC -->
                    <div class="mx-auto h-16 w-16 bg-white rounded-xl flex items-center justify-center shadow-lg mb-4 border-2 border-gray-200 p-2">
                        <img src="https://extranet.undac.edu.pe/img/undac.png" 
                             alt="Logo UNDAC" 
                             class="h-full w-full object-contain">
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">
                        Registro de Estudiante
                    </h1>
                    <p class="text-sm text-gray-600 font-medium mb-0.5">
                        Sistema SKYUNDAC
                    </p>
                    <p class="text-xs text-gray-500">
                        Universidad Nacional Daniel Alcides Carrión
                    </p>
                </div>

                <!-- Formulario de Registro -->
                <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                    @csrf

                    <!-- Nombres -->
                    <div>
                        <label for="nombres" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nombres <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <input id="nombres" 
                                   type="text" 
                                   name="nombres" 
                                   value="{{ old('nombres') }}" 
                                   required 
                                   autofocus 
                                   placeholder="Juan Carlos"
                                   class="input-with-icon block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('nombres') border-red-500 @enderror">
                        </div>
                        @error('nombres')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Apellidos -->
                    <div>
                        <label for="apellidos" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Apellidos <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input id="apellidos" 
                                   type="text" 
                                   name="apellidos" 
                                   value="{{ old('apellidos') }}" 
                                   required 
                                   placeholder="Pérez García"
                                   class="input-with-icon block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('apellidos') border-red-500 @enderror">
                        </div>
                        @error('apellidos')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DNI y Fecha de Nacimiento en una fila -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- DNI -->
                        <div>
                            <label for="dni" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                DNI <span class="text-red-500">*</span>
                            </label>
                            <input id="dni" 
                                   type="text" 
                                   name="dni" 
                                   value="{{ old('dni') }}" 
                                   required 
                                   maxlength="8"
                                   pattern="[0-9]{8}"
                                   placeholder="12345678"
                                   class="block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('dni') border-red-500 @enderror">
                            @error('dni')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                F. Nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input id="fecha_nacimiento" 
                                   type="date" 
                                   name="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento') }}" 
                                   required 
                                   max="{{ date('Y-m-d', strtotime('-15 years')) }}"
                                   class="block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('fecha_nacimiento') border-red-500 @enderror">
                            @error('fecha_nacimiento')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Sexo y Teléfono en una fila -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Sexo -->
                        <div>
                            <label for="sexo" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Sexo <span class="text-red-500">*</span>
                            </label>
                            <select id="sexo" 
                                    name="sexo" 
                                    required 
                                    class="block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('sexo') border-red-500 @enderror">
                                <option value="">Seleccionar</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sexo')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input id="telefono" 
                                   type="text" 
                                   name="telefono" 
                                   value="{{ old('telefono') }}" 
                                   required 
                                   maxlength="9"
                                   pattern="[0-9]{9}"
                                   placeholder="987654321"
                                   class="block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="username"
                                   placeholder="correo@undac.edu.pe"
                                   class="input-with-icon block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="input-with-icon block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('password') border-red-500 @enderror">
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Confirmar Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="input-with-icon block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
                        </div>
                    </div>

                    <!-- Botón de Registro -->
                    <button type="submit" 
                            class="w-full flex justify-center items-center py-2.5 px-4 mt-5 border-2 border-blue-700 rounded-lg shadow-md text-sm font-bold text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 transition transform hover:scale-[1.01]">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Crear Cuenta
                    </button>
                </form>

                <!-- Separador y Login -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-500 font-medium">¿Ya tienes cuenta?</span>
                        </div>
                    </div>

                    <!-- Botón de Login -->
                    <div class="mt-4">
                        <a href="{{ route('login') }}" 
                           class="w-full flex justify-center items-center py-2.5 px-4 border-2 border-blue-700 rounded-lg text-sm font-bold text-blue-700 hover:bg-blue-50 transition">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Iniciar Sesión
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 pt-5 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-500 font-medium">
                        © {{ date('Y') }} UNDAC - EISC
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Sistema SKYUNDAC v1.0
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>