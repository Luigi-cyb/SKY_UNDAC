<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Certificado - UNDAC</title>
    
    <!-- Favicon UNDAC -->
    <link rel="icon" type="image/x-icon" href="https://extranet.undac.edu.pe/img/favicon.ico">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<div class="container mx-auto px-4 py-10">
    <div class="max-w-5xl mx-auto">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm mb-6 no-print border border-gray-200">
            <div class="flex items-center justify-between px-8 py-6">
                <div class="flex items-center gap-4">
                    <img src="https://extranet.undac.edu.pe/img/undac.png" alt="UNDAC" class="h-16">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">SKY UNDAC</h1>
                        <p class="text-sm text-gray-600">Sistema de Certificación Académica</p>
                    </div>
                </div>
            </div>
        </div>

      

        <!-- Card del Certificado -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            
            <!-- Banner superior -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-8 py-10 text-white text-center">
                <img src="https://extranet.undac.edu.pe/img/undac.png" alt="UNDAC" class="h-20 mx-auto mb-4 bg-white p-2 rounded-lg">
                <h2 class="text-4xl font-bold mb-2">CERTIFICADO</h2>
                <p class="text-sm text-gray-300 uppercase tracking-wider">De Competencia Profesional</p>
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <p class="text-sm text-gray-300">Universidad Nacional Daniel Alcides Carrión</p>
                </div>
            </div>

            <!-- Contenido -->
            <div class="px-10 py-12">
                <div class="text-center mb-10">
                    <p class="text-gray-600 mb-3">Se certifica que</p>
                    
                    <h3 class="text-3xl font-bold text-gray-900 mb-6 pb-4 border-b-2 border-gray-200 inline-block px-8">
                        {{ strtoupper($certificado->inscripcion->estudiante->nombres) }}
                        {{ strtoupper($certificado->inscripcion->estudiante->apellidos) }}
                    </h3>

                    <p class="text-gray-600 mb-6">Ha completado satisfactoriamente el curso de:</p>

                    <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg mb-8">
                        <h4 class="text-2xl font-bold text-gray-800 mb-2">
                            {{ strtoupper($certificado->inscripcion->curso->nombre) }}
                        </h4>
                        <p class="text-sm text-gray-600">Código: <span class="font-mono">{{ $certificado->inscripcion->curso->codigo }}</span></p>
                    </div>
                </div>

                <!-- Información del curso -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="text-center p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Calificación Final</p>
                        <p class="text-4xl font-bold text-gray-900">
                            {{ number_format($certificado->inscripcion->nota_final ?? 0, 2) }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">de 20 puntos</p>
                    </div>
                    <div class="text-center p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Horas Académicas</p>
                        <p class="text-4xl font-bold text-gray-900">
                            {{ $certificado->inscripcion->curso->horas_academicas ?? '24' }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">horas</p>
                    </div>
                    <div class="text-center p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Fecha de Emisión</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $certificado->fecha_emision->format('d/m/Y') }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">{{ $certificado->fecha_emision->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                <!-- Firmas -->
                <div class="border-t border-gray-200 pt-10 mt-10">
                    <p class="text-center text-gray-600 mb-8">
                        Cerro de Pasco, {{ $certificado->fecha_emision->translatedFormat('d \d\e F \d\e Y') }}
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 px-8">
                        <!-- Firma Decano -->
                        <div class="text-center">
                            <div class="border-t-2 border-gray-800 mb-3 pb-3 mx-auto w-56"></div>
                            <p class="text-sm font-bold text-gray-900">DR. NOMBRE DEL DECANO</p>
                            <p class="text-xs text-gray-600 mt-1">Decano</p>
                            <p class="text-xs text-gray-500">Universidad Nacional Daniel Alcides Carrión</p>
                        </div>
                        
                        <!-- Firma Director -->
                        <div class="text-center">
                            <div class="border-t-2 border-gray-800 mb-3 pb-3 mx-auto w-56"></div>
                            <p class="text-sm font-bold text-gray-900">{{ strtoupper($certificado->firmado_por ?? 'DIRECTOR DE SISTEMAS') }}</p>
                            <p class="text-xs text-gray-600 mt-1">Director de Sistemas</p>
                            <p class="text-xs text-gray-500">Universidad Nacional Daniel Alcides Carrión</p>
                        </div>
                    </div>
                </div>

                <!-- Sección de Códigos y QR -->
                <div class="mt-12 pt-10 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-6 text-center">Información de Verificación</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Códigos -->
                        <div class="space-y-4">
                            <div class="border border-gray-200 p-4 rounded-lg bg-gray-50">
                                <p class="text-xs text-gray-500 font-semibold mb-2">Código de Certificado</p>
                                <p class="font-mono font-bold text-gray-900">{{ $certificado->codigo_certificado }}</p>
                            </div>
                            <div class="border border-gray-200 p-4 rounded-lg bg-gray-50">
                                <p class="text-xs text-gray-500 font-semibold mb-2">Código de Verificación</p>
                                <p class="font-mono text-xs text-gray-900 break-all">{{ $certificado->codigo_qr }}</p>
                            </div>
                            <div class="border border-gray-300 p-4 rounded-lg bg-gray-50">
                                <p class="text-xs text-gray-500 font-semibold mb-2">Enlace de Verificación</p>
                                <a href="{{ url('/certificado/' . $certificado->codigo_qr) }}" 
                                   target="_blank"
                                   class="text-gray-700 hover:text-gray-900 text-xs break-all underline">
                                    {{ url('/certificado/' . $certificado->codigo_qr) }}
                                </a>
                            </div>
                        </div>

                        <!-- Código QR Visual -->
                        <div class="flex flex-col items-center justify-center border border-gray-200 p-6 rounded-lg bg-gray-50">
                            <p class="text-sm text-gray-700 font-semibold mb-4">Escanea para verificar</p>
                            <div class="bg-white p-3 border border-gray-300 rounded">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(url('/certificado/' . $certificado->codigo_qr)) }}" 
                                     alt="Código QR" 
                                     class="w-48 h-48">
                            </div>
                            <p class="text-xs text-gray-500 mt-4 text-center max-w-xs">
                                Este código QR redirige a esta página de verificación
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="mt-10 flex gap-3 justify-center flex-wrap no-print">
                    <a href="{{ route('certificados.descargar-pdf', $certificado->id) }}" 
                       class="bg-gray-800 hover:bg-gray-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar PDF
                    </a>
                    <button onclick="window.print()" 
                            class="bg-gray-600 hover:bg-gray-500 text-white font-medium py-2.5 px-6 rounded-lg transition-colors inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir
                    </button>
                    <button onclick="compartir()" 
                            class="bg-gray-700 hover:bg-gray-600 text-white font-medium py-2.5 px-6 rounded-lg transition-colors inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        Compartir
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-8 py-5 text-center">
                <div class="flex items-center justify-center gap-2 text-gray-700 font-semibold mb-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Certificado Verificado
                </div>
                <p class="text-sm text-gray-600">Este certificado puede ser verificado en línea mediante el código QR o el enlace de verificación</p>
                <p class="text-xs text-gray-500 mt-3">Verificado: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Enlace de retorno -->
        <div class="text-center mt-8 no-print">
            <a href="/" class="text-gray-700 hover:text-gray-900 font-medium inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al inicio
            </a>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function copiarEnlace() {
        const input = document.getElementById('urlPublica');
        input.select();
        input.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(input.value).then(() => {
            const mensaje = document.getElementById('copiadoExito');
            mensaje.classList.remove('hidden');
            
            setTimeout(() => {
                mensaje.classList.add('hidden');
            }, 3000);
        }).catch(() => {
            alert('No se pudo copiar el enlace');
        });
    }

    function compartir() {
        const url = document.getElementById('urlPublica').value;
        const titulo = 'Certificado Académico - UNDAC';
        const texto = 'Verificar certificado de {{ $certificado->inscripcion->curso->nombre }}';
        
        if (navigator.share) {
            navigator.share({
                title: titulo,
                text: texto,
                url: url
            }).catch(() => {});
        } else {
            copiarEnlace();
        }
    }
</script>

</body>
</html>