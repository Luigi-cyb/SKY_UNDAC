<?php

namespace App\Http\Controllers;

use App\Models\PreguntaEvaluacion;
use App\Models\OpcionPregunta;
use App\Models\RespuestaEvaluacion;
use App\Models\Evaluacion;
use App\Models\Curso;
use App\Models\Calificacion;
use App\Models\Notificacion;
use App\Http\Requests\EvaluacionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class EvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $user = auth()->user();
    
    // ✅ Obtener solo los cursos del docente actual
    $docente = $user->docente;
    
    if (!$docente) {
        abort(403, 'No se encontró perfil de docente.');
    }
    
    // Cursos asignados al docente actual
    $cursos = \App\Models\Curso::whereHas('asignacionesDocentes', function($q) use ($docente) {
        $q->where('docente_id', $docente->id)
          ->where('activo', true);
    })->orderBy('nombre')->get();
    
    // ✅ Query base: solo evaluaciones de los cursos del docente
    $query = \App\Models\Evaluacion::with(['curso'])
        ->whereIn('curso_id', $cursos->pluck('id'));
    
    // Filtros
    if ($request->filled('curso_id')) {
        $query->where('curso_id', $request->curso_id);
    }
    
    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }
    
    if ($request->filled('activo')) {
        $query->where('activo', $request->activo);
    }
    
    $evaluaciones = $query->orderBy('fecha_evaluacion', 'desc')->paginate(15);
    
    // Estadísticas solo de los cursos del docente
    $stats = [
        'total' => \App\Models\Evaluacion::whereIn('curso_id', $cursos->pluck('id'))->count(),
        'activas' => \App\Models\Evaluacion::whereIn('curso_id', $cursos->pluck('id'))
            ->where('activo', true)->count(),
        'con_calificaciones' => \App\Models\Evaluacion::whereIn('curso_id', $cursos->pluck('id'))
            ->whereHas('calificaciones')->count(),
        'pendientes' => \App\Models\Evaluacion::whereIn('curso_id', $cursos->pluck('id'))
            ->where('activo', true)
            ->whereDoesntHave('calificaciones')->count(),
    ];
    
    return view('evaluaciones.index', compact(
        'evaluaciones',
        'cursos',
        'stats'
    ));
}

    /**
     * Show the form for creating a new resource.
     */
   /**
 * Show the form for creating a new resource.
 */
