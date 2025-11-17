<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                    📝 Inscripción a Curso
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Completa tu inscripción al curso
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Información del curso -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">📚 Información del Curso</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Código:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $curso->codigo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nombre:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $curso->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Modalidad:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $curso->modalidad->nombre ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Duración:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $curso->horas_academicas }} horas</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Fecha de inicio:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $curso->fecha_inicio->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Costo:</p>
                        <p class="text-2xl font-bold text-green-600">S/ {{ number_format($curso->costo_inscripcion, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Formulario de pago -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">💳 Información de Pago</h3>

                <form action="{{ route('estudiantes.guardar-inscripcion', $curso) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Método de pago -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Método de Pago <span class="text-red-500">*</span>
                        </label>
                        <select name="metodo_pago" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione un método</option>
                            <option value="transferencia">🏦 Transferencia Bancaria</option>
                            <option value="deposito">🏧 Depósito en Efectivo</option>
                            <option value="yape">📱 Yape</option>
                            <option value="plin">📱 Plin</option>
                        </select>
                        @error('metodo_pago')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de operación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Operación (opcional)
                        </label>
                        <input type="text" name="numero_operacion" 
                               placeholder="Ej: 123456789"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('numero_operacion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comprobante de pago -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Comprobante de Pago (opcional)
                        </label>
                        <input type="file" name="comprobante_pago" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Formatos: JPG, PNG, PDF (máx. 5MB)</p>
                        @error('comprobante_pago')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observaciones -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Observaciones (opcional)
                        </label>
                        <textarea name="observaciones" rows="3" 
                                  placeholder="Agrega cualquier comentario adicional..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Información bancaria -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-2">💡 Datos para Transferencia</h4>
                        <p class="text-sm text-blue-700">
                            <strong>Banco:</strong> BCP<br>
                            <strong>Cuenta:</strong> 194-1234567890-0-12<br>
                            <strong>CCI:</strong> 002-194-001234567890-12<br>
                            <strong>Titular:</strong> Universidad Nacional Daniel Alcides Carrión
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4">
                        <a href="{{ route('estudiantes.cursos-disponibles') }}" 
                           class="flex-1 bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-center hover:bg-gray-400 transition font-semibold">
                            ← Cancelar
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            ✅ Confirmar Inscripción
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>