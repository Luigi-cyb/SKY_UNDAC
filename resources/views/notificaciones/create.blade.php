<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">✉️ Enviar Nueva Notificación</h2>
                <p class="text-sm text-gray-600 mt-1">Crea y envía notificaciones</p>
            </div>
            <a href="{{ route('notificaciones.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 rounded-lg font-semibold text-sm text-white uppercase hover:bg-gray-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                <h3 class="text-red-800 font-bold mb-2">¡Hay errores!</h3>
                <ul class="list-disc list-inside text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('notificaciones.store') }}" method="POST">
                @csrf

                <!-- Configuración -->
                <div class="bg-white shadow-xl rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important;">
                        <h3 class="text-lg font-bold" style="color: white !important;">⚙️ Configuración</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipo <span class="text-red-500">*</span></label>
                            <select name="tipo" id="tipo" class="block w-full rounded-lg border-gray-300" required>
                                <option value="">Seleccionar</option>
                                <option value="email">📧 Email</option>
                                <option value="sistema">🔔 Sistema</option>
                                <option value="ambos">📧🔔 Ambos</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Prioridad</label>
                            <select name="prioridad" class="block w-full rounded-lg border-gray-300">
                                <option value="baja">🔵 Baja</option>
                                <option value="normal" selected>🟢 Normal</option>
                                <option value="alta">🟠 Alta</option>
                                <option value="urgente">🔴 Urgente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Destinatarios -->
                <div class="bg-white shadow-xl rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #10b981, #059669) !important;">
                        <h3 class="text-lg font-bold" style="color: white !important;">👥 Destinatarios</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Seleccionar <span class="text-red-500">*</span></label>
                            <select name="destinatario_tipo" id="destinatario_tipo" class="block w-full rounded-lg border-gray-300" required>
                                <option value="">Seleccionar</option>
                                <option value="todos_estudiantes">👥 Todos los Estudiantes</option>
                                <option value="todos_docentes">👨‍🏫 Todos los Docentes</option>
                                <option value="curso_especifico">📚 Curso Específico</option>
                                <option value="estudiante_individual">👤 Estudiante</option>
                                <option value="docente_individual">👨‍🏫 Docente</option>
                            </select>
                        </div>

                        <div id="selectorCurso" class="hidden">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Curso</label>
                            <select name="curso_id" id="curso_id" class="block w-full rounded-lg border-gray-300">
                                <option value="">Seleccionar</option>
                                @if(isset($cursos))
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div id="selectorEstudiante" class="hidden">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Estudiante</label>
                            <select name="estudiante_id" id="estudiante_id" class="block w-full rounded-lg border-gray-300">
                                <option value="">Seleccionar</option>
                                @if(isset($estudiantes))
                                    @foreach($estudiantes as $est)
                                        <option value="{{ $est->id }}">{{ $est->nombre }} {{ $est->apellido }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div id="selectorDocente" class="hidden">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Docente</label>
                            <select name="docente_id" id="docente_id" class="block w-full rounded-lg border-gray-300">
                                <option value="">Seleccionar</option>
                                @if(isset($docentes))
                                    @foreach($docentes as $doc)
                                        <option value="{{ $doc->id }}">{{ $doc->nombre }} {{ $doc->apellido }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="bg-white shadow-xl rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #f59e0b, #d97706) !important;">
                        <h3 class="text-lg font-bold" style="color: white !important;">✏️ Mensaje</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Asunto <span class="text-red-500">*</span></label>
                            <input type="text" name="asunto" id="asunto" class="block w-full rounded-lg border-gray-300" placeholder="Ej: Inicio de curso" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Contenido <span class="text-red-500">*</span></label>
                            <textarea name="contenido" id="contenido" rows="8" class="block w-full rounded-lg border-gray-300" placeholder="Escribe aquí..." required></textarea>
                            <div class="mt-2 p-3 bg-yellow-50 rounded-lg">
                                <p class="text-xs font-bold text-yellow-800">📝 Variables: <code>{nombre}</code> <code>{apellido}</code> <code>{curso}</code></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programación -->
                <div class="bg-white shadow-xl rounded-xl mb-6">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #8b5cf6, #7c3aed) !important;">
                        <h3 class="text-lg font-bold" style="color: white !important;">⏰ Programación</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="programar" id="programar" value="1" class="rounded">
                            <label for="programar" class="ml-2 text-sm font-bold">Programar envío</label>
                        </div>
                        <div id="fechaPrograma" class="mt-4 hidden">
                            <input type="datetime-local" name="fecha_envio" id="fecha_envio" min="{{ now()->format('Y-m-d\TH:i') }}" class="block w-full rounded-lg border-gray-300">
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="bg-white shadow-xl rounded-xl">
                    <div class="p-6 flex justify-between">
                        <a href="{{ route('notificaciones.index') }}" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded-lg">Cancelar</a>
                        <button type="submit" class="px-6 py-3 font-bold rounded-lg text-white" style="background: linear-gradient(to right, #6366f1, #4f46e5) !important;">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('destinatario_tipo').addEventListener('change', function() {
            const v = this.value;
            document.getElementById('selectorCurso').classList.add('hidden');
            document.getElementById('selectorEstudiante').classList.add('hidden');
            document.getElementById('selectorDocente').classList.add('hidden');
            if(v === 'curso_especifico') document.getElementById('selectorCurso').classList.remove('hidden');
            if(v === 'estudiante_individual') document.getElementById('selectorEstudiante').classList.remove('hidden');
            if(v === 'docente_individual') document.getElementById('selectorDocente').classList.remove('hidden');
        });
        document.getElementById('programar').addEventListener('change', function() {
            document.getElementById('fechaPrograma').classList.toggle('hidden', !this.checked);
        });
    </script>
    @endpush
</x-app-layout>