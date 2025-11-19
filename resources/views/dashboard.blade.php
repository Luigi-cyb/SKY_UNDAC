<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Dashboard - Sistema SKY-UNDAC
                </h2>
                <p class="text-sm text-gray-600 mt-1 ml-8">
                    {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Bienvenida -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-lg p-6 mb-6 text-white">
                <div class="flex items-center mb-2">
                    <span class="text-2xl mr-3">👋</span>
                    <h3 class="text-2xl font-bold">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-sm text-green-50">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <strong>Rol:</strong> 
                        <span class="ml-1.5 px-2 py-0.5 bg-green-800 rounded-full text-xs font-semibold">
                            {{ Auth::user()->roles->first()->name ?? 'Sin rol' }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Universidad Nacional Daniel Alcides Carrión
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos según ROL -->
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800">Accesos Rápidos</h3>
                </div>
                
                @role('Administrador|Comité Académico')
                <!-- ACCESOS PARA ADMINISTRADORES -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('cursos.index') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-blue-500 hover:border-blue-600">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 group-hover:bg-blue-500 transition-all">
                                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-blue-600 transition-all">Gestionar Cursos</h4>
                                <p class="text-gray-600 text-sm mt-1">Administrar cursos del sistema</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('inscripciones.index') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-green-500 hover:border-green-600">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-100 group-hover:bg-green-500 transition-all">
                                    <svg class="w-6 h-6 text-green-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-green-600 transition-all">Ver Inscripciones</h4>
                                <p class="text-gray-600 text-sm mt-1">Gestionar inscripciones</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('certificados.index') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-yellow-500 hover:border-yellow-600">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-yellow-100 group-hover:bg-yellow-500 transition-all">
                                    <svg class="w-6 h-6 text-yellow-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-yellow-600 transition-all">Certificados</h4>
                                <p class="text-gray-600 text-sm mt-1">Emitir certificados</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('pagos.index') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-purple-500 hover:border-purple-600">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-purple-100 group-hover:bg-purple-500 transition-all">
                                    <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-purple-600 transition-all">Gestión de Pagos</h4>
                                <p class="text-gray-600 text-sm mt-1">Administrar pagos</p>
                            </div>
                        </div>
                    </a>

                  
                </div>
                @endrole

                @role('Docente')
                <!-- ACCESOS PARA DOCENTES -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('docentes.mis-cursos') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-blue-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 group-hover:bg-blue-500 transition-all">
                                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-blue-600 transition-all">Mis Cursos</h4>
                                <p class="text-gray-600 text-sm mt-1">Ver cursos asignados</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('asistencias.index') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-green-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-100 group-hover:bg-green-500 transition-all">
                                    <svg class="w-6 h-6 text-green-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-green-600 transition-all">Asistencia</h4>
                                <p class="text-gray-600 text-sm mt-1">Registrar asistencia</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('evaluaciones.index') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-purple-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-purple-100 group-hover:bg-purple-500 transition-all">
                                    <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-purple-600 transition-all">Evaluaciones</h4>
                                <p class="text-gray-600 text-sm mt-1">Gestionar calificaciones</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endrole

                @role('Estudiante')
                <!-- ACCESOS PARA ESTUDIANTES -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('estudiantes.mis-cursos') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-blue-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 group-hover:bg-blue-500 transition-all">
                                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-blue-600 transition-all">Mis Cursos</h4>
                                <p class="text-gray-600 text-sm mt-1">Ver mis cursos inscritos</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('estudiantes.mis-inscripciones') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-green-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-100 group-hover:bg-green-500 transition-all">
                                    <svg class="w-6 h-6 text-green-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-green-600 transition-all">Mis Inscripciones</h4>
                                <p class="text-gray-600 text-sm mt-1">Ver estado de inscripciones</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('estudiantes.mis-certificados') }}" class="group bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-all border-l-4 border-yellow-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-yellow-100 group-hover:bg-yellow-500 transition-all">
                                    <svg class="w-6 h-6 text-yellow-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-bold text-gray-800 group-hover:text-yellow-600 transition-all">Mis Certificados</h4>
                                <p class="text-gray-600 text-sm mt-1">Descargar certificados</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endrole
            </div>

            <!-- Estadísticas rápidas (solo para Admin) -->
            @role('Administrador|Comité Académico')
            <div class="bg-white rounded-lg shadow-lg p-5 border-t-4 border-green-600">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800">Estadísticas Rápidas</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-xs font-semibold uppercase mb-1">Cursos</p>
                                <p class="text-3xl font-bold">{{ \App\Models\Curso::count() }}</p>
                            </div>
                            <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-xs font-semibold uppercase mb-1">Estudiantes</p>
                                <p class="text-3xl font-bold">{{ \App\Models\Estudiante::count() }}</p>
                            </div>
                            <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-xs font-semibold uppercase mb-1">Docentes</p>
                                <p class="text-3xl font-bold">{{ \App\Models\Docente::count() }}</p>
                            </div>
                            <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-4 text-white shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-xs font-semibold uppercase mb-1">Inscripciones</p>
                                <p class="text-3xl font-bold">{{ \App\Models\Inscripcion::count() }}</p>
                            </div>
                            <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            @endrole

        </div>
    </div>
</x-app-layout>