public function create(Request $request)
{
    // abort_unless(Auth::user()->can('evaluaciones.create'), 403);

    $user = Auth::user();
    $curso_id = $request->curso_id;
    
    // ✅ Verificar si el usuario tiene rol de Docente
    if ($user->hasRole('Docente')) {
        // Obtener docente autenticado
        $docente = \App\Models\Docente::where('correo_institucional', $user->email)
            ->orWhere('correo_personal', $user->email)
            ->first();
        
        if ($docente) {
            // Obtener SOLO los cursos asignados al docente
            $cursos = Curso::whereHas('asignacionesDocentes', function($query) use ($docente) {
                    $query->where('docente_id', $docente->id)
                          ->where('activo', true);
                })
                ->whereIn('estado', ['convocatoria', 'en_curso', 'Activo'])
                ->with(['modalidad', 'categoria'])
                ->orderBy('nombre')
                ->get();
        } else {
            // Si es docente pero no tiene perfil, mostrar todos (por si acaso)
            $cursos = Curso::whereIn('estado', ['convocatoria', 'en_curso', 'Activo'])
                ->with(['modalidad', 'categoria'])
                ->orderBy('nombre')
                ->get();
        }
    } else {
        // ✅ Si es Admin o Comité Académico, mostrar TODOS los cursos
        $cursos = Curso::whereIn('estado', ['convocatoria', 'en_curso', 'Activo'])
            ->with(['modalidad', 'categoria'])
            ->orderBy('nombre')
            ->get();
    }
    
    // 👨‍🏫 Docentes activos
    $docentes = \App\Models\Docente::where('activo', true)
        ->orderBy('nombres')
        ->get();

    // 📊 Calcular peso disponible si hay curso seleccionado
    $pesoDisponible = 100;
    if ($curso_id) {
        $pesoUsado = Evaluacion::where('curso_id', $curso_id)
            ->where('activo', true)
            ->sum('peso_porcentaje');
        $pesoDisponible = 100 - $pesoUsado;
    }

    return view('evaluaciones.create', compact('cursos', 'docentes', 'curso_id', 'pesoDisponible'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluacionRequest $request)
    {
        //abort_unless(Auth::user()->can('evaluaciones.store'), 403);

        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // ✅ Verificar que la suma de pesos no exceda 100%
            $pesoActual = Evaluacion::where('curso_id', $validated['curso_id'])
                ->where('activo', true)
                ->sum('peso_porcentaje');

            $pesoDisponible = 100 - $pesoActual;

            if ($validated['peso_porcentaje'] > $pesoDisponible) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "❌ El peso excede el disponible. Solo quedan {$pesoDisponible}% disponibles.");
            }

            // 📝 Crear evaluación
          // 📝 Crear evaluación
$evaluacion = Evaluacion::create([
    'curso_id' => $validated['curso_id'],
    'nombre' => $validated['nombre'],
    'descripcion' => $validated['descripcion'] ?? null,
    'tipo' => $validated['tipo'],
    'peso_porcentaje' => $validated['peso_porcentaje'],
    'fecha_evaluacion' => $validated['fecha_evaluacion'] ?? null,
    
    // ✅ NUEVOS CAMPOS
    'fecha_disponible' => $validated['fecha_disponible'],
    'fecha_limite' => $validated['fecha_limite'],
    'duracion_minutos' => $validated['duracion_minutos'],
    'numero_intentos_permitidos' => $validated['numero_intentos_permitidos'],
    'mostrar_respuestas_correctas' => $request->has('mostrar_respuestas_correctas'),
    'aleatorizar_preguntas' => $request->has('aleatorizar_preguntas'),
    
    'nota_maxima' => $validated['nota_maxima'] ?? 20,
    'nota_minima_aprobacion' => $validated['nota_minima_aprobacion'] ?? 10.5,
    'criterios_evaluacion' => $validated['criterios_evaluacion'] ?? null,
    'activo' => true,
]);

            // 📧 Notificar a estudiantes inscritos
$curso = Curso::with('inscripciones.estudiante')->find($validated['curso_id']);

/* TEMPORALMENTE COMENTADO - NOTIFICACIONES
foreach ($curso->inscripciones()->where('estado', 'confirmada')->get() as $inscripcion) {
    Notificacion::create([
        'user_id' => $inscripcion->estudiante->user_id,
        'tipo' => 'nueva_evaluacion',
        'titulo' => 'Nueva Evaluación Programada',
        'mensaje' => "Se ha programado una nueva evaluación '{$evaluacion->nombre}' para el curso '{$curso->nombre}'." . 
                    ($evaluacion->fecha_evaluacion ? " Fecha: " . date('d/m/Y', strtotime($evaluacion->fecha_evaluacion)) : ""),
        'leida' => false,
    ]);
}
*/

            DB::commit();

            Log::info("Evaluación creada: {$evaluacion->id} - {$evaluacion->nombre} - Curso: {$curso->nombre}");

            return redirect()->route('evaluaciones.index')
                ->with('success', "✅ Evaluación creada exitosamente. Peso: {$validated['peso_porcentaje']}%");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear evaluación: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al crear la evaluación: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluacion $evaluacion)
{
    // abort_unless(Auth::user()->can('evaluaciones.show'), 403);

    try {
        // Cargar relaciones
        $evaluacion->load([
            'curso',
            'calificaciones.inscripcion.estudiante'
        ]);

        // Verificar que tenga curso
        if (!$evaluacion->curso) {
            return redirect()->route('evaluaciones.index')
                ->with('error', '❌ Esta evaluación no tiene un curso asignado.');
        }

        // Estadísticas
        $calificaciones = $evaluacion->calificaciones;
        
        $stats = [
            'total_calificados' => $calificaciones->count(),
            'promedio' => $calificaciones->count() > 0 ? round($calificaciones->avg('nota'), 2) : 0,
            'nota_maxima_obtenida' => $calificaciones->count() > 0 ? $calificaciones->max('nota') : 0,
            'nota_minima_obtenida' => $calificaciones->count() > 0 ? $calificaciones->min('nota') : 0,
            'aprobados' => $calificaciones->where('nota', '>=', 10.5)->count(),
            'desaprobados' => $calificaciones->where('nota', '<', 10.5)->count(),
            'porcentaje_aprobacion' => $calificaciones->count() > 0 
                ? round(($calificaciones->where('nota', '>=', 10.5)->count() / $calificaciones->count()) * 100, 2)
                : 0,
        ];

        // Total de estudiantes inscritos
        $totalInscritos = $evaluacion->curso->inscripciones()
            ->where('estado', 'confirmada')
            ->count();

        $stats['pendientes'] = $totalInscritos - $stats['total_calificados'];

        return view('evaluaciones.show', compact('evaluacion', 'stats', 'totalInscritos'));

    } catch (\Exception $e) {
        \Log::error('Error en EvaluacionController@show: ' . $e->getMessage());
        return redirect()->route('evaluaciones.index')
            ->with('error', '❌ Error al cargar la evaluación: ' . $e->getMessage());
    }
}
    /**
     * Show the form for editing the specified resource.
     */
  public function edit(Evaluacion $evaluacion)
{
    abort_unless(Auth::user()->can('evaluaciones.edit'), 403);

    $cursos = Curso::whereIn('estado', ['convocatoria', 'en_curso'])
        ->orderBy('nombre')
        ->get();

    // ✅ AGREGAR ESTA LÍNEA
    $docentes = \App\Models\Docente::where('activo', true)
        ->orderBy('nombres')
        ->get();

    // 📊 Calcular peso disponible (excluyendo esta evaluación)
    $pesoUsado = Evaluacion::where('curso_id', $evaluacion->curso_id)
        ->where('activo', true)
        ->where('id', '!=', $evaluacion->id)
        ->sum('peso_porcentaje');
    
    $pesoDisponible = 100 - $pesoUsado;

    return view('evaluaciones.edit', compact('evaluacion', 'cursos', 'docentes', 'pesoDisponible'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluacionRequest $request, Evaluacion $evaluacion)
    {
        //abort_unless(Auth::user()->can('evaluaciones.update'), 403);

        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // ✅ Verificar suma de pesos (excluyendo esta evaluación)
            $pesoActual = Evaluacion::where('curso_id', $evaluacion->curso_id)
                ->where('activo', true)
                ->where('id', '!=', $evaluacion->id)
                ->sum('peso_porcentaje');

            $pesoDisponible = 100 - $pesoActual;

            if ($validated['peso_porcentaje'] > $pesoDisponible) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "❌ El peso excede el disponible. Solo quedan {$pesoDisponible}% disponibles.");
            }

            // 📝 Actualizar evaluación
            $evaluacion->update($validated);

            // 📧 Notificar cambios importantes
            if ($evaluacion->wasChanged(['fecha_evaluacion', 'peso_porcentaje', 'nota_maxima'])) {
                $inscripciones = $evaluacion->curso->inscripciones()
                    ->where('estado', 'confirmada')
                    ->get();

                foreach ($inscripciones as $inscripcion) {
                    Notificacion::create([
                        'user_id' => $inscripcion->estudiante->user_id,
                        'tipo' => 'evaluacion_modificada',
                        'titulo' => 'Evaluación Modificada',
                        'mensaje' => "La evaluación '{$evaluacion->nombre}' del curso '{$evaluacion->curso->nombre}' ha sido modificada.",
                        'leida' => false,
                    ]);
                }
            }

           DB::commit();

Log::info("Evaluación actualizada: {$evaluacion->id} - {$evaluacion->nombre}");

return redirect()->route('evaluaciones.preguntas', $evaluacion->id)
    ->with('success', '✅ Evaluación actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar evaluación: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar la evaluación: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluacion $evaluacion)
    {
        abort_unless(Auth::user()->can('evaluaciones.destroy'), 403);

        DB::beginTransaction();

        try {
            // ✅ Verificar que no tenga calificaciones registradas
            if ($evaluacion->calificaciones()->count() > 0) {
                return redirect()->route('evaluaciones.index')
                    ->with('error', '❌ No se puede eliminar una evaluación con calificaciones registradas.');
            }

            $nombre = $evaluacion->nombre;
            $cursoNombre = $evaluacion->curso->nombre;

            $evaluacion->delete();

            DB::commit();

            Log::warning("Evaluación eliminada: {$nombre} - Curso: {$cursoNombre}");

            return redirect()->route('evaluaciones.index')
                ->with('success', '✅ Evaluación eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar evaluación: ' . $e->getMessage());
            
            return redirect()->route('evaluaciones.index')
                ->with('error', '❌ Error al eliminar la evaluación: ' . $e->getMessage());
        }
    }

    /**
     * 📝 Formulario para calificar evaluación
     */
    public function calificar(Evaluacion $evaluacion)
{
    abort_unless(Auth::user()->can('evaluaciones.calificar'), 403);

    try {
        $evaluacion->load(['curso', 'calificaciones']);

        // 🎓 Obtener estudiantes inscritos
        $inscripciones = $evaluacion->curso->inscripciones()
            ->where('estado', 'confirmada')
            ->with('estudiante')
            ->orderBy('estudiante_id')
            ->get();

        // 📊 Marcar cuáles ya tienen calificación
        $inscripciones = $inscripciones->map(function ($inscripcion) use ($evaluacion) {
            $calificacion = $evaluacion->calificaciones
                ->where('inscripcion_id', $inscripcion->id)
                ->first();
            
            $inscripcion->calificacion_actual = $calificacion;
            return $inscripcion;
        });

        // 📈 Contar calificaciones ya registradas
        $calificadas = $evaluacion->calificaciones()->count();

        return view('evaluaciones.calificar', compact('evaluacion', 'inscripciones', 'calificadas'));

    } catch (\Exception $e) {
        Log::error('Error en formulario de calificación: ' . $e->getMessage());
        return redirect()->route('evaluaciones.index')
            ->with('error', '❌ Error al cargar el formulario de calificación.');
    }
}
    /**
     * 💾 Guardar calificaciones masivas
     */
    public function guardarCalificaciones(Request $request, Evaluacion $evaluacion)
{
    // abort_unless(Auth::user()->can('evaluaciones.calificar'), 403);

    $validated = $request->validate([
        'inscripciones' => 'required|array|min:1',
        'inscripciones.*.inscripcion_id' => 'required|exists:inscripciones,id',
        'inscripciones.*.nota' => 'nullable|numeric|min:0|max:' . $evaluacion->nota_maxima,
        'inscripciones.*.observaciones' => 'nullable|string|max:500',
    ]);

    DB::beginTransaction();

    try {
        $totalCalificaciones = 0;

        foreach ($validated['inscripciones'] as $inscripcionId => $calData) {
            // Solo procesar si hay una nota
            if (isset($calData['nota']) && $calData['nota'] !== '' && $calData['nota'] !== null) {
                
                $calificacion = Calificacion::updateOrCreate(
                    [
                        'inscripcion_id' => $calData['inscripcion_id'],
                        'evaluacion_id' => $evaluacion->id,
                    ],
                    [
                        'nota' => $calData['nota'],
                        'fecha_registro' => now(),
                        'observaciones' => $calData['observaciones'] ?? null,
                    ]
                );

                $totalCalificaciones++;
            }
        }

        DB::commit();

        \Log::info("Calificaciones guardadas - Evaluación: {$evaluacion->id} - Total: {$totalCalificaciones}");

        return redirect()->back()
            ->with('success', "✅ {$totalCalificaciones} calificación(es) guardada(s) exitosamente.");

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al guardar calificaciones: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', '❌ Error al guardar las calificaciones: ' . $e->getMessage());
    }
}


    /**
     * 🔄 Cambiar estado de la evaluación
     */
    public function toggleStatus(Evaluacion $evaluacion)
    {
        abort_unless(Auth::user()->can('evaluaciones.edit'), 403);

        try {
            $evaluacion->update(['activo' => !$evaluacion->activo]);

            $estado = $evaluacion->activo ? 'activada' : 'desactivada';
            
            Log::info("Evaluación {$estado}: {$evaluacion->id}");

            return redirect()->back()
                ->with('success', "✅ Evaluación {$estado} exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de evaluación: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al cambiar el estado de la evaluación.');
        }
    }

    /**
     * 📊 Obtener peso disponible para un curso (AJAX)
     */
    public function pesoDisponible(Request $request)
    {
        abort_unless(Auth::user()->can('evaluaciones.index'), 403);

        try {
            $cursoId = $request->get('curso_id');
            $evaluacionId = $request->get('evaluacion_id'); // Para edición

            if (!$cursoId) {
                return response()->json(['error' => 'Curso no especificado'], 400);
            }

            $query = Evaluacion::where('curso_id', $cursoId)
                ->where('activo', true);

            if ($evaluacionId) {
                $query->where('id', '!=', $evaluacionId);
            }

            $pesoUsado = $query->sum('peso_porcentaje');
            $pesoDisponible = 100 - $pesoUsado;

            return response()->json([
                'peso_usado' => $pesoUsado,
                'peso_disponible' => $pesoDisponible,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener peso disponible: ' . $e->getMessage());
            return response()->json(['error' => 'Error al calcular peso'], 500);
        }
    }

    /**
     * 📈 Estadísticas de evaluaciones por curso
     */
    public function estadisticasCurso($curso_id)
    {
        abort_unless(Auth::user()->can('reportes.academicos'), 403);

        try {
            $curso = Curso::findOrFail($curso_id);
            
            $evaluaciones = Evaluacion::where('curso_id', $curso_id)
                ->with('calificaciones')
                ->get();

            $stats = $evaluaciones->map(function ($evaluacion) {
                $calificaciones = $evaluacion->calificaciones;
                
                return [
                    'evaluacion' => $evaluacion,
                    'total_calificados' => $calificaciones->count(),
                    'promedio' => round($calificaciones->avg('nota'), 2),
                    'aprobados' => $calificaciones->where('nota', '>=', $evaluacion->nota_minima_aprobacion)->count(),
                    'desaprobados' => $calificaciones->where('nota', '<', $evaluacion->nota_minima_aprobacion)->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas de curso: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
            ], 500);
        }
    }

    /**
 * 📝 Mostrar interfaz para gestionar preguntas de una evaluación
 */
public function gestionarPreguntas($evaluacion_id)
{
    // abort_unless(Auth::user()->can('evaluaciones.update'), 403);
    
    $evaluacion = Evaluacion::with(['curso', 'preguntas.opciones'])
        ->findOrFail($evaluacion_id);
    
    $preguntas = $evaluacion->preguntas()->orderBy('orden')->get();
    
    return view('evaluaciones.gestionar-preguntas', compact('evaluacion', 'preguntas'));
}

/**
 * 💾 Guardar una nueva pregunta o actualizar existente
 */
/**
 * 💾 Guardar una nueva pregunta o actualizar existente
 */
public function guardarPregunta(Request $request, $evaluacion_id)
{
    // abort_unless(Auth::user()->can('evaluaciones.update'), 403);
    
    $evaluacion = Evaluacion::findOrFail($evaluacion_id);
    
    // ✅ VALIDACIÓN CORREGIDA
    $validated = $request->validate([
        'texto_pregunta' => 'required|string|min:10|max:1000',
        'tipo_pregunta' => 'required|in:multiple,verdadero_falso,corta',
        'puntos' => 'required|numeric|min:0.5|max:20',
        'orden' => 'nullable|integer|min:1',
        'opciones' => 'array|nullable',
        'opciones.*.texto' => 'required_with:opciones|string|max:500',
        'correcta' => 'nullable|integer',
        'respuesta_corta' => 'nullable|string|max:500',
    ]);
    
    try {
        DB::beginTransaction();
        
        // Determinar el orden automáticamente si no se especifica
        $ultimoOrden = $evaluacion->preguntas()->max('orden') ?? 0;
        $ultimoNumero = $evaluacion->preguntas()->max('numero_pregunta') ?? 0;
        
        // Crear nueva pregunta
        $pregunta = PreguntaEvaluacion::create([
            'evaluacion_id' => $evaluacion_id,
            'numero_pregunta' => $ultimoNumero + 1,
            'enunciado' => $validated['texto_pregunta'],
            'tipo_pregunta' => $validated['tipo_pregunta'],
            'puntos' => $validated['puntos'],
            'orden' => $validated['orden'] ?? ($ultimoOrden + 1),
            'obligatoria' => true,
        ]);
        
        // Guardar opciones para múltiple opción o verdadero/falso
        if (in_array($validated['tipo_pregunta'], ['multiple', 'verdadero_falso']) && isset($validated['opciones'])) {
            foreach ($validated['opciones'] as $index => $opcion) {
                OpcionPregunta::create([
                    'pregunta_id' => $pregunta->id,
                    'texto_opcion' => $opcion['texto'],
                    'es_correcta' => ($request->correcta == $index),
                    'orden' => $index + 1,
                ]);
            }
        }
        
        // Guardar respuesta correcta para pregunta corta
        if ($validated['tipo_pregunta'] == 'corta' && isset($validated['respuesta_corta'])) {
            $pregunta->update([
                'respuesta_correcta' => strtolower(trim($validated['respuesta_corta']))
            ]);
        }
        
        DB::commit();
        
        Log::info("✅ Pregunta guardada - Evaluación: {$evaluacion_id} - Pregunta: {$pregunta->id}");
        
        return redirect()
            ->route('evaluaciones.preguntas', $evaluacion_id)
            ->with('success', '✅ Pregunta creada exitosamente');
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ Error al guardar pregunta: ' . $e->getMessage());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return redirect()
            ->back()
            ->with('error', '❌ Error al guardar la pregunta: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * ✏️ Mostrar formulario para editar pregunta (AJAX)
 */
public function editarPregunta($pregunta_id)
{
    // abort_unless(Auth::user()->can('evaluaciones.update'), 403);
    
    $pregunta = PreguntaEvaluacion::with(['evaluacion.curso', 'opciones'])
        ->findOrFail($pregunta_id);
    
    return response()->json([
        'success' => true,
        'pregunta' => $pregunta
    ]);
}

/**
 * 🔄 Actualizar pregunta existente
 */
public function actualizarPregunta(Request $request, $pregunta_id)
{
    // abort_unless(Auth::user()->can('evaluaciones.update'), 403);
    
    $pregunta = PreguntaEvaluacion::findOrFail($pregunta_id);
    
    // Reutilizar validación y lógica de guardarPregunta
    $request->merge(['pregunta_id' => $pregunta_id]);
    return $this->guardarPregunta($request, $pregunta->evaluacion_id);
}

/**
 * 🗑️ Eliminar pregunta
 */
public function eliminarPregunta($pregunta_id)
{
    // abort_unless(Auth::user()->can('evaluaciones.update'), 403);
    
    $pregunta = PreguntaEvaluacion::findOrFail($pregunta_id);
    $evaluacion_id = $pregunta->evaluacion_id;
    
    // Verificar que no haya respuestas de estudiantes
    $tieneRespuestas = RespuestaEvaluacion::where('pregunta_id', $pregunta_id)->exists();
    
    if ($tieneRespuestas) {
        return redirect()
            ->back()
            ->with('error', 'No se puede eliminar esta pregunta porque ya tiene respuestas de estudiantes');
    }
    
    try {
        DB::beginTransaction();
        
        // Eliminar opciones
        $pregunta->opciones()->delete();
        
        // Eliminar pregunta
        $pregunta->delete();
        
        DB::commit();
        
        Log::info("Pregunta eliminada - ID: {$pregunta_id}");
        
        return redirect()
            ->route('evaluaciones.preguntas', $evaluacion_id)
            ->with('success', 'Pregunta eliminada exitosamente');
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al eliminar pregunta: ' . $e->getMessage());
        return redirect()
            ->back()
            ->with('error', 'Error al eliminar la pregunta: ' . $e->getMessage());
    }
}

/**
 * 👁️ Previsualizar evaluación completa
 */
public function preview($evaluacion_id)
{
    // abort_unless(Auth::user()->can('evaluaciones.view'), 403);
    
    $evaluacion = Evaluacion::with(['curso', 'preguntas.opciones'])
        ->findOrFail($evaluacion_id);
    
    $preguntas = $evaluacion->preguntas()
        ->orderBy('orden')
        ->with('opciones')
        ->get();
    
    $puntajeTotal = $preguntas->sum('puntos');
    
    return view('evaluaciones.preview', compact('evaluacion', 'preguntas', 'puntajeTotal'));
}

/**
 * 🔀 Reordenar preguntas (AJAX)
 */
public function reordenarPreguntas(Request $request, $evaluacion_id)
{
    // abort_unless(Auth::user()->can('evaluaciones.update'), 403);
    
    $evaluacion = Evaluacion::findOrFail($evaluacion_id);
    
    $validated = $request->validate([
        'orden' => 'required|array',
        'orden.*' => 'required|integer|exists:preguntas_evaluacion,id'
    ]);
    
    try {
        DB::beginTransaction();
        
        foreach ($validated['orden'] as $index => $pregunta_id) {
            PreguntaEvaluacion::where('id', $pregunta_id)
                ->update(['orden' => $index + 1]);
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Orden actualizado exitosamente'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al reordenar preguntas: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al reordenar: ' . $e->getMessage()
        ], 500);
    }
}
}