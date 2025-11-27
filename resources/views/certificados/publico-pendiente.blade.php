<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado en Proceso - UNDAC</title>
    <link rel="icon" type="image/x-icon" href="https://extranet.undac.edu.pe/img/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <img src="https://extranet.undac.edu.pe/img/undac.png" alt="UNDAC" class="h-20 mx-auto">
            </div>

            <div class="mb-6">
                <svg class="w-20 h-20 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-4">Certificado en Proceso</h2>
            
            <p class="text-gray-600 mb-6">
                {{ $mensaje }}
            </p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <strong>Código:</strong> <span class="font-mono">{{ $codigo }}</span>
                </p>
                <p class="text-xs text-yellow-700 mt-2">
                    El certificado estará disponible una vez completado el proceso de firma digital oficial.
                </p>
            </div>

            <a href="/" class="text-blue-600 hover:text-blue-800 font-medium">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>