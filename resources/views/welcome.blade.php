<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>SKY-UNDAC | Página Principal</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://extranet.undac.edu.pe/img/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="https://extranet.undac.edu.pe/img/favicon.ico">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-bg {
            background-image: url('images/undac1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <div class="min-h-screen hero-bg flex items-center justify-center">
        <div class="relative z-10 text-center px-4">
            
            <!-- Logo -->
            <div class="mx-auto h-24 w-24 bg-white rounded-2xl flex items-center justify-center shadow-2xl mb-8 p-2">
                <img src="https://extranet.undac.edu.pe/img/undac.png" 
                     alt="Logo UNDAC" 
                     class="h-full w-full object-contain">
            </div>

            <!-- Título -->
            <h1 class="text-5xl md:text-6xl font-black text-white mb-6 drop-shadow-lg">
                Página Principal
            </h1>

            <!-- Subtítulo -->
            <p class="text-xl md:text-2xl text-white font-semibold mb-12 drop-shadow-lg">
                Programa de Iniciación Tecnológica
            </p>
            <!-- Botón de Acceder -->
            <a href="{{ route('login') }}" 
               class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 rounded-xl font-bold text-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-2xl">
                <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Acceder
            </a>

            <!-- Footer -->
            <div class="mt-16">
                <p class="text-white text-sm font-semibold drop-shadow-lg">
                    Universidad Nacional Daniel Alcides Carrión
                </p>
                <p class="text-white text-opacity-90 text-xs mt-2 drop-shadow-lg">
                    Sistema SKY-UNDAC © {{ date('Y') }}
                </p>
            </div>

        </div>
    </div>
</body>
</html>