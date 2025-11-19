<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    🎓 Gestión de Certificados
                </h2>
                <p class="text-sm text-gray-600 mt-1">Administra todos los certificados emitidos</p>
            </div>
            @can('certificados.generar')
            <a href="{{ route('certificados.generar') }}" 
               class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition transform hover:scale-105 shadow-lg"
               style="background: linear-gradient(to right, #3b82f6, #2563eb) !important; color: white !important;">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Generar Certificados
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes de éxito -->
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6 shadow-md">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800 font-semibold">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Total</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $totalCertificados ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #10b981, #059669) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Emitidos</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $certificadosEmitidos ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #f59e0b, #d97706) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Pendientes</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $certificadosPendientes ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #ef4444, #dc2626) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Revocados</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $certificadosRevocados ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filtros de Búsqueda -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6">
                <div class="px-6 py-4" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important;">
                    <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtros de Búsqueda
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('certificados.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- Filtro por Curso -->
                        <div>
                            <label for="curso_id" class="block text-sm font-bold text-gray-700 mb-2">Curso</label>
                            <select name="curso_id" id="curso_id" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="">Todos los cursos</option>
                                @foreach($cursos ?? [] as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro por Estudiante -->
                        <div>
                            <label for="buscar" class="block text-sm font-bold text-gray-700 mb-2">Buscar</label>
                            <input type="text" name="buscar" id="buscar" 
                                   value="{{ request('buscar') }}"
                                   placeholder="DNI o nombre"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                        </div>

                        <!-- Filtro por Estado -->
                        <div>
                            <label for="estado" class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                            <select name="estado" id="estado" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="">Todos</option>
                                <option value="Emitido" {{ request('estado') == 'Emitido' ? 'selected' : '' }}>✅ Emitido</option>
                                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="Revocado" {{ request('estado') == 'Revocado' ? 'selected' : '' }}>❌ Revocado</option>
                            </select>
                        </div>

                        <!-- Filtro por Fecha -->
                        <div>
                            <label for="fecha_desde" class="block text-sm font-bold text-gray-700 mb-2">Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" 
                                   value="{{ request('fecha_desde') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                        </div>

                        <!-- Botones -->
                        <div class="flex items-end space-x-2">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide transition shadow-lg"
                                    style="background: linear-gradient(to right, #6366f1, #4f46e5) !important; color: white !important;">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Buscar
                            </button>
                            <a href="{{ route('certificados.index') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded-lg transition"
                               title="Limpiar filtros">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Certificados -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6">
                    @if($certificados->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Código</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Estudiante</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Curso</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Nota</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">QR</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
    @forelse($certificados as $certificado)
        @php
            $estudiante = $certificado->inscripcion->estudiante;
            $curso = $certificado->inscripcion->curso;
            // Obtener nota final real
            $notaFinal = $certificado->inscripcion->calificaciones->avg('nota') ?? 0;
        @endphp
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">
                    {{ $certificado->codigo_certificado }}
                </div>
                <div class="text-xs text-gray-500">
                    ID: {{ $certificado->id }}
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-sm">
                                {{ substr($estudiante->nombres, 0, 1) }}{{ substr($estudiante->apellidos, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $estudiante->nombres }} {{ $estudiante->apellidos }}
                        </div>
                        <div class="text-sm text-gray-500">
                            DNI: {{ $estudiante->dni }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">
                    {{ $curso->nombre }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ $curso->codigo }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
                <span class="text-xl font-bold text-green-600">
                    {{ number_format($notaFinal, 2) }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $certificado->fecha_emision->format('d/m/Y') }}
            </td>
           <td class="px-6 py-4 whitespace-nowrap text-center">
    @if($certificado->firmado)
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
            ✅ Firmado
        </span>
    @else
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
            ⏳ Sin Firmar
        </span>
    @endif
    
    @if($certificado->estado == 'revocado')
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 ml-1">
            ❌ Revocado
        </span>
    @endif
</td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
                <button type="button" 
                        onclick="mostrarQR('{{ $certificado->codigo_qr }}')"
                        class="text-blue-600 hover:text-blue-900" 
                        title="Ver QR">
                    📱
                </button>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    <div class="flex items-center space-x-2">
        <!-- Ver detalles -->
        <a href="{{ route('certificados.show', $certificado) }}" 
           class="text-blue-600 hover:text-blue-900" 
           title="Ver detalles">
            👁️
        </a>
        
        <!-- ✅ SIEMPRE mostrar: Descargar sin firma -->
        <a href="{{ route('certificados.descargar-sin-firmar', $certificado) }}" 
           class="text-purple-600 hover:text-purple-900" 
           title="Descargar PDF sin firmar (original)">
            📄
        </a>
        
        <!-- ✅ SIEMPRE mostrar: Subir/Re-subir PDF firmado -->
        <button type="button"
                onclick="abrirModalSubir({{ $certificado->id }})"
                class="text-green-600 hover:text-green-900" 
                title="{{ $certificado->firmado ? 'Reemplazar PDF firmado' : 'Subir PDF firmado' }}">
            ⬆️
        </button>
        
        <!-- ✅ SI YA ESTÁ FIRMADO: Descargar firmado + Ver público -->
        @if($certificado->firmado)
            <a href="{{ route('certificados.descargar', $certificado) }}" 
               class="text-green-600 hover:text-green-900" 
               title="Descargar PDF firmado">
                📥
            </a>
            
            <a href="{{ route('certificado.publico', $certificado->codigo_qr) }}" 
               target="_blank"
               class="text-blue-600 hover:text-blue-900" 
               title="Ver certificado público">
                🌐
            </a>
        @endif
        
        <!-- Revocar / Restaurar -->
        @if($certificado->estado == 'emitido')
            <form action="{{ route('certificados.revocar', $certificado) }}" 
                  method="POST" 
                  onsubmit="return confirm('¿Revocar este certificado?')"
                  style="display: inline;">
                @csrf
                <button type="submit" 
                        class="text-red-600 hover:text-red-900" 
                        title="Revocar">
                    🚫
                </button>
            </form>
        @elseif($certificado->estado == 'revocado')
            <form action="{{ route('certificados.restaurar', $certificado) }}" 
                  method="POST" 
                  onsubmit="return confirm('¿Restaurar este certificado?')"
                  style="display: inline;">
                @csrf
                <button type="submit" 
                        class="text-green-600 hover:text-green-900" 
                        title="Restaurar">
                    ✓
                </button>
            </form>
        @endif
    </div>
</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="px-6 py-12 text-center">
                <p class="text-gray-500">No hay certificados registrados</p>
            </td>
        </tr>
    @endforelse
</tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        @if($certificados->hasPages())
                        <div class="mt-6 border-t border-gray-200 pt-4">
                            {{ $certificados->links() }}
                        </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-4 text-lg font-semibold text-gray-900">No hay certificados registrados</p>
                            <p class="mt-2 text-sm text-gray-500">Comienza generando certificados para los estudiantes que aprobaron</p>
                            @can('certificados.generar')
                            <div class="mt-6">
                                <a href="{{ route('certificados.generar') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition"
                                   style="background: linear-gradient(to right, #3b82f6, #2563eb) !important; color: white !important;">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Generar Certificados
                                </a>
                            </div>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para subir PDF firmado -->
    <div id="modalSubirPDF" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(to right, #10b981, #059669) !important;">
                <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Subir Certificado Firmado
                </h3>
            </div>
            
            <!-- Body -->
            <form id="formSubirPDF" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        Seleccione el PDF firmado digitalmente
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col w-full border-4 border-dashed border-gray-300 hover:border-green-500 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                            <div class="flex flex-col items-center justify-center pt-7 pb-7">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-600 font-semibold">Click para seleccionar archivo</p>
                                <p class="text-xs text-gray-500 mt-1">Solo archivos PDF (máx. 5MB)</p>
                            </div>
                            <input type="file" 
                                   name="pdf_firmado" 
                                   accept=".pdf"
                                   required
                                   class="hidden"
                                   onchange="mostrarNombreArchivo(this)">
                        </label>
                    </div>
                    <p id="nombreArchivo" class="mt-2 text-sm text-gray-600 hidden"></p>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <div class="flex">
                        <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800">Importante</p>
                            <p class="text-xs text-yellow-700 mt-1">Asegúrese de que el PDF esté firmado digitalmente antes de subirlo.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide transition shadow-lg"
                            style="background: linear-gradient(to right, #10b981, #059669) !important; color: white !important;">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Subir
                    </button>
                    <button type="button" 
                            onclick="cerrarModalSubir()"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function abrirModalSubir(certificadoId) {
            const modal = document.getElementById('modalSubirPDF');
            const form = document.getElementById('formSubirPDF');
            form.action = `/certificados/${certificadoId}/subir-firmado`;
            modal.classList.remove('hidden');
        }

        function cerrarModalSubir() {
            const modal = document.getElementById('modalSubirPDF');
            modal.classList.add('hidden');
            document.getElementById('nombreArchivo').classList.add('hidden');
        }

        function mostrarNombreArchivo(input) {
            const nombreDiv = document.getElementById('nombreArchivo');
            if (input.files && input.files[0]) {
                nombreDiv.textContent = `Archivo seleccionado: ${input.files[0].name}`;
                nombreDiv.classList.remove('hidden');
            }
        }

        function mostrarQR(codigoQR) {
            const url = `{{ url('/certificado') }}/${codigoQR}`;
            window.open(url, '_blank');
        }
    </script>
    @endpush
</x-app-layout>