<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\ListaEspera;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // abort_unless(Auth::user()->can('inscripciones.index'), 403); // ✅ COMENTADO

    try {
        $query = Inscripcion::with(['estudiante', 'curso', 'pago']);

        // 🔍 Búsqueda avanzada
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigo_inscripcion', 'like', "%{$search}%")
                    ->orWhereHas('estudiante', function ($q2) use ($search) {
                        $q2->where('dni', 'like', "%{$search}%")
                            ->orWhere('nombres', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%");
                    })
                    ->orWhereHas('curso', function ($q2) use ($search) {
                        $q2->where('nombre', 'like', "%{$search}%")
                            ->orWhere('codigo', 'like', "%{$search}%");
                    });
            });
        }

        // 📊 Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // 📚 Filtro por curso
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        $inscripciones = $query->orderBy('created_at', 'desc')->paginate(15);

        // 📈 Estadísticas
        $stats = [
            'total' => Inscripcion::count(),
            'provisional' => Inscripcion::where('estado', 'provisional')->count(),
            'confirmada' => Inscripcion::where('estado', 'confirmada')->count(),
            'cancelada' => Inscripcion::where('estado', 'cancelada')->count(),
            'pagos_pendientes' => Inscripcion::where('pago_confirmado', false)
                ->whereIn('estado', ['provisional', 'confirmada'])->count(),
        ];

        // 📚 Cursos para filtro
        $cursos = Curso::whereIn('estado', ['convocatoria', 'en_curso'])
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'codigo']);

        return view('inscripciones.index', compact('inscripciones', 'stats', 'cursos'));

    } catch (\Exception $e) {
        \Log::error('Error en InscripcionController@index: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al cargar las inscripciones.');
    }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // abort_unless(Auth::user()->can('inscripciones.create'), 403); // ✅ COMENTADO

        // 📚 Cursos disponibles
        $cursos = Curso::whereIn('estado', ['convocatoria', 'en_curso'])
            ->with(['modalidad', 'categoria'])
            ->withCount(['inscripciones' => function ($query) {
                $query->whereIn('estado', ['provisional', 'confirmada']);
            }])
            ->get();

        // 🎓 Estudiantes activos
        $estudiantes = Estudiante::where('activo', true)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get(['id', 'dni', 'nombres', 'apellidos', 'correo_institucional']);

        return view('inscripciones.create', compact('cursos', 'estudiantes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // abort_unless(Auth::user()->can('inscripciones.store'), 403); // ✅ COMENTADO

    // ✅ LOG para ver qué datos llegan
    \Log::info('Datos recibidos:', $request->all());

    // Validación inline SIMPLIFICADA
    try {
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'curso_id' => 'required|exists:cursos,id',
            'fecha_inscripcion' => 'nullable|date',
            'observaciones' => 'nullable|string|max:500',
        ]);
    } catch (\Exception $e) {
        \Log::error('Error de validación: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error de validación: ' . $e->getMessage());
    }

    \Log::info('Validación pasada');

    DB::beginTransaction();

    try {
        $curso = Curso::findOrFail($validated['curso_id']);
        $estudiante = Estudiante::findOrFail($validated['estudiante_id']);

        \Log::info('Curso encontrado: ' . $curso->id);
        \Log::info('Estudiante encontrado: ' . $estudiante->id);

        // 🔐 Generar código único
        $codigoInscripcion = 'INS-' . date('Y') . '-' . strtoupper(Str::random(8));

        \Log::info('Código generado: ' . $codigoInscripcion);

        // 📝 Crear inscripción
        $inscripcion = Inscripcion::create([
            'estudiante_id' => $validated['estudiante_id'],
            'curso_id' => $validated['curso_id'],
            'codigo_inscripcion' => $codigoInscripcion,
            'fecha_inscripcion' => $validated['fecha_inscripcion'] ?? now(),
            'estado' => 'provisional',
            'pago_confirmado' => false,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        \Log::info('Inscripción creada: ' . $inscripcion->id);

        DB::commit();

// Limpia cualquier caché
\Artisan::call('view:clear');

return redirect()->route('inscripciones.index')
    ->with('success', "✅ Inscripción creada exitosamente. Código: {$codigoInscripcion}");
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al crear inscripción: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->withInput()
            ->with('error', '❌ Error al crear la inscripción: ' . $e->getMessage());
    }
}
    /**
     * Display the specified resource.
     */
    public function show(Inscripcion $inscripcion)
{
    try {
        $inscripcion->load([
            'estudiante',
            'curso.modalidad',
            'asistencias',
            'calificaciones.evaluacion',
        ]);

        // Estadísticas de la inscripción
        $stats = [
            'total_asistencias' => $inscripcion->asistencias->count(),
            'asistencias_presente' => $inscripcion->asistencias->where('estado', 'presente')->count(),
            'porcentaje_asistencia' => $inscripcion->asistencias->count() > 0 
                ? round(($inscripcion->asistencias->where('estado', 'presente')->count() / $inscripcion->asistencias->count()) * 100, 2)
                : 0,
            'promedio_notas' => $inscripcion->calificaciones->avg('nota') ?? 0,
            'total_evaluaciones' => $inscripcion->calificaciones->count(),
        ];

        return view('inscripciones.show', compact('inscripcion', 'stats'));

    } catch (\Exception $e) {
        \Log::error('Error en InscripcionController@show: ' . $e->getMessage());
        return redirect()->route('inscripciones.index')
            ->with('error', 'Error al cargar la información de la inscripción.');
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inscripcion $inscripcion)
    {
        // abort_unless(Auth::user()->can('inscripciones.edit'), 403); // ✅ COMENTADO

        $cursos = Curso::whereIn('estado', ['convocatoria', 'en_curso'])
            ->orderBy('nombre')
            ->get();

        $estudiantes = Estudiante::where('activo', true)
            ->orderBy('apellidos')
            ->get(['id', 'dni', 'nombres', 'apellidos']);

        return view('inscripciones.edit', compact('inscripcion', 'cursos', 'estudiantes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inscripcion $inscripcion)
    {
        // abort_unless(Auth::user()->can('inscripciones.update'), 403); // ✅ COMENTADO

        // Validación inline
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'curso_id' => 'required|exists:cursos,id',
            'fecha_inscripcion' => 'required|date',
            'estado' => 'required|in:provisional,confirmada,cancelada,rechazada',
            'pago_confirmado' => 'required|boolean',
            'observaciones' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // 📝 Guardar estado anterior para notificaciones
            $estadoAnterior = $inscripcion->estado;

            // 🔄 Actualizar inscripción
            $inscripcion->update($validated);

            // 📧 Notificar cambios de estado
            if ($estadoAnterior !== $validated['estado']) {
                $mensajes = [
                    'confirmada' => 'Tu inscripción ha sido confirmada.',
                    'cancelada' => 'Tu inscripción ha sido cancelada.',
                    'rechazada' => 'Tu inscripción ha sido rechazada.',
                ];

                if (isset($mensajes[$validated['estado']]) && $inscripcion->estudiante->user_id) {
                    Notificacion::create([
                        'user_id' => $inscripcion->estudiante->user_id,
                        'tipo' => 'cambio_estado_inscripcion',
                        'titulo' => 'Estado de Inscripción Actualizado',
                        'mensaje' => $mensajes[$validated['estado']],
                        'leida' => false,
                    ]);
                }

                // 🔄 Si se cancela o rechaza, liberar cupo y procesar lista de espera
                if (in_array($validated['estado'], ['cancelada', 'rechazada'])) {
                    $this->procesarListaEspera($inscripcion->curso_id);
                }
            }

            DB::commit();

            Log::info("Inscripción actualizada: {$inscripcion->id} - Estado: {$validated['estado']}");

            return redirect()->route('inscripciones.index')
                ->with('success', '✅ Inscripción actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar inscripción: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inscripcion $inscripcion)
    {
        // abort_unless(Auth::user()->can('inscripciones.destroy'), 403); // ✅ COMENTADO

        DB::beginTransaction();

        try {
            // ✅ Validar que solo se eliminen inscripciones provisionales
            if ($inscripcion->estado !== 'provisional') {
                return redirect()->route('inscripciones.index')
                    ->with('error', '❌ Solo se pueden eliminar inscripciones en estado provisional.');
            }

            // ✅ Verificar que no tenga pagos asociados
            if ($inscripcion->pago()->exists()) {
                return redirect()->route('inscripciones.index')
                    ->with('error', '❌ No se puede eliminar una inscripción con pagos registrados.');
            }

            $cursoId = $inscripcion->curso_id;
            $estudianteNombre = $inscripcion->estudiante->nombres . ' ' . $inscripcion->estudiante->apellidos;

            $inscripcion->delete();

            // 🔄 Procesar lista de espera
            $this->procesarListaEspera($cursoId);

            DB::commit();

            Log::warning("Inscripción eliminada: Estudiante {$estudianteNombre}");

            return redirect()->route('inscripciones.index')
                ->with('success', '✅ Inscripción eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar inscripción: ' . $e->getMessage());
            
            return redirect()->route('inscripciones.index')
                ->with('error', '❌ Error al eliminar la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar inscripción
     */
    public function confirmar(Inscripcion $inscripcion)
    {
        // abort_unless(Auth::user()->can('inscripciones.update'), 403); // ✅ COMENTADO

        DB::beginTransaction();

        try {
            // ✅ Validar que el pago esté confirmado
            if (!$inscripcion->pago_confirmado) {
                return redirect()->back()
                    ->with('error', '❌ No se puede confirmar la inscripción sin pago confirmado.');
            }

            $inscripcion->update([
                'estado' => 'confirmada',
            ]);

            // 📧 Notificar al estudiante
            if ($inscripcion->estudiante->user_id) {
                Notificacion::create([
                    'user_id' => $inscripcion->estudiante->user_id,
                    'tipo' => 'inscripcion_confirmada',
                    'titulo' => 'Inscripción Confirmada',
                    'mensaje' => "Tu inscripción al curso '{$inscripcion->curso->nombre}' ha sido confirmada. ¡Nos vemos en clase!",
                    'leida' => false,
                ]);
            }

            DB::commit();

            Log::info("Inscripción confirmada: {$inscripcion->id}");

            return redirect()->back()
                ->with('success', '✅ Inscripción confirmada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al confirmar inscripción: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', '❌ Error al confirmar la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * 🔄 Procesar lista de espera automáticamente
     */
    protected function procesarListaEspera($cursoId)
    {
        try {
            $curso = Curso::find($cursoId);
            
            if (!$curso) return;

            // 📊 Contar cupos disponibles
            $inscritosActuales = $curso->inscripciones()
                ->whereIn('estado', ['provisional', 'confirmada'])
                ->count();

            $cuposDisponibles = $curso->cupo_maximo - $inscritosActuales;

            if ($cuposDisponibles <= 0) return;

            // 📋 Obtener estudiantes en espera
            $enEspera = ListaEspera::where('curso_id', $cursoId)
                ->where('estado', 'en_espera')
                ->orderBy('posicion')
                ->limit($cuposDisponibles)
                ->get();

            foreach ($enEspera as $espera) {
                // 🔐 Generar código de inscripción
                do {
                    $codigoInscripcion = 'INS-' . date('Y') . '-' . strtoupper(Str::random(8));
                } while (Inscripcion::where('codigo_inscripcion', $codigoInscripcion)->exists());

                // ✅ Crear inscripción
                Inscripcion::create([
                    'estudiante_id' => $espera->estudiante_id,
                    'curso_id' => $espera->curso_id,
                    'codigo_inscripcion' => $codigoInscripcion,
                    'fecha_inscripcion' => now(),
                    'estado' => 'provisional',
                    'pago_confirmado' => false,
                    'observaciones' => 'Ingresado desde lista de espera',
                ]);

                // 📧 Notificar al estudiante
                if ($espera->estudiante->user_id) {
                    Notificacion::create([
                        'user_id' => $espera->estudiante->user_id,
                        'tipo' => 'cupo_disponible',
                        'titulo' => '¡Cupo Disponible!',
                        'mensaje' => "Se ha liberado un cupo en el curso '{$curso->nombre}'. Tu inscripción ha sido procesada.",
                        'leida' => false,
                    ]);
                }

                // 🔄 Actualizar estado en lista de espera
                $espera->update(['estado' => 'asignado']);

                Log::info("Estudiante {$espera->estudiante_id} promovido desde lista de espera al curso {$cursoId}");
            }

        } catch (\Exception $e) {
            Log::error('Error al procesar lista de espera: ' . $e->getMessage());
        }
    }

    /**
     * 📋 Ver lista de espera de un curso
     */
    public function listaEspera($cursoId)
    {
        // abort_unless(Auth::user()->can('inscripciones.index'), 403); // ✅ COMENTADO

        try {
            $curso = Curso::findOrFail($cursoId);
            
            $listaEspera = ListaEspera::where('curso_id', $cursoId)
                ->where('estado', 'en_espera')
                ->with('estudiante')
                ->orderBy('posicion')
                ->get();

            return view('inscripciones.lista-espera', compact('curso', 'listaEspera'));

        } catch (\Exception $e) {
            Log::error('Error al cargar lista de espera: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al cargar la lista de espera.');
        }
    }

    /**
     * 💰 Marcar pago como confirmado
     */
    public function confirmarPago(Inscripcion $inscripcion)
    {
        // abort_unless(Auth::user()->can('pagos.confirmar'), 403); // ✅ COMENTADO

        try {
            $inscripcion->update(['pago_confirmado' => true]);

            // 📧 Notificar al estudiante
            if ($inscripcion->estudiante->user_id) {
                Notificacion::create([
                    'user_id' => $inscripcion->estudiante->user_id,
                    'tipo' => 'pago_confirmado',
                    'titulo' => 'Pago Confirmado',
                    'mensaje' => "Tu pago para el curso '{$inscripcion->curso->nombre}' ha sido confirmado.",
                    'leida' => false,
                ]);
            }

            Log::info("Pago confirmado para inscripción: {$inscripcion->id}");

            return redirect()->back()
                ->with('success', '✅ Pago confirmado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al confirmar pago: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al confirmar el pago.');
        }
    }

    /**
     * 🔍 Buscar inscripciones (para AJAX)
     */
    public function buscar(Request $request)
    {
        // abort_unless(Auth::user()->can('inscripciones.index'), 403); // ✅ COMENTADO

        try {
            $search = $request->get('q', '');
            
            $inscripciones = Inscripcion::with(['estudiante', 'curso'])
                ->where(function ($query) use ($search) {
                    $query->where('codigo_inscripcion', 'like', "%{$search}%")
                        ->orWhereHas('estudiante', function ($q) use ($search) {
                            $q->where('dni', 'like', "%{$search}%")
                                ->orWhere('nombres', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%");
                        });
                })
                ->limit(10)
                ->get(['id', 'codigo_inscripcion', 'estudiante_id', 'curso_id', 'estado']);

            return response()->json($inscripciones);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de inscripciones: ' . $e->getMessage());
            return response()->json(['error' => 'Error en la búsqueda'], 500);
        }
    }
}