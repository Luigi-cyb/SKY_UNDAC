<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Docente;  // ← AGREGAR ESTA LÍNEA
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AsistenciaController extends Controller
{
    // ... resto del código
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
    
    // ✅ Query base: solo asistencias de los cursos del docente
    $query = \App\Models\Asistencia::with(['inscripcion.estudiante', 'curso'])
        ->whereIn('curso_id', $cursos->pluck('id'));
    
    // Filtros
    if ($request->filled('curso_id')) {
        $query->where('curso_id', $request->curso_id);
    }
    
    if ($request->filled('fecha')) {
        $query->whereDate('fecha_sesion', $request->fecha);
    }
    
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }
    
    $asistencias = $query->orderBy('fecha_sesion', 'desc')->paginate(15);
    
    // Estadísticas solo de los cursos del docente
    $totalRegistros = \App\Models\Asistencia::whereIn('curso_id', $cursos->pluck('id'))->count();
    $presentes = \App\Models\Asistencia::whereIn('curso_id', $cursos->pluck('id'))
        ->where('estado', 'presente')->count();
    $ausentes = \App\Models\Asistencia::whereIn('curso_id', $cursos->pluck('id'))
        ->where('estado', 'ausente')->count();
    $tardanzas = \App\Models\Asistencia::whereIn('curso_id', $cursos->pluck('id'))
        ->where('estado', 'tardanza')->count();
    
    return view('asistencias.index', compact(
        'asistencias',
        'cursos',
        'totalRegistros',
        'presentes',
        'ausentes',
        'tardanzas'
    ));
}

    public function create(Request $request)
{
    // abort_unless(Auth::user()->can('asistencias.create'), 403);

    try {
        // Obtener cursos del docente
        $docente = Docente::where('correo_institucional', Auth::user()->email)->first();
        
        $cursos = Curso::whereHas('asignacionesDocentes', function($query) use ($docente) {
            $query->where('docente_id', $docente->id ?? 0);
        })->whereIn('estado', ['en_curso', 'convocatoria'])->get();

        $curso_id = $request->curso_id;
        
        if (!$curso_id) {
            return redirect()->route('asistencias.index')
                ->with('error', '❌ Debe seleccionar un curso.');
        }

        $curso = Curso::with(['modalidad', 'categoria'])->findOrFail($curso_id);

        // 🎓 Obtener inscripciones confirmadas
        $inscripciones = $curso->inscripciones()
            ->where('estado', 'confirmada')
            ->with('estudiante')
            ->orderBy('estudiante_id')
            ->get();

        if ($inscripciones->isEmpty()) {
            return redirect()->route('asistencias.index')
                ->with('warning', '⚠️ El curso no tiene estudiantes inscritos.');
        }

        // 📊 Obtener última sesión registrada
        $ultimaSesion = Asistencia::where('curso_id', $curso_id)
            ->max('numero_sesion') ?? 0;

        $numeroSesion = $ultimaSesion + 1;

        return view('asistencias.create', compact('cursos', 'curso', 'inscripciones', 'numeroSesion'));

    } catch (\Exception $e) {
        Log::error('Error en AsistenciaController@create: ' . $e->getMessage());
        return redirect()->route('asistencias.index')
            ->with('error', '❌ Error al cargar el formulario de asistencia.');
    }
}

    public function store(Request $request)
{
    // abort_unless(Auth::user()->can('asistencias.store'), 403);

    $validated = $request->validate([
        'curso_id' => 'required|exists:cursos,id',
        'numero_sesion' => 'required|integer|min:1',
        'fecha_sesion' => 'required|date|before_or_equal:today',
        'tema_sesion' => 'nullable|string|max:500',
        'inscripcion_ids' => 'required|array|min:1',
        'inscripcion_ids.*' => 'required|exists:inscripciones,id',
        'estados' => 'required|array',
        'estados.*' => 'required|in:presente,ausente,tardanza,justificado',
        'horas_registro' => 'nullable|array',
        'horas_registro.*' => 'nullable',
        'observaciones' => 'nullable|string|max:1000',
    ]);

    DB::beginTransaction();

    try {
        $curso = Curso::findOrFail($validated['curso_id']);

        // Verificar que la sesión no exista ya
        $existe = Asistencia::where('curso_id', $validated['curso_id'])
            ->where('numero_sesion', $validated['numero_sesion'])
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Ya existe un registro de asistencia para esta sesión.');
        }

        $totalRegistrados = 0;
        $totalPresentes = 0;
        $totalAusentes = 0;

        // Registrar asistencias
        foreach ($validated['inscripcion_ids'] as $index => $inscripcionId) {
            $horaRegistro = $validated['horas_registro'][$index] ?? now()->format('H:i:s');
            
            Asistencia::create([
                'inscripcion_id' => $inscripcionId,
                'curso_id' => $validated['curso_id'],
                'numero_sesion' => $validated['numero_sesion'],
                'fecha_sesion' => $validated['fecha_sesion'],
                'hora_registro' => $horaRegistro,
                'estado' => $validated['estados'][$index],
                'tema_sesion' => $validated['tema_sesion'] ?? null,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            $totalRegistrados++;

            if ($validated['estados'][$index] === 'presente') {
                $totalPresentes++;
            } elseif ($validated['estados'][$index] === 'ausente') {
                $totalAusentes++;
            }
        }

        DB::commit();

        Log::info("Asistencia registrada - Curso: {$curso->id} - Sesión: {$validated['numero_sesion']} - Total: {$totalRegistrados}");

        $porcentajeAsistencia = $totalRegistrados > 0 ? round(($totalPresentes / $totalRegistrados) * 100, 2) : 0;

        return redirect()->route('asistencias.index')
            ->with('success', "✅ Asistencia registrada exitosamente. Sesión {$validated['numero_sesion']}: {$totalPresentes} presentes ({$porcentajeAsistencia}%), {$totalAusentes} ausentes.");

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al registrar asistencia: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', '❌ Error al registrar la asistencia: ' . $e->getMessage());
    }
}
    /**
     * Display the specified resource.
     */
    public function show($curso_id, $numero_sesion)
    {
        abort_unless(Auth::user()->can('asistencias.show'), 403);

        try {
            $curso = Curso::with(['modalidad', 'categoria'])->findOrFail($curso_id);
            
            $asistencias = Asistencia::where('curso_id', $curso_id)
                ->where('numero_sesion', $numero_sesion)
                ->with('inscripcion.estudiante')
                ->orderBy('inscripcion_id')
                ->get();

            if ($asistencias->isEmpty()) {
                return redirect()->route('asistencias.index')
                    ->with('warning', '⚠️ No hay registros de asistencia para esta sesión.');
            }

            // 📊 Estadísticas de la sesión
            $stats = [
                'total' => $asistencias->count(),
                'presente' => $asistencias->where('estado', 'presente')->count(),
                'ausente' => $asistencias->where('estado', 'ausente')->count(),
                'tardanza' => $asistencias->where('estado', 'tardanza')->count(),
                'justificado' => $asistencias->where('estado', 'justificado')->count(),
                'porcentaje_asistencia' => round(
                    ($asistencias->where('estado', 'presente')->count() / $asistencias->count()) * 100,
                    2
                ),
            ];

            // 📅 Información de la sesión
            $fechaSesion = $asistencias->first()->fecha_sesion;

            return view('asistencias.show', compact('curso', 'asistencias', 'numero_sesion', 'stats', 'fechaSesion'));

        } catch (\Exception $e) {
            Log::error('Error en AsistenciaController@show: ' . $e->getMessage());
            return redirect()->route('asistencias.index')
                ->with('error', '❌ Error al cargar la asistencia.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($curso_id, $numero_sesion)
    {
        abort_unless(Auth::user()->can('asistencias.edit'), 403);

        try {
            $curso = Curso::with(['modalidad', 'categoria'])->findOrFail($curso_id);
            
            $asistencias = Asistencia::where('curso_id', $curso_id)
                ->where('numero_sesion', $numero_sesion)
                ->with('inscripcion.estudiante')
                ->orderBy('inscripcion_id')
                ->get();

            if ($asistencias->isEmpty()) {
                return redirect()->route('asistencias.index')
                    ->with('error', '❌ No hay registros de asistencia para editar.');
            }

            $fechaSesion = $asistencias->first()->fecha_sesion;

            return view('asistencias.edit', compact('curso', 'asistencias', 'numero_sesion', 'fechaSesion'));

        } catch (\Exception $e) {
            Log::error('Error en AsistenciaController@edit: ' . $e->getMessage());
            return redirect()->route('asistencias.index')
                ->with('error', '❌ Error al cargar el formulario de edición.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $curso_id, $numero_sesion)
    {
        abort_unless(Auth::user()->can('asistencias.update'), 403);

        $validated = $request->validate([
            'fecha_sesion' => 'required|date|before_or_equal:today',
            'asistencias' => 'required|array|min:1',
            'asistencias.*.id' => 'required|exists:asistencias,id',
            'asistencias.*.estado' => 'required|in:presente,ausente,tardanza,justificado',
            'asistencias.*.observaciones' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $totalActualizados = 0;
            $cambiosRealizados = [];

            foreach ($validated['asistencias'] as $asistenciaData) {
                $asistencia = Asistencia::findOrFail($asistenciaData['id']);
                
                // 📝 Verificar si hubo cambios
                $estadoAnterior = $asistencia->estado;
                
                $asistencia->update([
                    'estado' => $asistenciaData['estado'],
                    'observaciones' => $asistenciaData['observaciones'] ?? null,
                    'fecha_sesion' => $validated['fecha_sesion'],
                ]);

                $totalActualizados++;

                // 📊 Registrar cambio para log
                if ($estadoAnterior !== $asistenciaData['estado']) {
                    $cambiosRealizados[] = [
                        'inscripcion_id' => $asistencia->inscripcion_id,
                        'antes' => $estadoAnterior,
                        'despues' => $asistenciaData['estado'],
                    ];
                }
            }

            DB::commit();

            Log::info("Asistencia actualizada - Curso: {$curso_id} - Sesión: {$numero_sesion} - Cambios: " . count($cambiosRealizados));

            return redirect()->route('asistencias.show', [$curso_id, $numero_sesion])
                ->with('success', "✅ Asistencia actualizada exitosamente. {$totalActualizados} registro(s) modificado(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar asistencia: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar la asistencia: ' . $e->getMessage());
        }
    }
/**
 * 📊 Reporte de asistencia por curso
 */
public function reporte(Request $request)
{
    // abort_unless(Auth::user()->can('asistencias.index'), 403);

    try {
        // Obtener cursos del docente
        $docente = Docente::where('correo_institucional', Auth::user()->email)->first();
        
        $cursos = Curso::whereHas('asignacionesDocentes', function($query) use ($docente) {
            $query->where('docente_id', $docente->id ?? 0);
        })->whereIn('estado', ['en_curso', 'convocatoria'])->get();

        $cursoSeleccionado = null;
        $totalSesiones = 0;
        $totalEstudiantes = 0;
        $porcentajeAsistenciaPromedio = 0;
        $estudiantesEnRiesgo = 0;
        $totalTardanzas = 0;
        $reporteDetallado = [];
        $datosSesiones = ['sesiones' => [], 'presentes' => [], 'ausentes' => [], 'tardanzas' => []];

        if ($request->has('curso_id')) {
            $curso_id = $request->get('curso_id');
            $cursoSeleccionado = Curso::with(['modalidad', 'categoria'])->findOrFail($curso_id);
            
            // Obtener inscripciones confirmadas
            $inscripciones = $cursoSeleccionado->inscripciones()
                ->where('estado', 'confirmada')
                ->with(['estudiante', 'asistencias'])
                ->get();
            
            $totalEstudiantes = $inscripciones->count();
            
            // Obtener total de sesiones
            $totalSesiones = Asistencia::where('curso_id', $curso_id)
                ->max('numero_sesion') ?? 0;
            
            // Calcular estadísticas
            $totalAsistencias = Asistencia::where('curso_id', $curso_id)->count();
            $totalPresentes = Asistencia::where('curso_id', $curso_id)->where('estado', 'presente')->count();
            $totalTardanzas = Asistencia::where('curso_id', $curso_id)->where('estado', 'tardanza')->count();
            
            $porcentajeAsistenciaPromedio = $totalAsistencias > 0 
                ? ($totalPresentes / $totalAsistencias) * 100 
                : 0;
            
            // Reporte detallado por estudiante
            foreach ($inscripciones as $inscripcion) {
                $asistencias = Asistencia::where('inscripcion_id', $inscripcion->id)->get();
                
                $sesiones = [];
                foreach ($asistencias as $asistencia) {
                    $sesiones[$asistencia->numero_sesion] = $asistencia->estado;
                }
                
                $presentes = $asistencias->where('estado', 'presente')->count();
                $ausentes = $asistencias->where('estado', 'ausente')->count();
                $tardanzas = $asistencias->where('estado', 'tardanza')->count();
                $total = $asistencias->count();
                
                $porcentaje = $total > 0 ? ($presentes / $total) * 100 : 0;
                
                if ($porcentaje < 70) {
                    $estudiantesEnRiesgo++;
                }
                
                $reporteDetallado[] = [
                    'estudiante' => $inscripcion->estudiante,
                    'sesiones' => $sesiones,
                    'presentes' => $presentes,
                    'ausentes' => $ausentes,
                    'tardanzas' => $tardanzas,
                    'porcentaje' => $porcentaje,
                ];
            }
            
            // Datos para gráfico
            for ($i = 1; $i <= $totalSesiones; $i++) {
                $datosSesiones['sesiones'][] = "Sesión $i";
                $datosSesiones['presentes'][] = Asistencia::where('curso_id', $curso_id)
                    ->where('numero_sesion', $i)
                    ->where('estado', 'presente')
                    ->count();
                $datosSesiones['ausentes'][] = Asistencia::where('curso_id', $curso_id)
                    ->where('numero_sesion', $i)
                    ->where('estado', 'ausente')
                    ->count();
                $datosSesiones['tardanzas'][] = Asistencia::where('curso_id', $curso_id)
                    ->where('numero_sesion', $i)
                    ->where('estado', 'tardanza')
                    ->count();
            }
        }

        return view('asistencias.reporte-curso', compact(
            'cursos',
            'cursoSeleccionado',
            'totalSesiones',
            'totalEstudiantes',
            'porcentajeAsistenciaPromedio',
            'estudiantesEnRiesgo',
            'totalTardanzas',
            'reporteDetallado',
            'datosSesiones'
        ));
        
    } catch (\Exception $e) {
        Log::error('Error en reporte de asistencias: ' . $e->getMessage());
        return redirect()->route('asistencias.index')
            ->with('error', '❌ Error al generar el reporte: ' . $e->getMessage());
    }
}   
    /**
     * 📥 Exportar reporte de asistencia a Excel/PDF
     */
    public function exportar($curso_id, $formato = 'pdf')
    {
        abort_unless(Auth::user()->can('reportes.exportar'), 403);

        try {
            $curso = Curso::findOrFail($curso_id);
            
            // TODO: Implementar exportación con Maatwebsite/Excel o DomPDF
            
            return redirect()->back()
                ->with('info', '⚠️ Funcionalidad de exportación en desarrollo.');

        } catch (\Exception $e) {
            Log::error('Error al exportar asistencia: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al exportar el reporte.');
        }
    }

    /**
     * 📊 Obtener estadísticas de asistencia de un estudiante
     */
    public function estadisticasEstudiante($inscripcion_id)
    {
        abort_unless(Auth::user()->can('asistencias.show'), 403);

        try {
            $inscripcion = Inscripcion::with(['estudiante', 'curso', 'asistencias'])
                ->findOrFail($inscripcion_id);

            $asistencias = $inscripcion->asistencias;

            $stats = [
                'total_sesiones' => $asistencias->count(),
                'presentes' => $asistencias->where('estado', 'presente')->count(),
                'ausentes' => $asistencias->where('estado', 'ausente')->count(),
                'tardanzas' => $asistencias->where('estado', 'tardanza')->count(),
                'justificados' => $asistencias->where('estado', 'justificado')->count(),
                'porcentaje_asistencia' => $asistencias->count() > 0
                    ? round(($asistencias->where('estado', 'presente')->count() / $asistencias->count()) * 100, 2)
                    : 0,
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas de estudiante: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
            ], 500);
        }
    }

    /**
     * 🔍 Buscar sesiones por curso (para AJAX)
     */
    public function buscarSesiones(Request $request)
    {
        abort_unless(Auth::user()->can('asistencias.index'), 403);

        try {
            $cursoId = $request->get('curso_id');
            
            if (!$cursoId) {
                return response()->json(['error' => 'Curso no especificado'], 400);
            }

            $sesiones = Asistencia::where('curso_id', $cursoId)
                ->select('numero_sesion', 'fecha_sesion')
                ->selectRaw('COUNT(*) as total_registros')
                ->selectRaw('SUM(CASE WHEN estado = "presente" THEN 1 ELSE 0 END) as presentes')
                ->groupBy('numero_sesion', 'fecha_sesion')
                ->orderBy('numero_sesion')
                ->get();

            return response()->json($sesiones);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de sesiones: ' . $e->getMessage());
            return response()->json(['error' => 'Error en la búsqueda'], 500);
        }
    }
}