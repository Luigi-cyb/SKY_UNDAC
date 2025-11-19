<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle del Certificado
            </h2>
            <a href="{{ route('certificados.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @php
                $estudiante = $certificado->inscripcion->estudiante;
                $curso = $certificado->inscripcion->curso;
                $notaFinal = $certificado->inscripcion->calificaciones->avg('nota') ?? 0;
                $urlPublica = url('/certificado/' . $certificado->codigo_qr);
            @endphp

            <!-- ⭐ NUEVO: Banner con Enlace Público ⭐ -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-xl p-6 mb-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white flex items-center mb-2">
                            🌐 Enlace Público del Certificado
                        </h3>
                        <p class="text-blue-100 text-sm mb-3">
                            Comparte este enlace para que cualquiera pueda verificar el certificado
                        </p>
                        
                        <!-- Input con el enlace -->
                        <div class="flex gap-2">
                            <input type="text" 
       id="urlPublica" 
       value="{{ $urlPublica }}"
       class="flex-1 px-4 py-2 rounded-lg bg-white text-gray-800 font-mono text-sm">
                            
                            <button onclick="copiarEnlace(event)"
                                    class="bg-white text-blue-600 hover:bg-blue-50 font-bold py-2 px-6 rounded-lg transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Copiar
                            </button>
                            
                            <a href="{{ $urlPublica }}" 
                               target="_blank"
                               class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Certificado -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        🎓 Certificado #{{ $certificado->codigo_certificado }}
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Columna Izquierda -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Código del Certificado</label>
                                <p class="mt-1 text-lg font-bold text-gray-900">{{ $certificado->codigo_certificado }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Código QR</label>
                                <p class="mt-1 text-base text-gray-900 font-mono">{{ $certificado->codigo_qr }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Estudiante</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ $estudiante->nombres }} {{ $estudiante->apellidos }}
                                </p>
                                <p class="text-sm text-gray-500">DNI: {{ $estudiante->dni }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Curso</label>
                                <p class="mt-1 text-base font-semibold text-gray-900">{{ $curso->nombre }}</p>
                                <p class="text-sm text-gray-500">{{ $curso->codigo }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nota Final</label>
                                <p class="mt-1 text-3xl font-bold text-green-600">{{ number_format($notaFinal, 2) }}</p>
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Fecha de Emisión</label>
                                <p class="mt-1 text-base text-gray-900">{{ $certificado->fecha_emision->format('d/m/Y') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Estado</label>
                                <p class="mt-1">
                                    @if($certificado->estado == 'emitido')
                                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ Emitido
                                        </span>
                                    @elseif($certificado->estado == 'pendiente')
                                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ Pendiente
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                            ❌ Revocado
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Firmado por</label>
                                <p class="mt-1 text-base text-gray-900">{{ $certificado->firmado_por ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Descargas</label>
                                <p class="mt-1 text-base text-gray-900">{{ $certificado->numero_veces_descargado ?? 0 }} veces</p>
                                @if($certificado->ultima_descarga)
                                    <p class="text-sm text-gray-500">Última: {{ $certificado->ultima_descarga->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>

                            @if($certificado->observaciones)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Observaciones</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $certificado->observaciones }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- ⭐ Código QR Visual CON ENLACE ⭐ -->
                    <div class="mt-6 border-t pt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Código QR para Validación</label>
                        <div class="flex flex-col items-center justify-center bg-gray-50 p-6 rounded-lg">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($urlPublica) }}" 
                                 alt="Código QR" 
                                 class="border-4 border-white shadow-lg">
                            <p class="text-center text-sm text-gray-500 mt-4">Escanea este código para ver el certificado público</p>
                            <p class="text-center text-xs text-gray-400 mt-2 font-mono">{{ $certificado->codigo_qr }}</p>
                            
                            <!-- ⭐ ENLACE CLICKEABLE DEBAJO DEL QR ⭐ -->
                            <a href="{{ $urlPublica }}" 
                               target="_blank" 
                               class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium underline break-all text-center">
                                🔗 {{ $urlPublica }}
                            </a>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="mt-6 flex gap-4">
                        @if($certificado->pdf_url)
                          <a href="{{ route('certificados.descargar-pdf', $certificado) }}"
   class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-center">
    📥 Descargar PDF
</a>
                        @else
                            <button disabled class="flex-1 bg-gray-400 text-white font-bold py-3 px-6 rounded-lg text-center cursor-not-allowed">
                                PDF no disponible
                            </button>
                        @endif

                        @if($certificado->estado == 'emitido')
                            <form action="{{ route('certificados.revocar', $certificado) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('¿Estás seguro de revocar este certificado?')"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg">
                                    🚫 Revocar Certificado
                                </button>
                            </form>
                        @elseif($certificado->estado === 'revocado')
                            <form action="{{ route('certificados.restaurar', $certificado->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('¿Restaurar este certificado?')"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                                    ✓ Restaurar Certificado
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copiarEnlace(event) {
    const url = "{{ $urlPublica }}";
    const boton = event.currentTarget;
    
    navigator.clipboard.writeText(url).then(function() {
        boton.textContent = '✅ ¡Copiado!';
        boton.style.backgroundColor = '#10b981';
        boton.style.color = 'white';
        
        setTimeout(function() {
            boton.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>Copiar';
            boton.style.backgroundColor = '';
            boton.style.color = '';
        }, 2000);
    }).catch(function(err) {
        console.error('Error:', err);
        alert('Error al copiar. URL: ' + url);
    });
}
    </script>
  
</x-app-layout>