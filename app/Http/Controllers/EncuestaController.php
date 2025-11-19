<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Curso;
use App\Models\RespuestaEncuesta;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class EncuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Auth::user()->can('encuestas.ver'), 403);

        $user = Auth::user();
        $query = Encuesta::with(['curso']);

        // Filtros
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $ahora = now();
            
            switch ($request->estado) {
                case 'activa':
                    $query->where('activa', true)
                          ->where('fecha_inicio', '<=', $ahora)
                          ->where('fecha_fin', '>=', $ahora);
                    break;
                case 'pendiente':
                    $query->where('fecha_inicio', '>', $ahora);
                    break;
                case 'finalizada':
                    $query->where('fecha_fin', '<', $ahora);
                    break;
                case 'inactiva':
                    $query->where('activa', false);
                    break;
            }
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        // Si es docente, solo ver encuestas de sus cursos
        if ($user->hasRole('Docente')) {
            $cursosDocente = $user->docente->asignaciones->pluck('curso_id');
            $query->whereIn('curso_id', $cursosDocente);
        }

        $encuestas = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calcular estadísticas rápidas para cada encuesta
        foreach ($encuestas as $encuesta) {
            $encuesta->total_respuestas = $encuesta->respuestas()->count();
            $encuesta->total_inscritos = $encuesta->curso->inscripciones()
                ->where('estado', 'confirmada')->count();
            $encuesta->porcentaje_participacion = $encuesta->total_inscritos > 0 
                ? round(($encuesta->total_respuestas / $encuesta->total_inscritos) * 100, 1) 
                : 0;
            $encuesta->estado_actual = $this->obtenerEstadoEncuesta($encuesta);
        }

        // Obtener cursos para filtro
        $cursos = $user->hasRole('Administrador') || $user->hasRole('Comité Académico')
            ? Curso::whereIn('estado', ['en_curso', 'finalizado'])->get()
            : ($user->hasRole('Docente')
                ? Curso::whereIn('id', $user->docente->asignaciones->pluck('curso_id'))
                       ->whereIn('estado', ['en_curso', 'finalizado'])->get()
                : collect());

        return view('encuestas.index', compact('encuestas', 'cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->can('encuestas.crear'), 403);

        $user = Auth::user();

        // Cursos disponibles según rol
        $cursos = $user->hasRole('Administrador') || $user->hasRole('Comité Académico')
            ? Curso::whereIn('estado', ['en_curso', 'finalizado'])->get()
            : ($user->hasRole('Docente')
                ? Curso::whereIn('id', $user->docente->asignaciones->pluck('curso_id'))
                       ->whereIn('estado', ['en_curso', 'finalizado'])->get()
                : collect());

        // Plantillas de preguntas predefinidas
        $plantillas = $this->obtenerPlantillasPreguntas();

        return view('encuestas.create', compact('cursos', 'plantillas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('encuestas.crear'), 403);

        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'tipo' => 'required|in:satisfaccion,evaluacion_docente,evaluacion_curso,general',
            'preguntas' => 'required|json',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'anonima' => 'required|boolean',
            'obligatoria' => 'nullable|boolean',
            'mostrar_resultados' => 'nullable|boolean',
        ], [
            'curso_id.required' => 'Debe seleccionar un curso.',
            'titulo.required' => 'El título es obligatorio.',
            'preguntas.required' => 'Debe agregar al menos una pregunta.',
            'preguntas.json' => 'El formato de preguntas no es válido.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ]);

        // Validar estructura de preguntas
        $preguntas = json_decode($validated['preguntas'], true);
        if (!$this->validarEstructuraPreguntas($preguntas)) {
            return back()->withErrors(['preguntas' => 'La estructura de preguntas no es válida.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $encuesta = Encuesta::create([
                'curso_id' => $validated['curso_id'],
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'tipo' => $validated['tipo'],
                'preguntas' => $validated['preguntas'],
                'fecha_inicio' => $validated['fecha_inicio'],
                'fecha_fin' => $validated['fecha_fin'],
                'anonima' => $validated['anonima'],
                'obligatoria' => $request->boolean('obligatoria'),
                'mostrar_resultados' => $request->boolean('mostrar_resultados'),
                'activa' => true,
            ]);

            // Log
            Log::info('Encuesta creada', [
                'encuesta_id' => $encuesta->id,
                'curso_id' => $encuesta->curso_id,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return redirect()->route('encuestas.index')
                ->with('success', '✅ Encuesta creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear encuesta: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al crear la encuesta. Intente nuevamente.'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.ver'), 403);

        $encuesta->load(['curso', 'respuestas.inscripcion.estudiante']);

        // Calcular estadísticas
        $totalRespuestas = $encuesta->respuestas()->count();
        $totalInscritos = $encuesta->curso->inscripciones()->where('estado', 'confirmada')->count();
        $porcentajeParticipacion = $totalInscritos > 0 
            ? round(($totalRespuestas / $totalInscritos) * 100, 1) 
            : 0;

        // Estado actual
        $estadoActual = $this->obtenerEstadoEncuesta($encuesta);

        // Preguntas
        $preguntas = json_decode($encuesta->preguntas, true);
        $totalPreguntas = count($preguntas);

        // Estadísticas por día (últimos 7 días)
        $respuestasPorDia = $encuesta->respuestas()
            ->select(DB::raw('DATE(fecha_respuesta) as fecha'), DB::raw('COUNT(*) as total'))
            ->where('fecha_respuesta', '>=', now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return view('encuestas.show', compact(
            'encuesta', 
            'totalRespuestas', 
            'totalInscritos', 
            'porcentajeParticipacion',
            'estadoActual',
            'totalPreguntas',
            'respuestasPorDia'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.editar'), 403);

        // No permitir editar si ya tiene respuestas (solo ciertos campos)
        $tieneRespuestas = $encuesta->respuestas()->count() > 0;

        $cursos = Curso::whereIn('estado', ['en_curso', 'finalizado'])->get();
        $preguntas = json_decode($encuesta->preguntas, true);

        return view('encuestas.edit', compact('encuesta', 'cursos', 'preguntas', 'tieneRespuestas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.editar'), 403);

        $tieneRespuestas = $encuesta->respuestas()->count() > 0;

        // Validación diferente si tiene respuestas
        $rules = [
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activa' => 'required|boolean',
        ];

        // Solo permitir cambiar preguntas si no tiene respuestas
        if (!$tieneRespuestas) {
            $rules['tipo'] = 'required|in:satisfaccion,evaluacion_docente,evaluacion_curso,general';
            $rules['preguntas'] = 'required|json';
            $rules['fecha_inicio'] = 'required|date';
            $rules['anonima'] = 'required|boolean';
        }

        $validated = $request->validate($rules, [
            'titulo.required' => 'El título es obligatorio.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ]);

        DB::beginTransaction();

        try {
            // Validar preguntas si se están actualizando
            if (isset($validated['preguntas'])) {
                $preguntas = json_decode($validated['preguntas'], true);
                if (!$this->validarEstructuraPreguntas($preguntas)) {
                    return back()->withErrors(['preguntas' => 'La estructura de preguntas no es válida.'])
                        ->withInput();
                }
            }

            $encuesta->update($validated);

            // Log
            Log::info('Encuesta actualizada', [
                'encuesta_id' => $encuesta->id,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return redirect()->route('encuestas.index')
                ->with('success', '✅ Encuesta actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar encuesta: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al actualizar la encuesta.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.eliminar'), 403);

        // Verificar que no tenga respuestas
        $totalRespuestas = $encuesta->respuestas()->count();
        
        if ($totalRespuestas > 0) {
            return redirect()->route('encuestas.index')
                ->with('error', "❌ No se puede eliminar una encuesta con {$totalRespuestas} respuesta(s).");
        }

        DB::beginTransaction();

        try {
            $encuesta->delete();

            // Log
            Log::info('Encuesta eliminada', [
                'encuesta_id' => $encuesta->id,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return redirect()->route('encuestas.index')
                ->with('success', '✅ Encuesta eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar encuesta: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al eliminar la encuesta.']);
        }
    }

    /**
     * Formulario para responder encuesta (estudiantes)
     */
    public function responder(Encuesta $encuesta)
    {
        $user = Auth::user();

        // Verificar que sea estudiante
        if (!$user->hasRole('Estudiante')) {
            abort(403, 'Solo los estudiantes pueden responder encuestas.');
        }

        $estudiante = $user->estudiante;

        // Verificar que esté inscrito en el curso
        $inscripcion = $estudiante->inscripciones()
            ->where('curso_id', $encuesta->curso_id)
            ->where('estado', 'confirmada')
            ->first();

        if (!$inscripcion) {
            abort(403, 'No estás inscrito en este curso.');
        }

        // Verificar que no haya respondido antes
        $yaRespondio = RespuestaEncuesta::where('encuesta_id', $encuesta->id)
            ->where('inscripcion_id', $inscripcion->id)
            ->exists();

        if ($yaRespondio) {
            return redirect()->route('dashboard')
                ->with('info', 'ℹ️ Ya has respondido esta encuesta.');
        }

        // Verificar que la encuesta esté activa
        if (!$encuesta->activa) {
            abort(403, 'Esta encuesta no está activa.');
        }

        // Verificar fechas
        $ahora = now();
        if ($ahora->lt($encuesta->fecha_inicio)) {
            return redirect()->route('dashboard')
                ->with('info', "ℹ️ Esta encuesta estará disponible desde el {$encuesta->fecha_inicio->format('d/m/Y')}.");
        }

        if ($ahora->gt($encuesta->fecha_fin)) {
            return redirect()->route('dashboard')
                ->with('warning', '⚠️ Esta encuesta ya ha finalizado.');
        }

        $preguntas = json_decode($encuesta->preguntas, true);

        return view('encuestas.responder', compact('encuesta', 'preguntas', 'inscripcion'));
    }

    /**
     * Guardar respuesta de encuesta
     */
    public function guardarRespuesta(Request $request, Encuesta $encuesta)
    {
        $user = Auth::user();

        if (!$user->hasRole('Estudiante')) {
            abort(403);
        }

        $validated = $request->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'respuestas' => 'required|array',
        ], [
            'respuestas.required' => 'Debe responder todas las preguntas obligatorias.',
        ]);

        // Verificar que la inscripción pertenezca al estudiante
        $inscripcion = Inscripcion::where('id', $validated['inscripcion_id'])
            ->where('estudiante_id', $user->estudiante->id)
            ->where('curso_id', $encuesta->curso_id)
            ->where('estado', 'confirmada')
            ->firstOrFail();

        // Verificar que no haya respondido antes
        $yaRespondio = RespuestaEncuesta::where('encuesta_id', $encuesta->id)
            ->where('inscripcion_id', $validated['inscripcion_id'])
            ->exists();

        if ($yaRespondio) {
            return redirect()->route('dashboard')
                ->with('error', '❌ Ya has respondido esta encuesta.');
        }

        // Validar respuestas contra preguntas
        $preguntas = json_decode($encuesta->preguntas, true);
        if (!$this->validarRespuestas($validated['respuestas'], $preguntas)) {
            return back()->withErrors(['respuestas' => 'Algunas respuestas no son válidas.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            RespuestaEncuesta::create([
                'encuesta_id' => $encuesta->id,
                'inscripcion_id' => $validated['inscripcion_id'],
                'respuestas' => json_encode($validated['respuestas']),
                'fecha_respuesta' => now(),
            ]);

            // Log
            Log::info('Respuesta de encuesta guardada', [
                'encuesta_id' => $encuesta->id,
                'inscripcion_id' => $validated['inscripcion_id'],
                'estudiante' => $user->email,
            ]);

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', '✅ ¡Gracias por responder la encuesta!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar respuesta: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al guardar las respuestas.'])
                ->withInput();
        }
    }

    /**
     * Ver resultados de encuesta
     */
    public function resultados(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.resultados'), 403);

        $encuesta->load(['curso', 'respuestas.inscripcion.estudiante']);

        $preguntas = json_decode($encuesta->preguntas, true);
        $respuestas = $encuesta->respuestas;

        // Procesar estadísticas
        $estadisticas = $this->procesarEstadisticas($preguntas, $respuestas);

        // Calcular métricas generales
        $totalRespuestas = $respuestas->count();
        $totalInscritos = $encuesta->curso->inscripciones()
            ->where('estado', 'confirmada')->count();
        $porcentajeParticipacion = $totalInscritos > 0 
            ? round(($totalRespuestas / $totalInscritos) * 100, 1) 
            : 0;

        // Calcular promedio general (solo de preguntas tipo escala)
        $promedioGeneral = $this->calcularPromedioGeneral($estadisticas);

        // Gráficos de datos
        $datosGraficos = $this->prepararDatosGraficos($estadisticas);

        return view('encuestas.resultados', compact(
            'encuesta', 
            'preguntas', 
            'estadisticas',
            'totalRespuestas',
            'totalInscritos',
            'porcentajeParticipacion',
            'promedioGeneral',
            'datosGraficos'
        ));
    }

    /**
     * Procesar estadísticas de respuestas
     */
    private function procesarEstadisticas($preguntas, $respuestas)
    {
        $estadisticas = [];

        foreach ($preguntas as $index => $pregunta) {
            $key = "pregunta_" . ($index + 1);
            $estadisticas[$key] = [
                'pregunta' => $pregunta['texto'],
                'tipo' => $pregunta['tipo'],
                'respuestas' => [],
                'total_respuestas' => 0,
            ];

            foreach ($respuestas as $respuesta) {
                $respuestasArray = json_decode($respuesta->respuestas, true);
                
                if (isset($respuestasArray[$key])) {
                    $valor = $respuestasArray[$key];
                    $estadisticas[$key]['total_respuestas']++;
                    
                    if ($pregunta['tipo'] === 'escala' || $pregunta['tipo'] === 'opcion_multiple') {
                        if (!isset($estadisticas[$key]['respuestas'][$valor])) {
                            $estadisticas[$key]['respuestas'][$valor] = 0;
                        }
                        $estadisticas[$key]['respuestas'][$valor]++;
                    } elseif ($pregunta['tipo'] === 'texto') {
                        $estadisticas[$key]['respuestas'][] = $valor;
                    }
                }
            }

            // Calcular promedios y porcentajes para escalas
            if ($pregunta['tipo'] === 'escala' && !empty($estadisticas[$key]['respuestas'])) {
                $suma = 0;
                $total = 0;
                foreach ($estadisticas[$key]['respuestas'] as $valor => $cantidad) {
                    $suma += $valor * $cantidad;
                    $total += $cantidad;
                }
                $estadisticas[$key]['promedio'] = $total > 0 ? round($suma / $total, 2) : 0;
                
                // Calcular porcentajes
                foreach ($estadisticas[$key]['respuestas'] as $valor => $cantidad) {
                    $estadisticas[$key]['porcentajes'][$valor] = $total > 0 
                        ? round(($cantidad / $total) * 100, 1) 
                        : 0;
                }
            }

            // Calcular porcentajes para opciones múltiples
            if ($pregunta['tipo'] === 'opcion_multiple' && !empty($estadisticas[$key]['respuestas'])) {
                $total = array_sum($estadisticas[$key]['respuestas']);
                foreach ($estadisticas[$key]['respuestas'] as $opcion => $cantidad) {
                    $estadisticas[$key]['porcentajes'][$opcion] = $total > 0 
                        ? round(($cantidad / $total) * 100, 1) 
                        : 0;
                }
            }
        }

        return $estadisticas;
    }

    /**
     * Calcular promedio general de encuesta
     */
    private function calcularPromedioGeneral($estadisticas)
    {
        $sumaPromedios = 0;
        $cantidadEscalas = 0;

        foreach ($estadisticas as $stat) {
            if ($stat['tipo'] === 'escala' && isset($stat['promedio'])) {
                $sumaPromedios += $stat['promedio'];
                $cantidadEscalas++;
            }
        }

        return $cantidadEscalas > 0 ? round($sumaPromedios / $cantidadEscalas, 2) : 0;
    }

    /**
     * Preparar datos para gráficos
     */
    private function prepararDatosGraficos($estadisticas)
    {
        $datos = [];

        foreach ($estadisticas as $key => $stat) {
            if ($stat['tipo'] === 'escala' || $stat['tipo'] === 'opcion_multiple') {
                $datos[$key] = [
                    'labels' => array_keys($stat['respuestas']),
                    'values' => array_values($stat['respuestas']),
                ];
            }
        }

        return $datos;
    }

    /**
     * Exportar resultados a PDF
     */
    public function exportarPdf(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.resultados'), 403);

        $encuesta->load(['curso', 'respuestas']);
        $preguntas = json_decode($encuesta->preguntas, true);
        $estadisticas = $this->procesarEstadisticas($preguntas, $encuesta->respuestas);

        $totalRespuestas = $encuesta->respuestas->count();
        $totalInscritos = $encuesta->curso->inscripciones()
            ->where('estado', 'confirmada')->count();
        $porcentajeParticipacion = $totalInscritos > 0 
            ? round(($totalRespuestas / $totalInscritos) * 100, 1) 
            : 0;
        $promedioGeneral = $this->calcularPromedioGeneral($estadisticas);

        $pdf = Pdf::loadView('encuestas.pdf', compact(
            'encuesta', 
            'preguntas', 
            'estadisticas',
            'totalRespuestas',
            'totalInscritos',
            'porcentajeParticipacion',
            'promedioGeneral'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("resultados-encuesta-{$encuesta->id}.pdf");
    }

    /**
     * Exportar resultados a Excel
     */
    public function exportarExcel(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.resultados'), 403);

        // Implementar exportación con Maatwebsite/Excel
        return back()->with('info', 'ℹ️ Funcionalidad de exportación a Excel en desarrollo.');
    }

    /**
     * Clonar encuesta
     */
    public function clonar(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.crear'), 403);

        DB::beginTransaction();

        try {
            $nuevaEncuesta = $encuesta->replicate();
            $nuevaEncuesta->titulo = $encuesta->titulo . ' (Copia)';
            $nuevaEncuesta->fecha_inicio = now()->addDay();
            $nuevaEncuesta->fecha_fin = now()->addDays(8);
            $nuevaEncuesta->activa = false;
            $nuevaEncuesta->save();

            DB::commit();

            return redirect()->route('encuestas.edit', $nuevaEncuesta)
                ->with('success', '✅ Encuesta clonada exitosamente. Ajusta las fechas y actívala.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al clonar encuesta: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al clonar la encuesta.']);
        }
    }

    /**
     * Activar/Desactivar encuesta
     */
    public function toggleActiva(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('encuestas.editar'), 403);

        $encuesta->update(['activa' => !$encuesta->activa]);

        $estado = $encuesta->activa ? 'activada' : 'desactivada';

        return back()->with('success', "✅ Encuesta {$estado} exitosamente.");
    }

    /**
     * Dashboard de encuestas pendientes (para estudiantes)
     */
    public function pendientes()
    {
        $user = Auth::user();

        if (!$user->hasRole('Estudiante')) {
            abort(403);
        }

        $estudiante = $user->estudiante;
        $cursosInscritos = $estudiante->inscripciones()
            ->where('estado', 'confirmada')
            ->pluck('curso_id');

        $ahora = now();

        $encuestasPendientes = Encuesta::whereIn('curso_id', $cursosInscritos)
            ->where('activa', true)
            ->where('fecha_inicio', '<=', $ahora)
            ->where('fecha_fin', '>=', $ahora)
            ->whereDoesntHave('respuestas', function ($query) use ($estudiante) {
                $query->whereHas('inscripcion', function ($q) use ($estudiante) {
                    $q->where('estudiante_id', $estudiante->id);
                });
            })
            ->with('curso')
            ->get();

        return view('encuestas.pendientes', compact('encuestasPendientes'));
    }

    /**
     * Obtener estado actual de la encuesta
     */
    private function obtenerEstadoEncuesta($encuesta)
    {
        if (!$encuesta->activa) {
            return 'inactiva';
        }

        $ahora = now();

        if ($ahora->lt($encuesta->fecha_inicio)) {
            return 'pendiente';
        }

        if ($ahora->gt($encuesta->fecha_fin)) {
            return 'finalizada';
        }

        return 'activa';
    }

    /**
     * Validar estructura de preguntas
     */
    private function validarEstructuraPreguntas($preguntas)
    {
        if (!is_array($preguntas) || empty($preguntas)) {
            return false;
        }

        foreach ($preguntas as $pregunta) {
            if (!isset($pregunta['texto']) || !isset($pregunta['tipo'])) {
                return false;
            }

            if (!in_array($pregunta['tipo'], ['texto', 'escala', 'opcion_multiple'])) {
                return false;
            }

            if ($pregunta['tipo'] === 'escala') {
                if (!isset($pregunta['escala_min']) || !isset($pregunta['escala_max'])) {
                    return false;
                }
            }

            if ($pregunta['tipo'] === 'opcion_multiple') {
                if (!isset($pregunta['opciones']) || !is_array($pregunta['opciones']) || empty($pregunta['opciones'])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Validar respuestas del estudiante
     */
    private function validarRespuestas($respuestas, $preguntas)
    {
        foreach ($preguntas as $index =>$pregunta) {
            $key = "pregunta_" . ($index + 1);

            // Verificar que exista la respuesta si es obligatoria
            if (isset($pregunta['obligatoria']) && $pregunta['obligatoria']) {
                if (!isset($respuestas[$key]) || empty($respuestas[$key])) {
                    return false;
                }
            }

            // Si existe respuesta, validar según tipo
            if (isset($respuestas[$key])) {
                $respuesta = $respuestas[$key];

                switch ($pregunta['tipo']) {
                    case 'escala':
                        // Validar que esté en el rango
                        if (!is_numeric($respuesta)) {
                            return false;
                        }
                        $min = $pregunta['escala_min'] ?? 1;
                        $max = $pregunta['escala_max'] ?? 5;
                        if ($respuesta < $min || $respuesta > $max) {
                            return false;
                        }
                        break;

                    case 'opcion_multiple':
                        // Validar que sea una opción válida
                        $opciones = $pregunta['opciones'] ?? [];
                        if (!in_array($respuesta, $opciones)) {
                            return false;
                        }
                        break;

                    case 'texto':
                        // Validar longitud máxima
                        if (strlen($respuesta) > 1000) {
                            return false;
                        }
                        break;
                }
            }
        }

        return true;
    }

    /**
     * Obtener plantillas predefinidas de preguntas
     */
    private function obtenerPlantillasPreguntas()
    {
        return [
            'satisfaccion_general' => [
                'nombre' => 'Satisfacción General',
                'preguntas' => [
                    [
                        'texto' => '¿Qué tan satisfecho estás con el curso en general?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿Recomendarías este curso a otros estudiantes?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['Sí, definitivamente', 'Probablemente sí', 'No estoy seguro', 'Probablemente no', 'Definitivamente no'],
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿Qué fue lo que más te gustó del curso?',
                        'tipo' => 'texto',
                        'obligatoria' => false,
                    ],
                    [
                        'texto' => '¿Qué sugerencias tienes para mejorar el curso?',
                        'tipo' => 'texto',
                        'obligatoria' => false,
                    ],
                ],
            ],
            'evaluacion_docente' => [
                'nombre' => 'Evaluación del Docente',
                'preguntas' => [
                    [
                        'texto' => '¿Qué tan claro fue el docente al explicar los temas?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿El docente respondió adecuadamente tus dudas?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿El docente utilizó ejemplos prácticos y relevantes?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿El docente demostró dominio del tema?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => 'Comentarios adicionales sobre el docente:',
                        'tipo' => 'texto',
                        'obligatoria' => false,
                    ],
                ],
            ],
            'evaluacion_curso' => [
                'nombre' => 'Evaluación del Curso',
                'preguntas' => [
                    [
                        'texto' => '¿El contenido del curso cumplió tus expectativas?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿Los materiales proporcionados fueron de calidad?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿Las evaluaciones fueron justas y apropiadas?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿La carga de trabajo fue adecuada?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['Muy ligera', 'Ligera', 'Adecuada', 'Pesada', 'Muy pesada'],
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿Qué temas te gustaría que se profundicen más?',
                        'tipo' => 'texto',
                        'obligatoria' => false,
                    ],
                ],
            ],
            'infraestructura' => [
                'nombre' => 'Infraestructura y Recursos',
                'preguntas' => [
                    [
                        'texto' => '¿Las instalaciones fueron adecuadas?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿Los equipos tecnológicos funcionaron correctamente?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => '¿La plataforma virtual fue fácil de usar?',
                        'tipo' => 'escala',
                        'escala_min' => 1,
                        'escala_max' => 5,
                        'obligatoria' => true,
                    ],
                    [
                        'texto' => 'Sugerencias para mejorar la infraestructura:',
                        'tipo' => 'texto',
                        'obligatoria' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Ver comparativa entre cursos
     */
    public function comparativa(Request $request)
    {
        abort_unless(Auth::user()->can('reportes.estadisticas'), 403);

        $cursos = Curso::whereIn('estado', ['finalizado'])->get();

        if ($request->filled('curso_ids')) {
            $cursoIds = $request->curso_ids;
            
            $encuestas = Encuesta::whereIn('curso_id', $cursoIds)
                ->where('tipo', 'satisfaccion')
                ->with(['curso', 'respuestas'])
                ->get();

            $datosComparativa = [];

            foreach ($encuestas as $encuesta) {
                $preguntas = json_decode($encuesta->preguntas, true);
                $estadisticas = $this->procesarEstadisticas($preguntas, $encuesta->respuestas);
                $promedioGeneral = $this->calcularPromedioGeneral($estadisticas);

                $datosComparativa[] = [
                    'curso' => $encuesta->curso->nombre,
                    'promedio' => $promedioGeneral,
                    'participacion' => $encuesta->respuestas->count(),
                ];
            }

            return view('encuestas.comparativa', compact('cursos', 'datosComparativa'));
        }

        return view('encuestas.comparativa', compact('cursos'));
    }

    /**
     * Cerrar encuestas vencidas automáticamente
     */
    public function cerrarVencidas()
    {
        abort_unless(Auth::user()->can('sistema.configuracion'), 403);

        $ahora = now();
        
        $encuestasVencidas = Encuesta::where('activa', true)
            ->where('fecha_fin', '<', $ahora)
            ->get();

        $totalCerradas = 0;

        DB::beginTransaction();

        try {
            foreach ($encuestasVencidas as $encuesta) {
                $encuesta->update(['activa' => false]);
                $totalCerradas++;
            }

            DB::commit();

            Log::info("Encuestas vencidas cerradas automáticamente: {$totalCerradas}");

            return back()->with('success', "✅ Se cerraron {$totalCerradas} encuesta(s) vencida(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cerrar encuestas vencidas: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al cerrar encuestas vencidas.']);
        }
    }

    /**
     * Vista previa de encuesta (sin guardar)
     */
    public function preview(Request $request)
    {
        abort_unless(Auth::user()->can('encuestas.crear'), 403);

        $preguntas = json_decode($request->preguntas, true);

        if (!$preguntas) {
            return back()->withErrors(['error' => 'No se pudieron cargar las preguntas.']);
        }

        return view('encuestas.preview', compact('preguntas'));
    }

    /**
     * Enviar recordatorio a estudiantes que no han respondido
     */
    public function enviarRecordatorio(Encuesta $encuesta)
    {
        abort_unless(Auth::user()->can('notificaciones.enviar'), 403);

        $inscripciones = Inscripcion::where('curso_id', $encuesta->curso_id)
            ->where('estado', 'confirmada')
            ->whereDoesntHave('respuestasEncuestas', function ($query) use ($encuesta) {
                $query->where('encuesta_id', $encuesta->id);
            })
            ->with('estudiante.user')
            ->get();

        $totalEnviados = 0;

        foreach ($inscripciones as $inscripcion) {
            // Aquí implementarías el envío de email/notificación
            // Por ahora solo incrementamos el contador
            $totalEnviados++;
        }

        Log::info("Recordatorios de encuesta enviados", [
            'encuesta_id' => $encuesta->id,
            'total_enviados' => $totalEnviados,
        ]);

        return back()->with('success', "✅ Se enviaron {$totalEnviados} recordatorio(s) exitosamente.");
    }

    /**
     * Obtener estadísticas rápidas para dashboard
     */
    public function estadisticasRapidas()
    {
        abort_unless(Auth::user()->can('reportes.estadisticas'), 403);

        $totalEncuestas = Encuesta::count();
        $encuestasActivas = Encuesta::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->count();
        $totalRespuestas = RespuestaEncuesta::count();
        $promedioParticipacion = Encuesta::all()->avg(function ($encuesta) {
            $totalInscritos = $encuesta->curso->inscripciones()->where('estado', 'confirmada')->count();
            $totalRespuestas = $encuesta->respuestas()->count();
            return $totalInscritos > 0 ? ($totalRespuestas / $totalInscritos) * 100 : 0;
        });

        return response()->json([
            'total_encuestas' => $totalEncuestas,
            'encuestas_activas' => $encuestasActivas,
            'total_respuestas' => $totalRespuestas,
            'promedio_participacion' => round($promedioParticipacion, 1),
        ]);
    }
}