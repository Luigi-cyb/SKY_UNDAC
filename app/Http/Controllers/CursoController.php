<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CategoriaCurso;
use App\Models\Modalidad;
use App\Http\Requests\CursoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Auth::user()->can('cursos.ver'), 403);

        $query = Curso::with(['categoria', 'modalidad']);

        // Filtros
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('modalidad_id')) {
            $query->where('modalidad_id', $request->modalidad_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('codigo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        // Ordenamiento
        $orderBy = $request->get('ordenar', 'created_at');
        $orderDir = $request->get('direccion', 'desc');
        $query->orderBy($orderBy, $orderDir);

        $cursos = $query->paginate(15);

        // Calcular estadísticas rápidas para cada curso
        foreach ($cursos as $curso) {
            $curso->total_inscritos = $curso->inscripciones()
                ->where('estado', 'confirmada')
                ->count();
            $curso->porcentaje_ocupacion = $curso->cupo_maximo > 0 
                ? round(($curso->total_inscritos / $curso->cupo_maximo) * 100, 1) 
                : 0;
        }

        // Datos para filtros
        $categorias = CategoriaCurso::where('activo', true)->get();
        $modalidades = Modalidad::where('activo', true)->get();

        return view('cursos.index', compact('cursos', 'categorias', 'modalidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->can('cursos.crear'), 403);

        $categorias = CategoriaCurso::where('activo', true)->get();
        $modalidades = Modalidad::where('activo', true)->get();

        return view('cursos.create', compact('categorias', 'modalidades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CursoRequest $request)
{
    abort_unless(Auth::user()->can('cursos.crear'), 403);

    DB::beginTransaction();

    try {
        $validated = $request->validated();

        // Subir sílabo si existe
        if ($request->hasFile('silabo')) {
            $silabo = $request->file('silabo');
            $nombreSilabo = time() . '_' . $silabo->getClientOriginalName();
            $rutaSilabo = $silabo->storeAs('silabos', $nombreSilabo, 'public');
            $validated['silabo_url'] = $rutaSilabo;
        }

        // Subir imagen si existe
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->storeAs('cursos/imagenes', $nombreImagen, 'public');
            $validated['imagen_url'] = $rutaImagen;
        }

        // ✅ MAPEO CORREGIDO - Incluye TODOS los campos
        $cursoData = [
            'codigo' => $validated['codigo'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'objetivos' => $validated['objetivos'] ?? null,
            'competencias' => $request->input('competencias') ?? null, // ✅ CORREGIDO
            'temario' => $request->input('temario') ?? null, // ✅ AGREGADO
            'perfil_ingreso' => $validated['requisitos'] ?? null,
            'perfil_egreso' => null,
            'categoria_id' => $validated['categoria_id'],
            'modalidad_id' => $validated['modalidad_id'],
            'nivel' => $request->input('nivel') ?? null, // ✅ CORREGIDO
            'horas_academicas' => $validated['duracion_horas'],
            'cupo_minimo' => $validated['cupo_minimo'],
            'cupo_maximo' => $validated['cupo_maximo'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'costo_inscripcion' => $validated['costo'],
            'nota_minima_aprobacion' => $validated['nota_minima_aprobacion'],
            'asistencia_minima_porcentaje' => $validated['porcentaje_asistencia_minima'],
            'estado' => $validated['estado'] ?? 'borrador',
            'silabo_url' => $validated['silabo_url'] ?? null,
            'imagen_url' => $validated['imagen_url'] ?? null,
        ];

        $curso = Curso::create($cursoData);

        // Log
        Log::info('Curso creado', [
            'curso_id' => $curso->id,
            'codigo' => $curso->codigo,
            'usuario' => Auth::user()->email,
        ]);

        DB::commit();

        return redirect()->route('cursos.index')
            ->with('success', '✅ Curso creado exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();

        // Eliminar archivos si existen
        if (isset($rutaSilabo) && Storage::disk('public')->exists($rutaSilabo)) {
            Storage::disk('public')->delete($rutaSilabo);
        }
        if (isset($rutaImagen) && Storage::disk('public')->exists($rutaImagen)) {
            Storage::disk('public')->delete($rutaImagen);
        }

        Log::error('Error al crear curso: ' . $e->getMessage());

        return back()->withErrors(['error' => '❌ Error al crear el curso. Intente nuevamente.'])
            ->withInput();
    }
}
    /**
     * Display the specified resource.
     */
    public function show(Curso $curso)
    {
        abort_unless(Auth::user()->can('cursos.ver'), 403);

        $curso->load([
            'categoria', 
            'modalidad', 
            'inscripciones.estudiante', 
            'asignacionesDocentes.docente',
            'evaluaciones',
            'materiales'
        ]);

        // Estadísticas del curso
        $totalInscritos = $curso->inscripciones()->where('estado', 'confirmada')->count();
        $porcentajeOcupacion = $curso->cupo_maximo > 0 
            ? round(($totalInscritos / $curso->cupo_maximo) * 100, 1) 
            : 0;
        $cuposDisponibles = $curso->cupo_maximo - $totalInscritos;

        // Estadísticas académicas
        $promedioNotas = $curso->inscripciones()
            ->where('estado', 'confirmada')
            ->whereHas('calificaciones')
            ->with('calificaciones')
            ->get()
            ->avg(function ($inscripcion) {
                return $inscripcion->calificaciones->avg('nota');
            });

        $tasaAprobacion = 0;
        if ($totalInscritos > 0) {
            $aprobados = $curso->inscripciones()
                ->where('estado', 'confirmada')
                ->whereHas('calificaciones', function ($query) use ($curso) {
                    $query->havingRaw('AVG(nota) >= ?', [$curso->nota_minima_aprobacion]);
                })
                ->count();
            $tasaAprobacion = round(($aprobados / $totalInscritos) * 100, 1);
        }

        return view('cursos.show', compact(
            'curso', 
            'totalInscritos', 
            'porcentajeOcupacion', 
            'cuposDisponibles',
            'promedioNotas',
            'tasaAprobacion'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        abort_unless(Auth::user()->can('cursos.editar'), 403);

        $categorias = CategoriaCurso::where('activo', true)->get();
        $modalidades = Modalidad::where('activo', true)->get();

        return view('cursos.edit', compact('curso', 'categorias', 'modalidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CursoRequest $request, Curso $curso)
{
    abort_unless(Auth::user()->can('cursos.editar'), 403);

    DB::beginTransaction();

    try {
        $validated = $request->validated();

        // Subir nuevo sílabo si existe
        if ($request->hasFile('silabo')) {
            // Eliminar sílabo anterior si existe
            if ($curso->silabo_url && Storage::disk('public')->exists($curso->silabo_url)) {
                Storage::disk('public')->delete($curso->silabo_url);
            }

            $silabo = $request->file('silabo');
            $nombreSilabo = time() . '_' . $silabo->getClientOriginalName();
            $rutaSilabo = $silabo->storeAs('silabos', $nombreSilabo, 'public');
            $validated['silabo_url'] = $rutaSilabo;
        }

        // Subir nueva imagen si existe
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($curso->imagen_url && Storage::disk('public')->exists($curso->imagen_url)) {
                Storage::disk('public')->delete($curso->imagen_url);
            }

            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->storeAs('cursos/imagenes', $nombreImagen, 'public');
            $validated['imagen_url'] = $rutaImagen;
        }

        // ✅ MAPEO CORREGIDO - Incluye TODOS los campos
        $cursoData = [
            'codigo' => $validated['codigo'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? $curso->descripcion,
            'objetivos' => $validated['objetivos'] ?? $curso->objetivos,
            'competencias' => $request->input('competencias') ?? $curso->competencias, // ✅ CORREGIDO
            'temario' => $request->input('temario') ?? $curso->temario, // ✅ AGREGADO
            'perfil_ingreso' => $validated['requisitos'] ?? $curso->perfil_ingreso,
            'categoria_id' => $validated['categoria_id'],
            'modalidad_id' => $validated['modalidad_id'],
            'nivel' => $request->input('nivel') ?? $curso->nivel, // ✅ CORREGIDO
            'horas_academicas' => $validated['duracion_horas'],
            'cupo_minimo' => $validated['cupo_minimo'],
            'cupo_maximo' => $validated['cupo_maximo'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'costo_inscripcion' => $validated['costo'],
            'nota_minima_aprobacion' => $validated['nota_minima_aprobacion'],
            'asistencia_minima_porcentaje' => $validated['porcentaje_asistencia_minima'],
            'estado' => $validated['estado'] ?? $curso->estado,
        ];

        // Agregar URLs de archivos si existen
        if (isset($validated['silabo_url'])) {
            $cursoData['silabo_url'] = $validated['silabo_url'];
        }
        if (isset($validated['imagen_url'])) {
            $cursoData['imagen_url'] = $validated['imagen_url'];
        }

        $curso->update($cursoData);

        // Log
        Log::info('Curso actualizado', [
            'curso_id' => $curso->id,
            'codigo' => $curso->codigo,
            'usuario' => Auth::user()->email,
        ]);

        DB::commit();

        return redirect()->route('cursos.index')
            ->with('success', '✅ Curso actualizado exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error al actualizar curso: ' . $e->getMessage());

        return back()->withErrors(['error' => '❌ Error al actualizar el curso.'])
            ->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        abort_unless(Auth::user()->can('cursos.eliminar'), 403);

        // Verificar que no tenga inscripciones confirmadas
        $inscritosConfirmados = $curso->inscripciones()
            ->where('estado', 'confirmada')
            ->count();

        if ($inscritosConfirmados > 0) {
            return redirect()->route('cursos.index')
                ->with('error', "❌ No se puede eliminar un curso con {$inscritosConfirmados} inscripción(es) confirmada(s).");
        }

        DB::beginTransaction();

        try {
            // Eliminar archivos asociados
            if ($curso->silabo_url && Storage::disk('public')->exists($curso->silabo_url)) {
                Storage::disk('public')->delete($curso->silabo_url);
            }
            if ($curso->imagen_url && Storage::disk('public')->exists($curso->imagen_url)) {
                Storage::disk('public')->delete($curso->imagen_url);
            }

            // Eliminar inscripciones provisionales
            $curso->inscripciones()->where('estado', 'provisional')->delete();

            // Eliminar asignaciones de docentes
            $curso->asignacionesDocentes()->delete();

            // Eliminar el curso
            $curso->delete();

            // Log
            Log::info('Curso eliminado', [
                'curso_id' => $curso->id,
                'codigo' => $curso->codigo,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return redirect()->route('cursos.index')
                ->with('success', '✅ Curso eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al eliminar curso: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al eliminar el curso.']);
        }
    }

    /**
     * Cambiar estado del curso
     */
    public function cambiarEstado(Request $request, Curso $curso)
    {
        abort_unless(Auth::user()->can('cursos.editar'), 403);

        $request->validate([
            'estado' => 'required|in:borrador,convocatoria,en_curso,finalizado,cancelado,archivado',
        ]);

        DB::beginTransaction();

        try {
            $estadoAnterior = $curso->estado;
            $curso->update(['estado' => $request->estado]);

            // Log
            Log::info('Estado de curso cambiado', [
                'curso_id' => $curso->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $request->estado,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return back()->with('success', "✅ Estado cambiado a: {$request->estado}");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al cambiar estado del curso: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al cambiar el estado.']);
        }
    }

    /**
     * Ver cursos públicos (catálogo)
     */
    public function catalogo(Request $request)
    {
        $query = Curso::with(['categoria', 'modalidad'])
            ->where('estado', 'convocatoria');

        // Filtros públicos
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('modalidad_id')) {
            $query->where('modalidad_id', $request->modalidad_id);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        $cursos = $query->orderBy('fecha_inicio', 'asc')->paginate(12);

        // Calcular cupos disponibles
        foreach ($cursos as $curso) {
            $inscritos = $curso->inscripciones()->where('estado', 'confirmada')->count();
            $curso->cupos_disponibles = $curso->cupo_maximo - $inscritos;
            $curso->porcentaje_ocupacion = $curso->cupo_maximo > 0 
                ? round(($inscritos / $curso->cupo_maximo) * 100, 1) 
                : 0;
        }

        $categorias = CategoriaCurso::where('activo', true)->get();
        $modalidades = Modalidad::where('activo', true)->get();

        return view('cursos.catalogo', compact('cursos', 'categorias', 'modalidades'));
    }
    public function asignarDocente(Request $request, Curso $curso)
{
    $validated = $request->validate([
        'docente_id' => 'required|exists:docentes,id',
        'tipo_asignacion' => 'required|in:titular,asistente,invitado',
        'carga_horaria' => 'required|integer|min:1',
    ]);

    // Verificar si el docente ya está asignado
    $existe = \App\Models\AsignacionDocente::where('curso_id', $curso->id)
        ->where('docente_id', $validated['docente_id'])
        ->where('activo', true)
        ->exists();

    if ($existe) {
        return redirect()->back()->with('error', '❌ Este docente ya está asignado al curso.');
    }

    \App\Models\AsignacionDocente::create([
        'docente_id' => $validated['docente_id'],
        'curso_id' => $curso->id,
        'tipo_asignacion' => $validated['tipo_asignacion'],
        'carga_horaria' => $validated['carga_horaria'],
        'fecha_asignacion' => now(),
        'activo' => true,
    ]);

    return redirect()->back()->with('success', '✅ Docente asignado correctamente al curso.');
}

public function desasignarDocente(Curso $curso, $asignacion)
{
    $asignacion = \App\Models\AsignacionDocente::findOrFail($asignacion);
    $asignacion->delete();

    return redirect()->back()->with('success', '✅ Docente desasignado correctamente del curso.');
}
}