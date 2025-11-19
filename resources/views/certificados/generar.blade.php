<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Generar Certificados
            </h2>
            <a href="{{ route('certificados.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-blue-900">Criterios de Certificación</h3>
                        <div class="mt-2 text-sm text-blue-800">
                            <ul class="list-disc list-inside space-y-1">
                                <li>El estudiante debe haber aprobado el curso (nota final ≥ 11)</li>
                                <li>Debe cumplir con el porcentaje mínimo de asistencia ({{ $porcentajeAsistenciaMinimo ?? 75 }}%)</li>
                                <li>No debe tener un certificado previamente emitido para este curso</li>
                                <li>La inscripción debe estar en estado "Finalizado"</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selección de Curso -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Seleccionar Curso</h3>
                    
                    <form method="GET" action="{{ route('certificados.generar') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-2">Curso</label>
                            <select name="curso_id" id="curso_id" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    onchange="this.form.submit()">
                                <option value="">Seleccione un curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }} - {{ $curso->codigo }} ({{ $curso->inscripciones_count }} inscritos)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                Cargar Estudiantes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($cursoSeleccionado))
                <!-- Información del Curso -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Curso</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Curso</label>
                                <p class="mt-1 text-base font-semibold text-gray-900">{{ $cursoSeleccionado->nombre }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Código</label>
                                <p class="mt-1 text-base text-gray-900">{{ $cursoSeleccionado->codigo }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Duración</label>
                                <p class="mt-1 text-base text-gray-900">{{ $cursoSeleccionado->duracion_horas }} horas</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Docente</label>
                                <p class="mt-1 text-base text-gray-900">{{ $docentePrincipal->nombres ?? 'N/A' }} {{ $docentePrincipal->apellidos ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <!-- Total Inscritos -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Inscritos</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $totalInscritos }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aptos para Certificar -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Aptos</p>
                                    <p class="text-2xl font-semibold text-green-600">{{ $estudiantesAptos->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Aptos -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">No Aptos</p>
                                    <p class="text-2xl font-semibold text-red-600">{{ $estudiantesNoAptos->count() }}</p>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Ya Certificados -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Ya Certificados</p>
                                        <p class="text-2xl font-semibold text-purple-600">{{ $yaCertificados }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de Generación -->
                    <form id="formCertificados" action="{{ route('certificados.generar-masivo') }}" method="POST">
                        @csrf
                        <input type="hidden" name="curso_id" value="{{ $cursoSeleccionado->id }}">

                        <!-- Estudiantes Aptos -->
                        @if($estudiantesAptos->count() > 0)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Estudiantes Aptos para Certificación ({{ $estudiantesAptos->count() }})
                                        </h3>
                                        <div>
                                            <button type="button" onclick="seleccionarTodos(true)" 
                                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium mr-4">
                                                Seleccionar Todos
                                            </button>
                                            <button type="button" onclick="seleccionarTodos(false)" 
                                                    class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                                Deseleccionar Todos
                                            </button>
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                                        <input type="checkbox" id="selectAll" onchange="seleccionarTodos(this.checked)"
                                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Estudiante
                                                    </th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        DNI
                                                    </th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Nota Final
                                                    </th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Asistencia
                                                    </th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Estado
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($estudiantesAptos as $inscripcion)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 text-center">
                                                            <input type="checkbox" name="inscripciones[]" value="{{ $inscripcion->id }}"
                                                                class="certificado-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                                checked>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10">
                                                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                                        <span class="text-green-600 font-semibold text-sm">
                                                                            {{ substr($inscripcion->estudiante->nombres, 0, 1) }}{{ substr($inscripcion->estudiante->apellidos, 0, 1) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="text-sm font-medium text-gray-900">
                                                                        {{ $inscripcion->estudiante->nombres }} {{ $inscripcion->estudiante->apellidos }}
                                                                    </div>
                                                                    <div class="text-sm text-gray-500">
                                                                        {{ $inscripcion->estudiante->email }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                                            {{ $inscripcion->estudiante->dni }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <span class="text-xl font-bold text-green-600">
                                                                {{ number_format($inscripcion->nota_final, 2) }}
                                                            </span>
                                                        </td>
                                                      
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
    @php
        $colorAsistencia = $inscripcion->porcentaje_asistencia >= 75 ? 'text-green-600' : 'text-yellow-600';
    @endphp
    <span class="text-sm font-semibold {{ $colorAsistencia }}">
        {{ number_format($inscripcion->porcentaje_asistencia, 1) }}%
    </span>
</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                ✓ APTO
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <button type="submit" 
        form="formCertificados"
        onclick="return confirm('¿Está seguro de generar los certificados seleccionados?')"
        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg inline-flex items-center transition duration-150">
         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Generar Certificados Seleccionados
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-semibold text-yellow-900">No hay estudiantes aptos para certificación</h3>
                                        <p class="mt-2 text-sm text-yellow-800">
                                            No se encontraron estudiantes que cumplan con los requisitos mínimos de certificación en este curso.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>

                    <!-- Estudiantes No Aptos -->
                    @if($estudiantesNoAptos->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Estudiantes No Aptos ({{ $estudiantesNoAptos->count() }})
                                </h3>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Estudiante
                                                </th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nota Final
                                                </th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Asistencia
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Motivo
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($estudiantesNoAptos as $inscripcion)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $inscripcion->estudiante->nombres }} {{ $inscripcion->estudiante->apellidos }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $inscripcion->estudiante->dni }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <span class="text-lg font-bold {{ $inscripcion->nota_final >= 11 ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ number_format($inscripcion->nota_final, 2) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <span class="text-sm font-semibold {{ $inscripcion->porcentaje_asistencia >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ number_format($inscripcion->porcentaje_asistencia, 1) }}%
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-red-600">
                                                        @if($inscripcion->nota_final < 11)
                                                            • Nota insuficiente (menor a 11)
                                                        @endif
                                                        @if($inscripcion->porcentaje_asistencia < 75)
                                                            <br>• Asistencia insuficiente (menor a 75%)
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        @push('scripts')
        <script>
            function seleccionarTodos(checked) {
                const checkboxes = document.querySelectorAll('.certificado-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = checked;
                });
                document.getElementById('selectAll').checked = checked;
            }
        </script>
        @endpush
    </x-app-layout>