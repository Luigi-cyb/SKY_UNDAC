<?php

namespace App\Http\Controllers;

use App\Models\MaterialCurso;
use App\Models\Curso;
use App\Models\DescargaMaterial;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MaterialCursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Auth::user()->can('materiales.ver'), 403);

        $user = Auth::user();
        $query = MaterialCurso::with(['curso']);

        // Filtrar por rol
        if ($user->hasRole('Estudiante')) {
            // Estudiantes solo ven materiales de sus cursos inscritos
            $cursosInscritos = $user->estudiante->inscripciones()
                ->where('estado', 'confirmada')
                ->pluck('curso_id');
            
            $query->whereIn('curso_id', $cursosInscritos)
                  ->where('activo', true)
                  ->where(function ($q) {
                      $q->whereNull('fecha_disponible_desde')
                        ->orWhere('fecha_disponible_desde', '<=', now());
                  })
                  ->where(function ($q) {
                      $q->whereNull('fecha_disponible_hasta')
                        ->orWhere('fecha_disponible_hasta', '>=', now());
                  });
        } elseif ($user->hasRole('Docente')) {
            // Docentes ven materiales de sus cursos asignados
            $cursosDocente = $user->docente->asignaciones()
                ->pluck('curso_id');
            
            $query->whereIn('curso_id', $cursosDocente);
        }

        // Filtros adicionales
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        $materiales = $query->orderBy('created_at', 'desc')->paginate(15);

        // Obtener cursos para filtros
        $cursos = $user->hasRole('Administrador') || $user->hasRole('Comité Académico')
            ? Curso::whereIn('estado', ['convocatoria', 'en_curso', 'finalizado'])->get()
            : ($user->hasRole('Docente')
                ? Curso::whereIn('id', $user->docente->asignaciones->pluck('curso_id'))->get()
                : Curso::whereIn('id', $user->estudiante->inscripciones()
                    ->where('estado', 'confirmada')
                    ->pluck('curso_id'))->get());

        return view('materiales.index', compact('materiales', 'cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        abort_unless(Auth::user()->can('materiales.subir'), 403);

        $curso_id = $request->curso_id;
        $user = Auth::user();

        // Cursos disponibles según rol
        $cursos = $user->hasRole('Administrador') || $user->hasRole('Comité Académico')
            ? Curso::whereIn('estado', ['convocatoria', 'en_curso'])->get()
            : ($user->hasRole('Docente')
                ? Curso::whereIn('id', $user->docente->asignaciones->pluck('curso_id'))
                       ->whereIn('estado', ['convocatoria', 'en_curso'])
                       ->get()
                : collect());

        return view('materiales.create', compact('cursos', 'curso_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('materiales.subir'), 403);

        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'tipo' => 'required|in:presentacion,lectura,guia,silabo,otro',
            'sesion_numero' => 'nullable|integer|min:1|max:100',
            'archivo' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png|max:10240', // 10MB
            'visible_para' => 'required|in:todos,grupo,restringido',
            'fecha_disponible_desde' => 'nullable|date|after_or_equal:today',
            'fecha_disponible_hasta' => 'nullable|date|after:fecha_disponible_desde',
        ], [
            'curso_id.required' => 'Debe seleccionar un curso.',
            'titulo.required' => 'El título es obligatorio.',
            'archivo.required' => 'Debe seleccionar un archivo.',
            'archivo.max' => 'El archivo no debe superar los 10MB.',
            'archivo.mimes' => 'Tipo de archivo no permitido.',
            'fecha_disponible_desde.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
        ]);

        DB::beginTransaction();

        try {
            // Subir archivo con nombre único
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();
            $nombreUnico = Str::slug($validated['titulo']) . '_' . time() . '.' . $extension;
            $rutaArchivo = $archivo->storeAs('materiales/' . $validated['curso_id'], $nombreUnico, 'public');

            // Crear material
            $material = MaterialCurso::create([
                'curso_id' => $validated['curso_id'],
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'tipo' => $validated['tipo'],
                'sesion_numero' => $validated['sesion_numero'] ?? null,
                'nombre_archivo' => $archivo->getClientOriginalName(),
                'ruta_archivo' => $rutaArchivo,
                'tamanio_bytes' => $archivo->getSize(),
                'tipo_mime' => $archivo->getMimeType(),
                'visible_para' => $validated['visible_para'],
                'fecha_disponible_desde' => $validated['fecha_disponible_desde'] ?? now(),
                'fecha_disponible_hasta' => $validated['fecha_disponible_hasta'] ?? null,
                'activo' => true,
            ]);

            // Registrar en log
            Log::info('Material subido', [
                'material_id' => $material->id,
                'curso_id' => $material->curso_id,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return redirect()->route('materiales.index')
                ->with('success', '✅ Material subido exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Eliminar archivo si existe
            if (isset($rutaArchivo) && Storage::disk('public')->exists($rutaArchivo)) {
                Storage::disk('public')->delete($rutaArchivo);
            }

            Log::error('Error al subir material: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al subir el material. Intente nuevamente.'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialCurso $material)
    {
        abort_unless(Auth::user()->can('materiales.ver'), 403);

        // Verificar acceso
        if (!$this->tieneAccesoMaterial(Auth::user(), $material)) {
            abort(403, 'No tienes acceso a este material.');
        }

        $material->load(['curso', 'descargas.inscripcion.estudiante']);

        // Estadísticas de descargas
        $totalDescargas = $material->descargas->count();
        $descargasUnicas = $material->descargas->unique('inscripcion_id')->count();

        return view('materiales.show', compact('material', 'totalDescargas', 'descargasUnicas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaterialCurso $material)
    {
        abort_unless(Auth::user()->can('materiales.editar'), 403);

        return view('materiales.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaterialCurso $material)
    {
        abort_unless(Auth::user()->can('materiales.editar'), 403);

        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'tipo' => 'required|in:presentacion,lectura,guia,silabo,otro',
            'sesion_numero' => 'nullable|integer|min:1|max:100',
            'visible_para' => 'required|in:todos,grupo,restringido',
            'fecha_disponible_desde' => 'nullable|date',
            'fecha_disponible_hasta' => 'nullable|date|after:fecha_disponible_desde',
            'activo' => 'required|boolean',
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar archivo si se proporciona uno nuevo
            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                $extension = $archivo->getClientOriginalExtension();
                $nombreUnico = Str::slug($validated['titulo']) . '_' . time() . '.' . $extension;
                $rutaArchivo = $archivo->storeAs('materiales/' . $material->curso_id, $nombreUnico, 'public');

                // Eliminar archivo anterior
                if (Storage::disk('public')->exists($material->ruta_archivo)) {
                    Storage::disk('public')->delete($material->ruta_archivo);
                }

                $validated['nombre_archivo'] = $archivo->getClientOriginalName();
                $validated['ruta_archivo'] = $rutaArchivo;
                $validated['tamanio_bytes'] = $archivo->getSize();
                $validated['tipo_mime'] = $archivo->getMimeType();
            }

            $material->update($validated);

            // Registrar en log
            Log::info('Material actualizado', [
                'material_id' => $material->id,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return redirect()->route('materiales.index')
                ->with('success', '✅ Material actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al actualizar material: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al actualizar el material.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialCurso $material)
    {
        abort_unless(Auth::user()->can('materiales.eliminar'), 403);

        DB::beginTransaction();

        try {
            // Eliminar archivo físico
            if (Storage::disk('public')->exists($material->ruta_archivo)) {
                Storage::disk('public')->delete($material->ruta_archivo);
            }

            // Eliminar registros de descargas
            $material->descargas()->delete();

            // Eliminar material
            $material->delete();

            // Registrar en log
            Log::info('Material eliminado', [
                'material_id' => $material->id,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return redirect()->route('materiales.index')
                ->with('success', '✅ Material eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al eliminar material: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al eliminar el material.']);
        }
    }

    /**
     * Descargar material
     */
    public function descargar(MaterialCurso $material)
    {
        abort_unless(Auth::user()->can('materiales.descargar'), 403);

        // Verificar si el usuario tiene acceso al material
        if (!$this->tieneAccesoMaterial(Auth::user(), $material)) {
            abort(403, 'No tienes acceso a este material.');
        }

        // Verificar si el archivo existe
        if (!Storage::disk('public')->exists($material->ruta_archivo)) {
            abort(404, 'El archivo no existe.');
        }

        DB::beginTransaction();

        try {
            // Registrar descarga si es estudiante
            $user = Auth::user();
            if ($user->hasRole('Estudiante')) {
                $estudiante = $user->estudiante;
                $inscripcion = $estudiante->inscripciones()
                    ->where('curso_id', $material->curso_id)
                    ->where('estado', 'confirmada')
                    ->first();

                if ($inscripcion) {
                    DescargaMaterial::create([
                        'material_id' => $material->id,
                        'inscripcion_id' => $inscripcion->id,
                        'fecha_descarga' => now(),
                        'ip_descarga' => request()->ip(),
                    ]);
                }
            }

            DB::commit();

            // Log de descarga
            Log::info('Material descargado', [
                'material_id' => $material->id,
                'usuario' => $user->email,
                'ip' => request()->ip(),
            ]);

            return Storage::disk('public')->download($material->ruta_archivo, $material->nombre_archivo);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al descargar material: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al descargar el material.']);
        }
    }

    /**
     * Verificar si el usuario tiene acceso al material
     */
    private function tieneAccesoMaterial($user, $material): bool
    {
        // Administradores y comité académico siempre tienen acceso
        if ($user->hasAnyRole(['Administrador', 'Comité Académico'])) {
            return true;
        }

        // Docentes: verificar si está asignado al curso
        if ($user->hasRole('Docente')) {
            $docenteAsignado = $user->docente->asignaciones()
                ->where('curso_id', $material->curso_id)
                ->exists();
            
            return $docenteAsignado;
        }

        // Estudiantes: verificar inscripción y fechas
        if ($user->hasRole('Estudiante')) {
            $estudiante = $user->estudiante;
            $inscripcion = $estudiante->inscripciones()
                ->where('curso_id', $material->curso_id)
                ->where('estado', 'confirmada')
                ->first();

            if (!$inscripcion) {
                return false;
            }

            // Verificar si el material está activo
            if (!$material->activo) {
                return false;
            }

            // Verificar fechas de disponibilidad
            $ahora = now();
            if ($material->fecha_disponible_desde && $ahora->lt($material->fecha_disponible_desde)) {
                return false;
            }
            if ($material->fecha_disponible_hasta && $ahora->gt($material->fecha_disponible_hasta)) {
                return false;
            }

            // Verificar visibilidad
            if ($material->visible_para === 'restringido') {
                // Aquí podrías agregar lógica adicional para grupos específicos
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Ver materiales por curso
     */
    public function porCurso($curso_id)
    {
        abort_unless(Auth::user()->can('materiales.ver'), 403);

        $curso = Curso::findOrFail($curso_id);
        $user = Auth::user();

        // Verificar acceso al curso
        if ($user->hasRole('Estudiante')) {
            $inscrito = $user->estudiante->inscripciones()
                ->where('curso_id', $curso_id)
                ->where('estado', 'confirmada')
                ->exists();
            
            if (!$inscrito) {
                abort(403, 'No estás inscrito en este curso.');
            }
        }

        $query = MaterialCurso::where('curso_id', $curso_id);

        // Filtrar por fechas si es estudiante
        if ($user->hasRole('Estudiante')) {
            $query->where('activo', true)
                  ->where(function ($q) {
                      $q->whereNull('fecha_disponible_desde')
                        ->orWhere('fecha_disponible_desde', '<=', now());
                  })
                  ->where(function ($q) {
                      $q->whereNull('fecha_disponible_hasta')
                        ->orWhere('fecha_disponible_hasta', '>=', now());
                  });
        }

        $materiales = $query->orderBy('sesion_numero')
                           ->orderBy('created_at', 'desc')
                           ->get();

        // Agrupar por tipo
        $materialesPorTipo = $materiales->groupBy('tipo');

        return view('materiales.por-curso', compact('curso', 'materiales', 'materialesPorTipo'));
    }

    /**
     * Ver estadísticas de descargas
     */
    public function estadisticas($curso_id)
    {
        abort_unless(Auth::user()->can('reportes.academicos'), 403);

        $curso = Curso::findOrFail($curso_id);
        $materiales = MaterialCurso::where('curso_id', $curso_id)->get();

        $estadisticas = [];
        foreach ($materiales as $material) {
            $estadisticas[] = [
                'material' => $material,
                'total_descargas' => $material->descargas->count(),
                'descargas_unicas' => $material->descargas->unique('inscripcion_id')->count(),
                'ultima_descarga' => $material->descargas->max('fecha_descarga'),
            ];
        }

        return view('materiales.estadisticas', compact('curso', 'estadisticas'));
    }

    /**
     * Activar/Desactivar material
     */
    public function toggleActivo(MaterialCurso $material)
    {
        abort_unless(Auth::user()->can('materiales.editar'), 403);

        $material->update(['activo' => !$material->activo]);

        $estado = $material->activo ? 'activado' : 'desactivado';

        return back()->with('success', "✅ Material {$estado} exitosamente.");
    }

    /**
     * Descargar todos los materiales de un curso (ZIP)
     */
    public function descargarTodos($curso_id)
    {
        abort_unless(Auth::user()->can('materiales.descargar'), 403);

        $curso = Curso::findOrFail($curso_id);
        $user = Auth::user();

        // Verificar acceso
        if ($user->hasRole('Estudiante')) {
            $inscrito = $user->estudiante->inscripciones()
                ->where('curso_id', $curso_id)
                ->where('estado', 'confirmada')
                ->exists();
            
            if (!$inscrito) {
                abort(403);
            }
        }

        $materiales = MaterialCurso::where('curso_id', $curso_id)
            ->where('activo', true)
            ->get();

        // Aquí podrías implementar la creación de un ZIP con todos los archivos
        // Por ahora retornamos un mensaje

        return back()->with('info', 'ℹ️ Funcionalidad de descarga masiva en desarrollo.');
    }
}