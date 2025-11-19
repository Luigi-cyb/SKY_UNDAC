<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    👤 Mi Perfil
                </h2>
                <p class="text-sm text-gray-600 mt-1">Administra tu información personal y configuración de cuenta</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Grid de 2 columnas en desktop -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Columna izquierda: Card de usuario -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl sticky top-6">
                        <div class="px-6 py-8" style="background: linear-gradient(to bottom, #6366f1, #4f46e5) !important;">
                            <div class="flex flex-col items-center">
                                <div class="h-32 w-32 rounded-full flex items-center justify-center mb-4 shadow-lg" style="background: rgba(255, 255, 255, 0.2) !important; backdrop-filter: blur(10px);">
                                    <span class="text-6xl font-bold" style="color: white !important;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </span>
                                </div>
                                <h3 class="text-2xl font-bold mb-1" style="color: white !important;">{{ Auth::user()->name }}</h3>
                                <p class="text-sm mb-4" style="color: rgba(255, 255, 255, 0.9) !important;">{{ Auth::user()->email }}</p>
                                
                                <div class="w-full space-y-2 mt-4">
                                    <div class="flex items-center justify-between px-4 py-2 rounded-lg" style="background: rgba(255, 255, 255, 0.1) !important;">
                                        <span class="text-sm" style="color: rgba(255, 255, 255, 0.9) !important;">Miembro desde</span>
                                        <span class="text-sm font-semibold" style="color: white !important;">{{ Auth::user()->created_at->format('M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between px-4 py-2 rounded-lg" style="background: rgba(255, 255, 255, 0.1) !important;">
                                        <span class="text-sm" style="color: rgba(255, 255, 255, 0.9) !important;">Último acceso</span>
                                        <span class="text-sm font-semibold" style="color: white !important;">Hoy</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 border-t border-gray-100">
                            <h4 class="text-sm font-bold text-gray-700 uppercase mb-3">Accesos Rápidos</h4>
                            <div class="space-y-2">
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="h-5 w-5 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('cursos.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="h-5 w-5 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Mis Cursos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Formularios -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Información del Perfil -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                        <div class="px-6 py-4" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important;">
                            <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información del Perfil
                            </h3>
                            <p class="text-sm mt-1" style="color: rgba(255, 255, 255, 0.9) !important;">Actualiza tu información personal y dirección de correo</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Actualizar Contraseña -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                        <div class="px-6 py-4" style="background: linear-gradient(to right, #10b981, #059669) !important;">
                            <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Actualizar Contraseña
                            </h3>
                            <p class="text-sm mt-1" style="color: rgba(255, 255, 255, 0.9) !important;">Asegúrate de usar una contraseña larga y segura</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Eliminar Cuenta -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-2 border-red-100">
                        <div class="px-6 py-4" style="background: linear-gradient(to right, #ef4444, #dc2626) !important;">
                            <h3 class="text-lg font-bold flex items-center" style="color: white !important;">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Zona de Peligro
                            </h3>
                            <p class="text-sm mt-1" style="color: rgba(255, 255, 255, 0.9) !important;">Eliminar permanentemente tu cuenta del sistema</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>