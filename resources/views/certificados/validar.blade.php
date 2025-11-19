<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Certificado - Sistema SKY</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-blue-600 rounded-full p-4">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Validación de Certificado</h1>
                <p class="text-gray-600">Universidad Nacional Daniel Alcides Carrión</p>
                <p class="text-sm text-gray-500">Escuela de Ingeniería de Sistemas y Computación</p>
            </div>

            <!-- Formulario de Búsqueda -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <form action="{{ route('certificados.validar.verificar') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="codigo_verificacion" class="block text-sm font-medium text-gray-700 mb-2">
                            Código de Verificación
                        </label>
                        <input type="text" 
                               name="codigo_verificacion" 
                               id="codigo_verificacion" 
                               required
                               placeholder="Ej: CERT-2024-000001"
                               value="{{ old('codigo_verificacion', $codigoIngresado ?? '') }}"
                               class="w-full px-4 py-3 text-lg rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('codigo_verificacion') border-red-500 @enderror">
                        @error('codigo_verificacion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            Ingrese el código de verificación que aparece en el certificado
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Verificar Certificado
                    </button>
                </form>
            </div>

            <!-- Resultado de Validación -->
            @if(isset($certificado))
                @if($certificado && $certificado->estado == 'Emitido')
                    <!-- Certificado Válido -->
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-green-500 p-4 text-center">
                            <div class="flex justify-center mb-2">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">✓ CERTIFICADO VÁLIDO</h2>
                            <p class="text-green-100 mt-1">Este certificado es auténtico y está vigente</p>
                        </div>

                        <div class="p-8">
                            <!-- Información del Certificado -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Información del Certificado</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Código de Verificación</label>
                                        <p class="text-base font-semibold text-gray-900">{{ $certificado->codigo_verificacion }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Fecha de Emisión</label>
                                        <p class="text-base text-gray-900">{{ \Carbon\Carbon::parse($certificado->fecha_emision)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Estudiante -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Datos del Estudiante</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nombres y Apellidos</label>
                                        <p class="text-base font-semibold text-gray-900">
                                            {{ $certificado->inscripcion->estudiante->nombres }} {{ $certificado->inscripcion->estudiante->apellidos }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Documento de Identidad</label>
                                        <p class="text-base text-gray-900">{{ $certificado->inscripcion->estudiante->dni }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Curso -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Datos del Curso</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="text-sm font-medium text-gray-600">Nombre del Curso</label>
                                        <p class="text-base font-semibold text-gray-900">{{ $certificado->inscripcion->curso->nombre }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Código del Curso</label>
                                        <p class="text-base text-gray-900">{{ $certificado->inscripcion->curso->codigo }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Duración</label>
                                        <p class="text-base text-gray-900">{{ $certificado->inscripcion->curso->duracion_horas }} horas académicas</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Modalidad</label>
                                        <p class="text-base text-gray-900">{{ $certificado->inscripcion->curso->modalidad->nombre ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nota Final</label>
                                        <p class="text-2xl font-bold text-green-600">{{ number_format($certificado->nota_final, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Código QR -->
                            @if($certificado->codigo_qr)
                            <div class="flex justify-center mt-8">
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Código QR de Verificación</p>
                                    <div class="inline-block p-4 bg-white border-2 border-gray-300 rounded-lg">
                                        <img src="data:image/png;base64,{{ $certificado->codigo_qr }}" 
                                             alt="QR Code" 
                                             class="w-32 h-32">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Información de Validaciones -->
                            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-800">
                                            Este certificado ha sido validado 
                                            <span class="font-semibold">{{ $certificado->validaciones->count() }}</span> 
                                            {{ $certificado->validaciones->count() == 1 ? 'vez' : 'veces' }}
                                        </p>
                                        <p class="text-xs text-blue-600 mt-1">
                                            Última validación: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($certificado && $certificado->estado == 'Revocado')
                    <!-- Certificado Revocado -->
                    <div class="bg-red-50 border-2 border-red-500 rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-red-500 p-4 text-center">
                            <div class="flex justify-center mb-2">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">✗ CERTIFICADO REVOCADO</h2>
                            <p class="text-red-100 mt-1">Este certificado ha sido anulado y no tiene validez</p>
                        </div>

                        <div class="p-8">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Código de Verificación</label>
                                    <p class="text-base font-semibold text-gray-900">{{ $certificado->codigo_verificacion }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Estado</label>
                                    <p class="text-base font-semibold text-red-600">REVOCADO</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Motivo</label>
                                    <p class="text-base text-gray-900">{{ $certificado->motivo_revocacion ?? 'No especificado' }}</p>
                                </div>
                            </div>

                            <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm text-red-800">
                                    <strong>Advertencia:</strong> Este certificado no debe ser considerado válido para ningún propósito oficial.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

            @elseif(isset($error))
                <!-- Certificado No Encontrado -->
                <div class="bg-yellow-50 border-2 border-yellow-500 rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-yellow-500 p-4 text-center">
                        <div class="flex justify-center mb-2">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">CERTIFICADO NO ENCONTRADO</h2>
                        <p class="text-yellow-100 mt-1">No se encontró un certificado con este código</p>
                    </div>

                    <div class="p-8">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Código Ingresado</label>
                                <p class="text-base font-semibold text-gray-900">{{ $codigoIngresado }}</p>
                            </div>
                        </div>

                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-yellow-900 mb-2">Posibles causas:</h3>
                            <ul class="text-sm text-yellow-800 space-y-1 list-disc list-inside">
                                <li>El código de verificación no es correcto</li>
                                <li>El certificado aún no ha sido emitido</li>
                                <li>Hay un error tipográfico en el código</li>
                                <li>El certificado no existe en nuestro sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Información adicional -->
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Importante</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <p>• Todos los certificados emitidos por la UNDAC - EISC pueden ser verificados a través de este portal.</p>
                    <p>• El código de verificación es único para cada certificado.</p>
                    <p>• Si tiene dudas sobre la autenticidad de un certificado, puede comunicarse con la Escuela de Ingeniería de Sistemas y Computación.</p>
                    <p>• La validación queda registrada en nuestro sistema con fines de auditoría.</p>
                </div>
            </div>

            <!-- Botón de Nueva Búsqueda -->
            <div class="mt-6 text-center">
                <a href="{{ route('certificados.validar') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Validar Otro Certificado
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center text-sm text-gray-500">
                <p>© {{ date('Y') }} Universidad Nacional Daniel Alcides Carrión</p>
                <p class="mt-1">Escuela de Ingeniería de Sistemas y Computación</p>
                <p class="mt-2">Sistema de Gestión de Certificados - SKY</p>
            </div>
        </div>
    </div>
</body>
</html>