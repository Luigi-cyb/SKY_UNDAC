<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    🔔 Gestión de Notificaciones
                </h2>
                <p class="text-sm text-gray-600 mt-1">Administra todas las notificaciones del sistema</p>
            </div>
            <a href="{{ route('notificaciones.create') }}" 
               class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition transform hover:scale-105 shadow-lg"
               style="background: linear-gradient(to right, #3b82f6, #2563eb) !important; color: white !important;">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Notificación
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alertas -->
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

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-md">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-red-800 font-semibold">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- Filtros -->
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
                    <form method="GET" action="{{ route('notificaciones.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Buscar</label>
                            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por asunto o contenido..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipo</label>
                            <select name="tipo" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="">Todos</option>
                                <option value="email" {{ request('tipo') == 'email' ? 'selected' : '' }}>📧 Email</option>
                                <option value="sistema" {{ request('tipo') == 'sistema' ? 'selected' : '' }}>🔔 Sistema</option>
                                <option value="sms" {{ request('tipo') == 'sms' ? 'selected' : '' }}>📱 SMS</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                            <select name="estado" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>✅ Enviado</option>
                                <option value="fallido" {{ request('estado') == 'fallido' ? 'selected' : '' }}>❌ Fallido</option>
                                <option value="leido" {{ request('estado') == 'leido' ? 'selected' : '' }}>👁️ Leído</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg font-bold text-sm uppercase tracking-wide transition shadow-lg" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important; color: white !important;">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Total</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['total'] ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #f59e0b, #d97706) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Pendientes</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['pendientes'] ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #10b981, #059669) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Enviadas</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['enviadas'] ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="rounded-xl shadow-lg p-6" style="background: linear-gradient(to bottom right, #ef4444, #dc2626) !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase" style="color: rgba(255, 255, 255, 0.9) !important;">Fallidas</p>
                            <p class="text-4xl font-bold mt-2" style="color: white !important;">{{ $estadisticas['fallidas'] ?? 0 }}</p>
                        </div>
                        <svg class="h-16 w-16" style="color: rgba(255, 255, 255, 0.3) !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        Lista de Notificaciones
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Asunto</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Destinatario</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Tipo</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Estado</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Fecha</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($notificaciones as $notificacion)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ Str::limit($notificacion->asunto, 40) }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($notificacion->mensaje, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $notificacion->destinatario_nombre }}</div>
                                        <div class="text-xs text-gray-500">{{ $notificacion->destinatario_email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold @if($notificacion->tipo === 'email') bg-blue-100 text-blue-800 @elseif($notificacion->tipo === 'sistema') bg-purple-100 text-purple-800 @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($notificacion->tipo) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold @if($notificacion->estado === 'enviado') bg-green-100 text-green-800 @elseif($notificacion->estado === 'pendiente') bg-yellow-100 text-yellow-800 @elseif($notificacion->estado === 'fallido') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($notificacion->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($notificacion->created_at)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($notificacion->created_at)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button onclick="verDetalle({{ $notificacion->id }})" class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-xs font-semibold">
                                                Ver
                                            </button>
                                            @if($notificacion->estado === 'pendiente' || $notificacion->estado === 'fallido')
                                            <form action="{{ route('notificaciones.reenviar', $notificacion) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('¿Reenviar?')" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition text-xs font-semibold">
                                                    Reenviar
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('notificaciones.destroy', $notificacion) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar?')" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-xs font-semibold">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <p class="text-gray-500">No hay notificaciones</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($notificaciones->hasPages())
                    <div class="mt-6">{{ $notificaciones->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="modalDetalle" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
            <div class="px-6 py-4 border-b flex justify-between items-center" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important;">
                <h3 class="text-lg font-bold" style="color: white !important;">Detalle</h3>
                <button onclick="cerrarModal()" class="hover:bg-white hover:bg-opacity-20 rounded p-1" style="color: white !important;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="contenidoDetalle" class="p-6"></div>
        </div>
    </div>

    @push('scripts')
    <script>
        function verDetalle(id) {
            fetch('/notificaciones/' + id + '/detalle')
                .then(r => r.json())
                .then(d => {
                    document.getElementById('contenidoDetalle').innerHTML = '<div class="space-y-4"><div class="bg-gray-50 rounded p-4"><strong>Asunto:</strong> ' + d.asunto + '</div><div class="bg-gray-50 rounded p-4"><strong>Mensaje:</strong> ' + d.mensaje + '</div></div>';
                    document.getElementById('modalDetalle').classList.remove('hidden');
                });
        }
        function cerrarModal() {
            document.getElementById('modalDetalle').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>