<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago con Yape</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Encabezado -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Pago con Yape</h1>
                <p class="text-gray-600 mt-2">Completa tu inscripción rápidamente</p>
            </div>

            <!-- Card de Detalles -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="border-b pb-4 mb-4">
                    <p class="text-gray-600 text-sm">Código de Inscripción</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $inscripcion->codigo_inscripcion }}</p>
                </div>

                <div class="border-b pb-4 mb-4">
                    <p class="text-gray-600 text-sm">Curso</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $inscripcion->curso->nombre }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-gray-600 text-sm">Fecha de Inscripción</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $inscripcion->fecha_inscripcion->format('d/m/Y') }}</p>
                </div>

                <!-- Monto a Pagar -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border-2 border-green-200">
                    <p class="text-gray-600 text-sm mb-2">Monto a Pagar</p>
                    <p class="text-4xl font-bold text-green-600">S/. {{ number_format($inscripcion->curso->costo_inscripcion, 2) }}</p>
                </div>
            </div>

            <!-- Instrucciones Yape -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded">
                <h3 class="font-semibold text-yellow-800 mb-2">📱 Instrucciones de Pago</h3>
                <ol class="text-sm text-yellow-700 space-y-2 list-decimal list-inside">
                    <li>Abre tu aplicación <strong>Yape</strong> en tu celular</li>
                    <li>Selecciona <strong>"Enviar dinero"</strong></li>
                    <li>Ingresa el número: <strong style="font-size: 18px; color: #065f46;">975609083</strong></li>
                    <li>Ingresa el monto: <strong>S/. {{ number_format($inscripcion->curso->costo_inscripcion, 2) }}</strong></li>
                    <li>Completa el pago en Yape</li>
                    <li><strong>Copia el código de validación</strong> (6 dígitos que Yape te mostrará)</li>
                    <li>Pégalo en el campo de abajo y presiona "Confirmar Pago"</li>
                </ol>
            </div>

            <!-- Formulario de Validación -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form action="{{ route('estudiantes.pago.registrar', $inscripcion->id) }}" method="POST">
                    @csrf

                    <!-- Código de Validación Yape -->
                    <!-- Formulario de Validación -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form action="{{ route('estudiantes.pago.registrar', $inscripcion->id) }}" method="POST">
                    @csrf

                    <!-- Código de Validación Yape -->
                    <div class="mb-6">
                        <label for="codigo_validacion" class="block text-gray-700 font-semibold mb-2">
                            Código de Validación Yape <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="codigo_validacion" 
                            id="codigo_validacion" 
                            class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-center text-2xl tracking-widest"
                            placeholder="123456"
                            required>
                        <p class="text-gray-500 text-xs mt-2">Ingresa 6 dígitos (Ej: 123456)</p>
                        @error('codigo_validacion')
                            <p class="text-red-500 text-sm mt-1">❌ {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón Enviar -->
                    <button type="submit" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg">
                        ✓ Confirmar Pago
                    </button>

                    <!-- Enlace de Retorno -->
                    <div class="text-center">
                        <a href="{{ route('estudiantes.mis-cursos') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            ← Volver a Mis Cursos
                        </a>
                    </div>
                </form>
            </div>

            <!-- Nota de Seguridad -->
            <div class="mt-6 text-center text-gray-600 text-xs">
                <p>🔒 Tu información está protegida. Nunca compartiremos tus datos.</p>
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    @foreach($errors->all() as $error)
                        <p>✗ {{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-formatea el código de validación (solo números)
        document.getElementById('codigo_validacion').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    </script>
</body>
</html>