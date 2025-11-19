<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reportes y Estadísticas') }}
            </h2>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                    10 Reportes Disponibles
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Sección de Reportes Académicos -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Reportes Académicos
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Inscripciones -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-blue-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Inscripciones</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Reporte detallado de inscripciones por curso, estado y periodo con exportación a PDF.</p>
                            <a href="{{ route('reportes.inscripciones') }}" class="inline-flex items-center text-blue-700 hover:text-blue-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Calificaciones -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-green-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Calificaciones</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Reporte completo de calificaciones por estudiante, curso y evaluación.</p>
                            <a href="{{ route('reportes.calificaciones') }}" class="inline-flex items-center text-green-700 hover:text-green-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Asistencia -->
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-teal-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Asistencia</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Control de asistencias por curso, sesión y porcentajes de participación.</p>
                            <a href="{{ route('reportes.asistencia') }}" class="inline-flex items-center text-teal-700 hover:text-teal-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Rendimiento Académico -->
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-indigo-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Rendimiento</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Estadísticas de aprobación, promedios y desempeño académico por curso.</p>
                            <a href="{{ route('reportes.rendimiento') }}" class="inline-flex items-center text-indigo-700 hover:text-indigo-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Certificados -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-purple-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Certificados</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Certificados emitidos, validaciones y control de documentos académicos.</p>
                            <a href="{{ route('reportes.certificados') }}" class="inline-flex items-center text-purple-700 hover:text-purple-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Satisfacción -->
                    <div class="bg-gradient-to-br from-pink-50 to-pink-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-pink-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Satisfacción</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Encuestas de satisfacción y evaluación de la calidad académica.</p>
                            <a href="{{ route('reportes.satisfaccion') }}" class="inline-flex items-center text-pink-700 hover:text-pink-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Sección de Reportes Administrativos -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="h-8 w-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Reportes Administrativos
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Pagos -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-yellow-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Pagos</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Control de ingresos, pagos pendientes y conciliación financiera con PDF.</p>
                            <a href="{{ route('reportes.pagos') }}" class="inline-flex items-center text-yellow-700 hover:text-yellow-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Carga Docente -->
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-orange-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Carga Docente</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Distribución de cursos, horas académicas y carga de trabajo por docente.</p>
                            <a href="{{ route('reportes.carga-docente') }}" class="inline-flex items-center text-orange-700 hover:text-orange-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Estadísticas Generales -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-red-600 rounded-lg p-3 shadow-md">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                    </svg>
                                </div>
                                <h3 class="ml-4 text-xl font-bold text-gray-900">Estadísticas</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed">Dashboard integral con indicadores clave y métricas del sistema.</p>
                            <a href="{{ route('reportes.estadisticas') }}" class="inline-flex items-center text-red-700 hover:text-red-900 font-semibold">
                                Ver reporte
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Información Adicional -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-blue-900">Información</h3>
                        <p class="mt-2 text-sm text-blue-800">
                            Los reportes incluyen filtros avanzados, exportación a PDF/Excel y visualizaciones interactivas. 
                            Todos los datos se actualizan en tiempo real desde la base de datos.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>