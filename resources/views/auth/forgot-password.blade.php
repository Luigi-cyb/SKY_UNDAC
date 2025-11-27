<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>SKY-UNDAC | Recuperar Contraseña</title>
    
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
            background-image: url('/images/undac1.jpg');
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
    <div class="min-h-screen flex items-center justify-center bg-login p-4">
        
        <!-- Contenedor principal -->
        <div class="relative z-10 w-full" style="max-width: 384px;">
            <div class="glass-card rounded-2xl shadow-2xl p-8">
                
                <!-- Logo y Encabezado -->
                <div class="text-center mb-6">
                    <!-- Logo SKYUNDAC -->
                    <div class="mx-auto h-16 w-16 bg-white rounded-xl flex items-center justify-center shadow-lg mb-4 border-2 border-gray-200">
                        <svg class="h-9 w-9 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        ¿Olvidaste tu contraseña?
                    </h1>
                    <p class="text-sm text-gray-600">
                        Ingresa tu correo institucional y te enviaremos un enlace para restablecerla.
                    </p>
                </div>

                <!-- Mensaje de éxito -->
                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-green-700 font-medium">¡Enlace enviado! Revisa tu correo.</p>
                        </div>
                    </div>
                @endif

                <!-- Formulario -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Correo Institucional
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
                                   autofocus
                                   placeholder="correo@undac.edu.pe"
                                   class="input-with-icon block w-full py-2.5 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón de Enviar -->
                    <button type="submit" 
                        class="w-full flex justify-center items-center py-2.5 px-4 mt-5 border-2 border-blue-700 rounded-lg shadow-md text-sm font-bold text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 transition transform hover:scale-[1.01]">                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Enviar Enlace de Recuperación
                    </button>
                </form>

                <!-- Separador y Volver -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-500 font-medium">¿Recordaste tu contraseña?</span>
                        </div>
                    </div>

                    <!-- Botón de Volver al Login -->
                    <div class="mt-4">
                        <a href="{{ route('login') }}" 
                           class="w-full flex justify-center items-center py-2.5 px-4 border-2 border-blue-700 rounded-lg text-sm font-bold text-blue-700 hover:bg-blue-50 transition">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Volver a Iniciar Sesión
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