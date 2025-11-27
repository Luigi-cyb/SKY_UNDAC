<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Responder Encuesta') }}
            </h2>
            <a href="{{ route('encuestas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información de la Encuesta -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-l-4 border-blue-500">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $encuesta->titulo }}</h3>
                            @if($encuesta->descripcion)
                            <p class="mt-2 text-sm text-gray-600">{{ $encuesta->descripcion }}</p>
                            @endif
                            <div class="mt-4 flex flex-wrap gap-3">
                                @if($encuesta->curso)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $encuesta->curso->nombre }}
                                </span>
                                @endif
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ ucfirst($encuesta->tipo) }}
                                </span>
                                @if($encuesta->anonima)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                    Anónima
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensaje de ya respondida -->
            @if($yaRespondida)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Ya has respondido esta encuesta anteriormente.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Formulario de Respuestas -->
            @if(!$yaRespondida)
            <form method="POST" action="{{ route('encuestas.guardar-respuestas', $encuesta->id) }}">
                @csrf

                <div class="space-y-6">
                    @foreach($encuesta->preguntas as $index => $pregunta)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">
                                    {{ $index + 1 }}. {{ $pregunta->texto }}
                                    @if($pregunta->obligatoria)
                                    <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if($pregunta->tipo == 'escala')
                                <!-- Escala 1-5 -->
                                <div class="flex items-center space-x-4 mt-4">
                                    <span class="text-sm text-gray-600">Muy insatisfecho</span>
                                    <div class="flex space-x-3">
                                        @for($i = 1; $i <= 5; $i++)
                                        <label class="flex flex-col items-center cursor-pointer">
                                            <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{ $i }}" 
                                                {{ $pregunta->obligatoria ? 'required' : '' }}
                                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <span class="mt-1 text-xs text-gray-600">{{ $i }}</span>
                                        </label>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600">Muy satisfecho</span>
                                </div>

                                @elseif($pregunta->tipo == 'opcion_multiple')
                                <!-- Opción Múltiple -->
                                <div class="space-y-2 mt-4">
                                    @php
                                        $opciones = is_array($pregunta->opciones) ? $pregunta->opciones : json_decode($pregunta->opciones, true);
                                    @endphp
                                    @if($opciones)
                                        @foreach($opciones as $opcion)
                                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{ $opcion }}"
                                                {{ $pregunta->obligatoria ? 'required' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <span class="ml-3 text-sm text-gray-900">{{ $opcion }}</span>
                                        </label>
                                        @endforeach
                                    @endif
                                </div>

                                @elseif($pregunta->tipo == 'si_no')
                                <!-- Sí/No -->
                                <div class="flex space-x-4 mt-4">
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer flex-1">
                                        <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="Si"
                                            {{ $pregunta->obligatoria ? 'required' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-3 text-sm text-gray-900 font-medium">Sí</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer flex-1">
                                        <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="No"
                                            {{ $pregunta->obligatoria ? 'required' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-3 text-sm text-gray-900 font-medium">No</span>
                                    </label>
                                </div>

                                @elseif($pregunta->tipo == 'texto_corto')
                                <!-- Texto Corto -->
                                <input type="text" name="respuestas[{{ $pregunta->id }}]"
                                    {{ $pregunta->obligatoria ? 'required' : '' }}
                                    class="mt-4 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Tu respuesta...">

                                @elseif($pregunta->tipo == 'texto_largo')
                                <!-- Texto Largo -->
                                <textarea name="respuestas[{{ $pregunta->id }}]" rows="4"
                                    {{ $pregunta->obligatoria ? 'required' : '' }}
                                    class="mt-4 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Tu respuesta..."></textarea>
                                @endif

                                @error('respuestas.' . $pregunta->id)
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Botones -->
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600">
                                <span class="text-red-500">*</span> Campos obligatorios
                            </p>
                            <div class="flex space-x-4">
                                <a href="{{ route('encuestas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancelar
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Enviar Respuestas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @else
            <!-- Botón para volver si ya respondió -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <a href="{{ route('encuestas.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Volver a Encuestas
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>