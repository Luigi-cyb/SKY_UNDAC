<nav x-data="{ open: false }" class="bg-gradient-to-r from-green-700 to-green-600 border-b-4 border-green-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <img src="https://extranet.undac.edu.pe/img/undac.png" alt="UNDAC" class="h-12 bg-white rounded-lg p-1 shadow-md transform group-hover:scale-105 transition-transform duration-300">
                        <div class="ml-3 flex flex-col">
                            <span class="text-lg font-bold text-white">SKY UNDAC</span>
                            <span class="text-xs text-green-100">Sistema de Gestión Académica</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex items-center">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Inicio
                    </a>

                    @role('Administrador|Comité Académico')
                    <!-- CURSOS -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('cursos.*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Cursos
                                <svg class="ms-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-white rounded-lg shadow-xl border-t-4 border-green-600">
                                <a href="{{ route('cursos.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ver Cursos
                                </a>
                                <a href="{{ route('cursos.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Crear Curso
                                </a>
                            </div>
                        </x-slot>
                    </x-dropdown>

                    <!-- ESTUDIANTES -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('estudiantes.*') && !request()->routeIs('estudiantes.mis-*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Estudiantes
                                <svg class="ms-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-white rounded-lg shadow-xl border-t-4 border-blue-600">
                                <a href="{{ route('estudiantes.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ver Estudiantes
                                </a>
                                <a href="{{ route('estudiantes.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Registrar Estudiante
                                </a>
                            </div>
                        </x-slot>
                    </x-dropdown>

                    <!-- DOCENTES -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('docentes.*') && !request()->routeIs('docentes.mis-*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Docentes
                                <svg class="ms-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-white rounded-lg shadow-xl border-t-4 border-purple-600">
                                <a href="{{ route('docentes.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 transition-colors font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ver Docentes
                                </a>
                                <a href="{{ route('docentes.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Registrar Docente
                                </a>
                            </div>
                        </x-slot>
                    </x-dropdown>

                    <!-- INSCRIPCIONES -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('inscripciones.*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Inscripciones
                                <svg class="ms-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-white rounded-lg shadow-xl border-t-4 border-indigo-600">
                                <a href="{{ route('inscripciones.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 transition-colors font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ver Inscripciones
                                </a>
                                <a href="{{ route('inscripciones.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Nueva Inscripción
                                </a>
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @endrole

                    @role('Administrador')
                    <!-- PAGOS -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('pagos.*') || request()->routeIs('comprobantes.*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Pagos
                                <svg class="ms-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-white rounded-lg shadow-xl border-t-4 border-emerald-600">
                                <a href="{{ route('pagos.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 transition-colors font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ver Pagos
                                </a>
                                <a href="{{ route('pagos.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Registrar Pago
                                </a>
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @endrole

                    @role('Administrador|Comité Académico')
                    <!-- CERTIFICADOS -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('certificados.*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                Certificados
                                <svg class="ms-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-white rounded-lg shadow-xl border-t-4 border-yellow-600">
                                <a href="{{ route('certificados.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 transition-colors font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ver Certificados
                                </a>
                                <a href="{{ route('certificados.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Generar Certificados
                                </a>
                            </div>
                        </x-slot>
                    </x-dropdown>

                    <!-- REPORTES -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('reportes.*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Reportes
                                <svg class="ms-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-white rounded-lg shadow-xl border-t-4 border-red-600">
                                <a href="{{ route('reportes.inscripciones') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Inscripciones
                                </a>
                                <a href="{{ route('reportes.calificaciones') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Calificaciones
                                </a>
                                <a href="{{ route('reportes.asistencia') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Asistencia
                                </a>
                                <a href="{{ route('reportes.pagos') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Pagos
                                </a>
                                <a href="{{ route('reportes.certificados') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors border-t border-gray-100 font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    Certificados
                                </a>
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @endrole

                    @role('Docente')
                    <a href="{{ route('docentes.mis-cursos') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('docentes.mis-cursos') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Mis Cursos
                    </a>
                    <a href="{{ route('asistencias.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('asistencias.*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Asistencia
                    </a>
                    <a href="{{ route('evaluaciones.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('evaluaciones.*') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Evaluaciones
                    </a>
                    @endrole

                    @role('Estudiante')
                    <a href="{{ route('estudiantes.mis-cursos') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('estudiantes.mis-cursos') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Mis Cursos
                    </a>
                    <a href="{{ route('estudiantes.mis-inscripciones') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('estudiantes.mis-inscripciones') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Mis Inscripciones
                    </a>
                    <a href="{{ route('estudiantes.mis-certificados') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('estudiantes.mis-certificados') ? 'bg-white text-green-700 shadow-md' : 'text-white hover:bg-green-600' }} transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Mis Certificados
                    </a>
                    @endrole
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 bg-green-800 rounded-lg text-sm font-semibold text-white hover:bg-green-900 transition-all shadow-md">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center mr-2">
                                    <span class="font-bold text-green-700 text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden lg:block">{{ Auth::user()->name }}</span>
                            </div>
                            <svg class="ms-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="bg-white rounded-lg shadow-xl border-t-4 border-green-600">
                            <div class="px-4 py-4 bg-green-50">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-green-600 flex items-center justify-center mr-3">
                                        <span class="text-xl font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                            {{ Auth::user()->roles->first()->name ?? 'Sin rol' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors font-semibold border-b border-gray-100">
                                <svg class="w-4 h-4 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Mi Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-sm text-red-700 hover:bg-red-50 transition-colors font-semibold">
                                    <svg class="w-4 h-4 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-lg text-white hover:bg-green-600 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-green-800">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Inicio</a>
            
            @role('Administrador|Comité Académico')
            <a href="{{ route('cursos.index') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Cursos</a>
            <a href="{{ route('estudiantes.index') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Estudiantes</a>
            <a href="{{ route('docentes.index') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Docentes</a>
            <a href="{{ route('inscripciones.index') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Inscripciones</a>
            @endrole
            
            @role('Administrador')
            <a href="{{ route('pagos.index') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Pagos</a>
            @endrole
            
            @role('Administrador|Comité Académico')
            <a href="{{ route('certificados.index') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Certificados</a>
            <a href="{{ route('reportes.inscripciones') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">📊 Reportes</a>
            @endrole

            @role('Docente')
            <a href="{{ route('docentes.mis-cursos') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Mis Cursos</a>
            <a href="{{ route('asistencias.index') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Asistencia</a>
            <a href="{{ route('evaluaciones.index') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Evaluaciones</a>
            @endrole

            @role('Estudiante')
            <a href="{{ route('estudiantes.mis-cursos') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Mis Cursos</a>
            <a href="{{ route('estudiantes.mis-inscripciones') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Mis Inscripciones</a>
            <a href="{{ route('estudiantes.mis-certificados') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Mis Certificados</a>
            @endrole
        </div>
        
        <div class="pt-4 pb-1 border-t border-green-600">
            <div class="px-4 mb-3">
                <p class="text-white font-bold">{{ Auth::user()->name }}</p>
                <p class="text-green-200 text-sm">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-white font-semibold hover:bg-green-700">Mi Perfil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-white font-semibold hover:bg-green-700">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</nav>