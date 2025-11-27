<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    ➕ Registrar Nuevo Pago
                </h2>
                <p class="text-sm text-gray-600 mt-1">Registra un pago de inscripción al sistema</p>
            </div>
            <a href="{{ route('pagos.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes de error -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-md">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-red-800 font-bold mb-2">¡Atención! Hay errores en el formulario:</h3>
                            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Información de Inscripción -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #10b981, #059669) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Seleccionar Inscripción
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Botón para abrir modal -->
                        <button type="button" id="open_modal_btn" 
                                class="w-full px-4 py-3 text-left rounded-lg border-2 border-gray-300 hover:border-green-500 transition flex items-center justify-between bg-white hover:bg-green-50"
                                style="min-height: 44px;">
                            <span id="selected_text" class="text-gray-600">
                                <svg class="inline h-5 w-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Haz clic para buscar una inscripción...
                            </span>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>

                        <!-- Input oculto -->
                        <input type="hidden" name="inscripcion_id" id="inscripcion_id">

                        <!-- Modal de búsqueda -->
                        <div id="search_modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                            <div class="bg-white rounded-xl shadow-2xl w-11/12 md:w-2/3 lg:w-1/2 max-h-96 flex flex-col" style="max-height: 500px;">
                                
                                <!-- Header del modal -->
                                <div class="p-4 border-b border-gray-200">
                                    <div class="relative">
                                        <svg class="absolute left-3 top-3 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <input type="text" id="modal_search" 
                                               placeholder="Busca por nombre, DNI, curso o monto..."
                                               class="w-full pl-10 pr-10 py-2 rounded-lg border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition text-sm"
                                               autofocus>
                                        <button type="button" id="close_modal_btn" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Lista de resultados -->
                                <div id="results_container" class="overflow-y-auto flex-1 p-2">
                                    <div id="results_list"></div>
                                </div>

                                <!-- Footer -->
                                <div class="p-3 border-t border-gray-200 text-center text-xs text-gray-500">
                                    Mostrando <span id="results_count">0</span> de {{ count($inscripciones) }} inscripciones
                                </div>
                            </div>
                        </div>

                        <p class="mt-2 text-xs text-gray-500">💡 Selecciona una inscripción pendiente de pago</p>
                        
                        @error('inscripcion_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const openBtn = document.getElementById('open_modal_btn');
                        const closeBtn = document.getElementById('close_modal_btn');
                        const modal = document.getElementById('search_modal');
                        const modalSearch = document.getElementById('modal_search');
                        const resultsList = document.getElementById('results_list');
                        const resultsCount = document.getElementById('results_count');
                        const selectedText = document.getElementById('selected_text');
                        const hiddenInput = document.getElementById('inscripcion_id');

                        const inscripciones = [
                            @foreach($inscripciones as $inscripcion)
                            {
                                id: "{{ $inscripcion->id }}",
                                nombres: "{{ $inscripcion->estudiante->nombres }}",
                                apellidos: "{{ $inscripcion->estudiante->apellidos }}",
                                dni: "{{ $inscripcion->estudiante->dni }}",
                                curso: "{{ $inscripcion->curso->nombre }}",
                                costo: "{{ number_format($inscripcion->curso->costo_inscripcion, 2) }}",
                                search: "{{ strtolower($inscripcion->estudiante->nombres . ' ' . $inscripcion->estudiante->apellidos . ' ' . $inscripcion->estudiante->dni . ' ' . $inscripcion->curso->nombre . ' ' . $inscripcion->curso->costo_inscripcion) }}"
                            },
                            @endforeach
                        ];

                        function renderResults(searchTerm = '') {
                            let filtered = inscripciones;
                            
                            if (searchTerm.length > 0) {
                                filtered = inscripciones.filter(item => 
                                    item.search.includes(searchTerm.toLowerCase())
                                );
                            }

                            resultsList.innerHTML = filtered.length === 0 
                                ? '<div class="p-4 text-center text-gray-500 text-sm">❌ No se encontraron resultados</div>'
                                : filtered.map(item => `
                                    <div class="result-item p-3 hover:bg-green-50 cursor-pointer rounded-lg transition border-b last:border-b-0 flex items-center justify-between"
                                         data-id="${item.id}">
                                        <div class="flex-1">
                                            <p class="font-semibold text-sm text-gray-900">
                                                🎓 ${item.nombres} ${item.apellidos}
                                            </p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                📚 ${item.curso} • 💰 S/ ${item.costo}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">DNI: ${item.dni}</p>
                                        </div>
                                        <svg class="h-5 w-5 text-green-500 ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                `).join('');

                            resultsCount.textContent = filtered.length;

                            // Agregar event listeners
                            document.querySelectorAll('.result-item').forEach(item => {
                                item.addEventListener('click', function() {
                                    const id = this.getAttribute('data-id');
                                    const selected = inscripciones.find(i => i.id === id);
                                    
                                    hiddenInput.value = id;
                                    selectedText.innerHTML = `
                                        <svg class="inline h-5 w-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        ✅ ${selected.nombres} ${selected.apellidos} - ${selected.curso}
                                    `;
                                    modal.classList.add('hidden');
                                });
                            });
                        }

                        // Abrir modal
                        openBtn.addEventListener('click', function() {
                            modal.classList.remove('hidden');
                            modalSearch.focus();
                            renderResults();
                        });

                        // Cerrar modal
                        closeBtn.addEventListener('click', function() {
                            modal.classList.add('hidden');
                        });

                        // Cerrar al clickear fuera
                        modal.addEventListener('click', function(e) {
                            if (e.target === this) {
                                this.classList.add('hidden');
                            }
                        });

                        // Buscar en tiempo real
                        modalSearch.addEventListener('input', function() {
                            renderResults(this.value);
                        });

                        // Tecla ESC para cerrar
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                                modal.classList.add('hidden');
                            }
                        });
                    });
                </script>

                <!-- Datos del Pago -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #3b82f6, #2563eb) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Datos del Pago
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Método de Pago -->
                            <div>
                                <label for="metodo_pago_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Método de Pago <span class="text-red-500">*</span>
                                </label>
                                <select name="metodo_pago_id" id="metodo_pago_id" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">Seleccione un método</option>
                                    @foreach($metodosPago as $metodo)
                                    <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('metodo_pago_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Monto -->
                            <div>
                                <label for="monto" class="block text-sm font-bold text-gray-700 mb-2">
                                    Monto (S/) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold text-lg">
                                        S/
                                    </span>
                                    <input type="number" name="monto" id="monto" step="0.01" min="0" required
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition pl-12 text-lg font-semibold"
                                           placeholder="0.00">
                                </div>
                                @error('monto')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fecha de Pago -->
                            <div>
                                <label for="fecha_pago" class="block text-sm font-bold text-gray-700 mb-2">
                                    Fecha y Hora de Pago <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="fecha_pago" id="fecha_pago" required
                                       value="{{ old('fecha_pago', now()->format('Y-m-d\TH:i')) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                @error('fecha_pago')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Número de Operación -->
                            <div>
                                <label for="numero_operacion" class="block text-sm font-bold text-gray-700 mb-2">
                                    Número de Operación
                                </label>
                                <input type="text" name="numero_operacion" id="numero_operacion"
                                       value="{{ old('numero_operacion') }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition"
                                       placeholder="Ej: OP-12345678">
                                <p class="mt-1 text-xs text-gray-500">Número de operación bancaria o transacción</p>
                                @error('numero_operacion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <label for="descripcion" class="block text-sm font-bold text-gray-700 mb-2">
                                    Descripción / Observaciones
                                </label>
                                <textarea name="descripcion" id="descripcion" rows="3"
                                          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition"
                                          placeholder="Detalles adicionales del pago, observaciones, etc...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Información Importante -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #f59e0b, #d97706) !important;">
                        <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información Importante
                        </h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">El pago se registrará con estado <strong>Pendiente</strong> por defecto</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">Deberá ser <strong>validado por un administrador</strong> posteriormente</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">El estudiante recibirá una <strong>notificación por correo</strong> al validarse el pago</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">Verifica que el monto coincida con el <strong>costo de la inscripción</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="p-6 flex justify-between items-center">
                        <a href="{{ route('pagos.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-lg font-bold text-sm text-gray-800 uppercase tracking-wide hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 transition shadow-lg transform hover:scale-105"
                                style="background: linear-gradient(to right, #10b981, #059669) !important; color: white !important;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Registrar Pago
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>