<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900">⏳ Pagos Pendientes de Validación</h2>
                <p class="text-sm text-gray-600 mt-1">Revisa y confirma los pagos realizados por Yape</p>
            </div>
            <a href="{{ route('pagos.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Pagos
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Mensajes -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($pagos->count() == 0)
            <!-- Sin pagos pendientes -->
            <div class="bg-white rounded-2xl shadow-lg p-12">
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-green-100 rounded-full p-6 mb-6">
                        <svg class="w-20 h-20 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">¡Todo al día!</h3>
                    <p class="text-gray-600 text-center">No hay pagos pendientes de validación en este momento.</p>
                </div>
            </div>
            @else
            <!-- Tarjeta de resumen -->
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl shadow-xl p-6 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium mb-1">Total Pendientes</p>
                        <p class="text-5xl font-bold">{{ $pagos->total() }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-6">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabla de pagos -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-800 to-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Estudiante</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Curso</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Monto</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Código Yape</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pagos as $pago)
                            <tr class="hover:bg-yellow-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ substr($pago->inscripcion->estudiante->nombres, 0, 1) }}{{ substr($pago->inscripcion->estudiante->apellidos, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ $pago->inscripcion->estudiante->nombres }} {{ $pago->inscripcion->estudiante->apellidos }}
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                                DNI: {{ $pago->inscripcion->estudiante->dni }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $pago->inscripcion->curso->nombre }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $pago->inscripcion->curso->codigo }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center px-4 py-2 bg-green-100 rounded-lg">
                                        <span class="text-2xl font-bold text-green-700">S/ {{ number_format($pago->monto, 2) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center">
                                        <span class="bg-yellow-400 text-yellow-900 px-4 py-2 rounded-lg font-mono font-bold text-lg shadow-md">
                                            {{ $pago->numero_operacion }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $pago->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $pago->created_at->format('H:i:s') }}</div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        Hace {{ $pago->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <!-- Confirmar -->
                                        <form action="{{ route('admin.pagos.confirmar-manual', $pago->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="observaciones" value="Pago confirmado por administrador">
                                            <button type="submit" 
                                                    onclick="return confirm('¿Confirmar este pago?\n\nEstudiante: {{ $pago->inscripcion->estudiante->nombres }}\nMonto: S/ {{ number_format($pago->monto, 2) }}')"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Confirmar
                                            </button>
                                        </form>

                                        <!-- Rechazar -->
                                        <button type="button" 
                                                onclick="abrirModal({{ $pago->id }}, '{{ $pago->inscripcion->estudiante->nombres }} {{ $pago->inscripcion->estudiante->apellidos }}', '{{ number_format($pago->monto, 2) }}')"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($pagos->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $pagos->links() }}
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Modal de Rechazo Mejorado -->
    <div id="modal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-fade-in">
            <!-- Header del Modal -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4 rounded-t-2xl">
                <h3 class="text-xl font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Rechazar Pago
                </h3>
            </div>

            <!-- Cuerpo del Modal -->
            <form id="formRechazar" method="POST" class="p-6">
                @csrf
                
                <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded">
                    <p class="text-sm text-yellow-800">
                        <strong>Estudiante:</strong> <span id="nombreEstudiante"></span><br>
                        <strong>Monto:</strong> S/ <span id="montoEstudiante"></span>
                    </p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <span class="text-red-600">*</span> Motivo del rechazo:
                    </label>
                    <textarea name="motivo_rechazo" 
                              class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all" 
                              rows="4" 
                              required
                              placeholder="Describe el motivo del rechazo..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">Este motivo será enviado al estudiante</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="cerrarModal()"
                            class="flex-1 px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Rechazar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    function abrirModal(pagoId, nombreEstudiante, monto) {
        document.getElementById('modal').style.display = 'flex';
        document.getElementById('formRechazar').action = '/admin/pagos/' + pagoId + '/rechazar-manual';
        document.getElementById('nombreEstudiante').textContent = nombreEstudiante;
        document.getElementById('montoEstudiante').textContent = monto;
    }

    function cerrarModal() {
        document.getElementById('modal').style.display = 'none';
        document.querySelector('textarea[name="motivo_rechazo"]').value = '';
    }

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModal();
        }
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('modal').addEventListener('click', function(event) {
        if (event.target === this) {
            cerrarModal();
        }
    });
    </script>
    @endpush
</x-app-layout>