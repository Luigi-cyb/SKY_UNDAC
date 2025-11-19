<?php

namespace App\Http\Controllers;


use App\Models\Asistencia;
use App\Models\SesionCurso;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SesionCursoController extends Controller
{
    /**
     * Mostrar lista de sesiones de un curso
     */
   /**
 * Mostrar lista de sesiones de un curso
 */
public function index($cursoId)
{
    $curso = Curso::findOrFail($cursoId);
    $sesiones = SesionCurso::where('curso_id', $cursoId)
        ->orderBy('numero_sesion')
        ->get();

    // ✅ NUEVO: Obtener información de horas disponibles
    $horasInfo = $this->validarHorasDisponibles($cursoId);

    return view('sesiones.index', compact('curso', 'sesiones', 'horasInfo'));
}

    /**
     * Mostrar formulario para crear sesión
     */
    /**
 * Mostrar formulario para crear sesión
 */
public function create($cursoId)
{
    $curso = Curso::findOrFail($cursoId);
    
    // Obtener el siguiente número de sesión
    $ultimaSesion = SesionCurso::where('curso_id', $cursoId)
        ->orderBy('numero_sesion', 'desc')
        ->first();
    
    $siguienteNumero = $ultimaSesion ? $ultimaSesion->numero_sesion + 1 : 1;

    // ✅ NUEVO: Obtener información de horas disponibles
    $horasInfo = $this->validarHorasDisponibles($cursoId);

    return view('sesiones.create', compact('curso', 'siguienteNumero', 'horasInfo'));
}

    /**
 * Guardar nueva sesión
 */
public function store(Request $request, $cursoId)
{
    $curso = Curso::findOrFail($cursoId);

    $validated = $request->validate([
        'numero_sesion' => 'required|integer|min:1',
        'titulo' => 'required|string|max:200',
        'descripcion' => 'nullable|string',
        'objetivos' => 'nullable|string',
        'fecha_sesion' => 'required|date',
        'hora_inicio' => 'required',
        'hora_fin' => 'required|after:hora_inicio',
        'enlace_clase_vivo' => 'nullable|url|max:500',
        'enlace_grabacion' => 'nullable|url|max:500',
        'plataforma_vivo' => 'required|in:youtube,google_meet,zoom,otro',
        'estado' => 'required|in:programada,en_vivo,finalizada,cancelada',
        'visible' => 'boolean',
        'permite_asistencia' => 'boolean'
    ]);

    DB::beginTransaction();

    try {
        // Verificar que no exista ya ese número de sesión
        $existe = SesionCurso::where('curso_id', $cursoId)
            ->where('numero_sesion', $validated['numero_sesion'])
            ->exists();

        if ($existe) {
            return back()->withErrors(['numero_sesion' => 'Ya existe una sesión con ese número'])
                ->withInput();
        }

        // Calcular duración en minutos de la nueva sesión
        $inicio = \Carbon\Carbon::parse($validated['hora_inicio']);
        $fin = \Carbon\Carbon::parse($validated['hora_fin']);
        $duracionMinutos = $fin->diffInMinutes($inicio);

        // ✅ NUEVA VALIDACIÓN: Verificar horas disponibles
        $horasInfo = $this->validarHorasDisponibles($cursoId);
        
        if ($duracionMinutos > $horasInfo['minutos_disponibles']) {
            return back()->withErrors([
                'hora_fin' => 'Has alcanzado el límite de horas del curso. ' .
                             'Disponibles: ' . $horasInfo['horas_disponibles'] . ' horas (' . $horasInfo['minutos_disponibles'] . ' minutos). ' .
                             'Intentas programar: ' . round($duracionMinutos / 60, 2) . ' horas (' . $duracionMinutos . ' minutos).'
            ])->withInput();
        }

        SesionCurso::create([
            'curso_id' => $cursoId,
            'numero_sesion' => $validated['numero_sesion'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'objetivos' => $validated['objetivos'],
            'fecha_sesion' => $validated['fecha_sesion'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'duracion_minutos' => $duracionMinutos,
            'enlace_clase_vivo' => $validated['enlace_clase_vivo'],
            'enlace_grabacion' => $validated['enlace_grabacion'],
            'plataforma_vivo' => $validated['plataforma_vivo'],
            'estado' => $validated['estado'],
            'visible' => $request->has('visible'),
            'permite_asistencia' => $request->has('permite_asistencia')
        ]);

        DB::commit();

        return redirect()->route('sesiones.index', $cursoId)
            ->with('success', 'Sesión creada exitosamente. Horas usadas: ' . 
                   round(($horasInfo['minutos_usados'] + $duracionMinutos) / 60, 2) . ' / ' . 
                   $horasInfo['horas_totales'] . ' horas');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear sesión: ' . $e->getMessage());
        
        return back()->withErrors(['error' => 'Error al crear la sesión'])
            ->withInput();
    }
}

    /**
     * Mostrar formulario de edición
     */
    public function edit($sesionId)
    {
        $sesion = SesionCurso::findOrFail($sesionId);
        $curso = $sesion->curso;

        return view('sesiones.edit', compact('sesion', 'curso'));
    }

    /**
     * Actualizar sesión
     */public function update(Request $request, $sesionId)
{
    $sesion = SesionCurso::findOrFail($sesionId);

    $validated = $request->validate([
        'titulo' => 'required|string|max:200',
        'descripcion' => 'nullable|string',
        'objetivos' => 'nullable|string',
        'fecha_sesion' => 'required|date',
        'hora_inicio' => 'required',
        'hora_fin' => 'required',
        'enlace_clase_vivo' => 'nullable|url|max:500',
        'enlace_grabacion' => 'nullable|url|max:500',
        'plataforma_vivo' => 'required|in:youtube,google_meet,zoom,otro',
        'estado' => 'required|in:programada,en_vivo,finalizada,cancelada',
    ]);

    DB::beginTransaction();

    try {
        // Calcular duración
        $inicio = \Carbon\Carbon::parse($validated['hora_inicio']);
        $fin = \Carbon\Carbon::parse($validated['hora_fin']);
        $duracionMinutos = $fin->diffInMinutes($inicio);

        $sesion->update([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'objetivos' => $validated['objetivos'],
            'fecha_sesion' => $validated['fecha_sesion'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'duracion_minutos' => $duracionMinutos,
            'enlace_clase_vivo' => $validated['enlace_clase_vivo'],
            'enlace_grabacion' => $validated['enlace_grabacion'],
            'plataforma_vivo' => $validated['plataforma_vivo'],
            'estado' => $validated['estado'],
            'visible' => $request->has('visible') ? 1 : 0,
            'permite_asistencia' => $request->has('permite_asistencia') ? 1 : 0,
        ]);

        DB::commit();

        return redirect()->route('sesiones.index', $sesion->curso_id)
            ->with('success', 'Sesión actualizada exitosamente');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar sesión: ' . $e->getMessage());
        
        return back()->withErrors(['error' => 'Error al actualizar la sesión'])
            ->withInput();
    }
}

    /**
     * Eliminar sesión
     */
    public function destroy($sesionId)
    {
        $sesion = SesionCurso::findOrFail($sesionId);
        $cursoId = $sesion->curso_id;

        DB::beginTransaction();

        try {
            $sesion->delete();
            
            DB::commit();

            return redirect()->route('sesiones.index', $cursoId)
                ->with('success', 'Sesión eliminada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar sesión: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Error al eliminar la sesión']);
        }
    }
    /**
     * Validar horas disponibles del curso
     * Retorna las horas ya programadas y las horas disponibles
     */
    /**
     * Iniciar sesión en vivo
     */
    public function iniciarSesion($sesionId)
    {
        $sesion = SesionCurso::findOrFail($sesionId);

        // Verificar que la sesión esté programada
        if ($sesion->estado === 'en_vivo') {
            return back()->with('error', 'La sesión ya está en vivo');
        }

        if ($sesion->estado === 'finalizada') {
            return back()->with('error', 'No puedes iniciar una sesión finalizada');
        }

        DB::beginTransaction();

        try {
            $sesion->update([
                'estado' => 'en_vivo',
                'permite_asistencia' => true,
                'fecha_inicio_asistencia' => now(),
                'fecha_fin_asistencia' => now()->addMinutes(15) // 15 minutos para marcar asistencia
            ]);

            DB::commit();

            return back()->with('success', 'Sesión iniciada. Los estudiantes pueden marcar asistencia durante 15 minutos.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al iniciar sesión: ' . $e->getMessage());
            
            return back()->with('error', 'Error al iniciar la sesión');
        }
    }

    /**
     * Finalizar sesión en vivo
     */
    public function finalizarSesion($sesionId)
    {
        $sesion = SesionCurso::findOrFail($sesionId);

        // Verificar que la sesión esté en vivo
        if ($sesion->estado !== 'en_vivo') {
            return back()->with('error', 'La sesión no está en vivo');
        }

        DB::beginTransaction();

        try {
            $sesion->update([
                'estado' => 'finalizada',
                'permite_asistencia' => false,
                'fecha_fin_asistencia' => now() // Cerrar ventana de asistencia
            ]);

            DB::commit();

            return back()->with('success', 'Sesión finalizada exitosamente. Ya no se puede marcar asistencia.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al finalizar sesión: ' . $e->getMessage());
            
            return back()->with('error', 'Error al finalizar la sesión');
        }
    }

    /**
     * Validar horas disponibles del curso
     * Retorna las horas ya programadas y las horas disponibles
     */
    
    private function validarHorasDisponibles($cursoId)
{
    // Obtener el curso
    $curso = Curso::findOrFail($cursoId);
    
    // Sumar todos los minutos de las sesiones ya creadas (en valor ABSOLUTO)
    $minutosUsados = SesionCurso::where('curso_id', $cursoId)
        ->sum(DB::raw('ABS(duracion_minutos)'));
    
    // Convertir horas académicas del curso a minutos
    $minutosTotales = $curso->horas_academicas * 60;
    
    // Calcular minutos disponibles
    $minutosDisponibles = $minutosTotales - $minutosUsados;
    
    return [
        'minutos_usados' => abs($minutosUsados), // Valor absoluto
        'minutos_totales' => $minutosTotales,
        'minutos_disponibles' => max(0, $minutosDisponibles), // No puede ser negativo
        'horas_usadas' => round(abs($minutosUsados) / 60, 2), // Valor absoluto
        'horas_totales' => $curso->horas_academicas,
        'horas_disponibles' => round(max(0, $minutosDisponibles) / 60, 2), // No negativo
        'porcentaje_usado' => $minutosTotales > 0 ? round((abs($minutosUsados) / $minutosTotales) * 100, 2) : 0
    ];
}
    /**
 * Ver lista de asistencias de una sesión
 */
public function verAsistencias($sesionId)
{
    $sesion = SesionCurso::with([
    'curso.inscripciones.estudiante.user'
])->findOrFail($sesionId);

    $user = auth()->user();

    // ✅ PERMITIR A DOCENTES Y ADMIN/COMITÉ
    $esDocente = $user->hasRole('Docente');
    $esAdmin = $user->hasAnyRole(['Administrador', 'Comité Académico']);

    // Si es docente, verificar que esté asignado al curso
    if ($esDocente) {
        // Cargar la relación docente si no está cargada
        if (!$user->relationLoaded('docente')) {
            $user->load('docente');
        }

        // Verificar que el usuario tenga un registro de docente
        if (!$user->docente) {
            abort(403, 'No se encontró el perfil de docente para este usuario');
        }

        $docenteAsignado = $sesion->curso->asignacionesDocentes()
            ->where('docente_id', $user->docente->id)
            ->where('activo', true)
            ->exists();

        if (!$docenteAsignado) {
            abort(403, 'No tienes permiso para ver estas asistencias');
        }
    } elseif (!$esAdmin) {
        // Si no es docente ni admin, denegar acceso
        abort(403, 'No tienes permiso para ver estas asistencias');
    }

    // Obtener todas las inscripciones confirmadas del curso
    $inscripciones = $sesion->curso->inscripciones()
        ->where('estado', 'confirmada')
        ->with('estudiante.user')
        ->get();

    // Guardar inscritos para la vista (la vista espera esta variable)
    $inscritos = $inscripciones;

    // Crear array de asistencias con toda la información
    // Crear array de asistencias con toda la información
// Crear array de asistencias con toda la información
$asistencias = $inscripciones->map(function ($inscripcion) use ($sesion) {
    // Buscar la asistencia con valores explícitos
    $asistencia = Asistencia::where('curso_id', (int)$sesion->curso_id)
    ->where('numero_sesion', (int)$sesion->numero_sesion)
    ->where('inscripcion_id', $inscripcion->id)
    ->first();

    return [
        'inscripcion' => $inscripcion,
        'estudiante' => $inscripcion->estudiante,
        'asistencia' => $asistencia,
        'estado' => $asistencia ? $asistencia->estado : 'ausente',
        'hora_registro' => $asistencia ? $asistencia->hora_registro : null,
    ];
});

    // Calcular estadísticas
    $estadisticas = [
        'total' => $inscripciones->count(),
        'presentes' => $asistencias->where('estado', 'presente')->count(),
        'ausentes' => $asistencias->where('estado', 'ausente')->count(),
        'tardanzas' => $asistencias->where('estado', 'tardanza')->count(),
        'porcentaje_asistencia' => $inscripciones->count() > 0 
            ? round(($asistencias->where('estado', 'presente')->count() / $inscripciones->count()) * 100, 1)
            : 0
    ];

    return view('sesiones.asistencias', compact('sesion', 'asistencias', 'estadisticas', 'inscritos'));
}
}