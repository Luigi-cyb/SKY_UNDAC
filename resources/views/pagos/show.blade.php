<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Pago
            </h2>
            <a href="{{ route('pagos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información del Pago -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Información del Pago</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Código de Pago:</p>
                            <p class="font-semibold">{{ $pago->codigo_pago }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Estado:</p>
                            @if($pago->estado == 'confirmado')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmado</span>
                            @elseif($pago->estado == 'pendiente')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rechazado</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Monto:</p>
                            <p class="font-semibold text-green-600 text-xl">S/ {{ number_format($pago->monto, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Pago:</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Método de Pago:</p>
                            <p class="font-semibold">{{ $pago->metodoPago->nombre }}</p>
                        </div>
                        @if($pago->numero_operacion)
                        <div>
                            <p class="text-sm text-gray-600">Número de Operación:</p>
                            <p class="font-semibold">{{ $pago->numero_operacion }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del Estudiante -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Información del Estudiante</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nombre Completo:</p>
                            <p class="font-semibold">{{ $pago->inscripcion->estudiante->nombres }} {{ $pago->inscripcion->estudiante->apellidos }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">DNI:</p>
                            <p class="font-semibold">{{ $pago->inscripcion->estudiante->dni }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Correo:</p>
                            <p class="font-semibold">{{ $pago->inscripcion->estudiante->correo_institucional }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Curso -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Información del Curso</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nombre del Curso:</p>
                            <p class="font-semibold">{{ $pago->inscripcion->curso->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Código:</p>
                            <p class="font-semibold">{{ $pago->inscripcion->curso->codigo }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>