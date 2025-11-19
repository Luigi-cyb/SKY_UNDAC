<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">
            📝 Mis Inscripciones
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(isset($mensaje))
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
                <p class="text-yellow-800">{{ $mensaje }}</p>
            </div>
            @endif

            <!-- Estadísticas -->
            @if(isset($estadisticas))
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ $estadisticas['total'] }}</p>
                    <p class="text-sm text-gray-600">Total</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $estadisticas['confirmadas'] }}</p>
                    <p class="text-sm text-gray-600">Confirmadas</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $estadisticas['pendientes'] }}</p>
                    <p class="text-sm text-gray-600">Pendientes</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-3xl font-bold text-red-600">{{ $estadisticas['canceladas'] }}</p>
                    <p class="text-sm text-gray-600">Canceladas</p>
                </div>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(isset($inscripciones) && $inscripciones->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Curso</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inscripciones as $inscripcion)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $inscripcion->curso->nombre }}</div>
                                    <div class="text-sm text-gray-500">{{ $inscripcion->curso->codigo }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $inscripcion->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($inscripcion->estado == 'confirmada') bg-green-100 text-green-800
                                        @elseif($inscripcion->estado == 'provisional') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($inscripcion->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📝</div>
                    <p class="text-gray-600 text-lg font-semibold">No tienes inscripciones registradas</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>