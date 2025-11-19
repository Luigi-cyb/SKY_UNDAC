<?php

namespace App\Http\Controllers;
use App\Models\Pago;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Inscripcion;  // ⭐ AGREGAR ESTA LÍNEA
use App\Models\Asistencia;    // ⭐ AGREGAR ESTA LÍNEA
use App\Models\SesionCurso;   // ⭐ AGREGAR ESTA LÍNEA
use App\Models\Calificacion;
use App\Models\IntentoEvaluacion;
use App\Models\Estudiante;
use App\Models\User;
use App\Http\Requests\EstudianteRequest;
use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Certificado;



class EstudianteController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Auth::user()->can('estudiantes.ver'), 403);

        $query = Estudiante::with('user');

        // Filtros
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombres', 'like', '%' . $buscar . '%')
                  ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                  ->orWhere('dni', 'like', '%' . $buscar . '%')
                  ->orWhere('codigo_universitario', 'like', '%' . $buscar . '%')
                  ->orWhere('email', 'like', '%' . $buscar . '%');
            });
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('escuela')) {
            $query->where('escuela_profesional', 'like', '%' . $request->escuela . '%');
        }

        // Ordenamiento
        $orderBy = $request->get('ordenar', 'created_at');
        $orderDir = $request->get('direccion', 'desc');
        $query->orderBy($orderBy, $orderDir);

        $estudiantes = $query->paginate(15);

        // Calcular estadísticas rápidas
        foreach ($estudiantes as $estudiante) {
            $estudiante->total_inscripciones = $estudiante->inscripciones()->count();
            $estudiante->cursos_activos = $estudiante->inscripciones()
                ->where('estado', 'confirmada')
                ->whereHas('curso', function ($q) {
                    $q->where('estado', 'en_curso');
                })
                ->count();
        }

        return view('estudiantes.index', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->can('estudiantes.crear'), 403);

        return view('estudiantes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    abort_unless(Auth::user()->can('estudiantes.crear'), 403);

    // ✅ VALIDACIÓN ADAPTADA A LA BASE DE DATOS REAL
    $validated = $request->validate([
        'dni' => 'required|string|size:8|regex:/^[0-9]{8}$/|unique:estudiantes,dni',
        'codigo_estudiante' => 'nullable|string|max:20|unique:estudiantes,codigo_estudiante',
        'nombres' => 'required|string|max:100',
        'apellidos' => 'required|string|max:100',
        'correo_institucional' => 'required|email|max:100|unique:estudiantes,correo_institucional|unique:users,email',
        'correo_personal' => 'nullable|email|max:100',
        'telefono' => 'nullable|string|max:15',
        'telefono_emergencia' => 'nullable|string|max:15',
        'direccion' => 'nullable|string',
        'fecha_nacimiento' => 'nullable|date|before:-16 years',
        'sexo' => 'nullable|in:M,F',
        'pertenece_eisc' => 'required|boolean',
        'ciclo_academico' => 'nullable|string|max:10',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'dni.required' => 'El DNI es obligatorio.',
        'dni.size' => 'El DNI debe tener 8 dígitos.',
        'dni.regex' => 'El DNI solo debe contener números.',
        'dni.unique' => 'Este DNI ya está registrado.',
        'correo_institucional.required' => 'El correo institucional es obligatorio.',
        'correo_institucional.unique' => 'Este correo ya está registrado.',
        'pertenece_eisc.required' => '¿Pertenece a EISC? es obligatorio.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ]);

    DB::beginTransaction();

    try {
        // Crear usuario en tabla users
        $user = User::create([
            'name' => $validated['nombres'] . ' ' . $validated['apellidos'],
            'email' => $validated['correo_institucional'], // ✅ Usar correo_institucional
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        // Asignar rol de Estudiante
        $user->assignRole('Estudiante');

        // Subir foto si existe
        $fotoUrl = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $foto->getClientOriginalName();
            $rutaFoto = $foto->storeAs('estudiantes/fotos', $nombreFoto, 'public');
            $fotoUrl = $rutaFoto;
        }

        // ✅ Crear estudiante con campos correctos de la BD
        $estudiante = Estudiante::create([
            'user_id' => $user->id,
            'dni' => $validated['dni'],
            'codigo_estudiante' => $validated['codigo_estudiante'] ?? null, // ✅ codigo_estudiante
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'sexo' => $validated['sexo'] ?? null, // ✅ sexo (no genero)
            'telefono' => $validated['telefono'] ?? null,
            'telefono_emergencia' => $validated['telefono_emergencia'] ?? null, // ✅ existe en BD
            'direccion' => $validated['direccion'] ?? null,
            'correo_personal' => $validated['correo_personal'] ?? null, // ✅ existe en BD
            'correo_institucional' => $validated['correo_institucional'], // ✅ correo_institucional
            'pertenece_eisc' => $validated['pertenece_eisc'], // ✅ existe en BD
            'ciclo_academico' => $validated['ciclo_academico'] ?? null, // ✅ ciclo_academico
            'foto_url' => $fotoUrl,
            'activo' => true,
        ]);

        // Log
        Log::info('Estudiante creado', [
            'estudiante_id' => $estudiante->id,
            'dni' => $estudiante->dni,
            'usuario' => Auth::user()->email,
        ]);

        DB::commit();

        return redirect()->route('estudiantes.index')
            ->with('success', '✅ Estudiante creado exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();

        // Eliminar foto si existe
        if (isset($rutaFoto) && Storage::disk('public')->exists($rutaFoto)) {
            Storage::disk('public')->delete($rutaFoto);
        }

        Log::error('Error al crear estudiante: ' . $e->getMessage());

        return back()->withErrors(['error' => '❌ Error al crear el estudiante. ' . $e->getMessage()])
            ->withInput();
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Estudiante $estudiante)
{
    abort_unless(Auth::user()->can('estudiantes.ver'), 403);

    // Cargar relaciones necesarias
    $estudiante->load([
        'user', 
        'inscripciones.curso.categoria',
        'inscripciones.curso.modalidad',
        'inscripciones.pago',
        'inscripciones.certificado'
    ]);

    // Calcular estadísticas
    $totalInscripciones = $estudiante->inscripciones->count();
    
    $cursosActivos = $estudiante->inscripciones->filter(function($inscripcion) {
        return $inscripcion->estado == 'confirmada' 
            && $inscripcion->curso 
            && $inscripcion->curso->estado == 'en_curso';
    })->count();

    $cursosFinalizados = $estudiante->inscripciones->filter(function($inscripcion) {
        return $inscripcion->estado == 'confirmada' 
            && $inscripcion->curso 
            && $inscripcion->curso->estado == 'finalizado';
    })->count();

    $certificadosObtenidos = $estudiante->inscripciones->filter(function($inscripcion) {
        return $inscripcion->certificado !== null;
    })->count();

    $promedioGeneral = 0;

    return view('estudiantes.show', compact(
        'estudiante',
        'totalInscripciones',
        'cursosActivos',
        'cursosFinalizados',
        'certificadosObtenidos',
        'promedioGeneral'
    ));
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        abort_unless(Auth::user()->can('estudiantes.editar'), 403);

        return view('estudiantes.edit', compact('estudiante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estudiante $estudiante)
{
    abort_unless(Auth::user()->can('estudiantes.editar'), 403);

    // ✅ VALIDACIÓN ADAPTADA A LA BASE DE DATOS REAL
    $validated = $request->validate([
        'dni' => 'required|string|size:8|regex:/^[0-9]{8}$/|unique:estudiantes,dni,' . $estudiante->id,
        'codigo_estudiante' => 'nullable|string|max:20|unique:estudiantes,codigo_estudiante,' . $estudiante->id,
        'nombres' => 'required|string|max:100',
        'apellidos' => 'required|string|max:100',
        'correo_institucional' => 'required|email|max:100|unique:estudiantes,correo_institucional,' . $estudiante->id . '|unique:users,email,' . $estudiante->user_id,
        'correo_personal' => 'nullable|email|max:100',
        'telefono' => 'nullable|string|max:15',
        'telefono_emergencia' => 'nullable|string|max:15',
        'direccion' => 'nullable|string',
        'fecha_nacimiento' => 'nullable|date|before:-16 years',
        'sexo' => 'nullable|in:M,F',
        'pertenece_eisc' => 'required|boolean',
        'ciclo_academico' => 'nullable|string|max:10',
        'activo' => 'required|boolean',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    DB::beginTransaction();

    try {
        // Subir nueva foto si existe
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($estudiante->foto_url && Storage::disk('public')->exists($estudiante->foto_url)) {
                Storage::disk('public')->delete($estudiante->foto_url);
            }

            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $foto->getClientOriginalName();
            $rutaFoto = $foto->storeAs('estudiantes/fotos', $nombreFoto, 'public');
            $validated['foto_url'] = $rutaFoto;
        }

        // ✅ Actualizar estudiante con campos correctos de la BD
        $estudiante->update([
            'dni' => $validated['dni'],
            'codigo_estudiante' => $validated['codigo_estudiante'] ?? null,
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'sexo' => $validated['sexo'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
            'correo_personal' => $validated['correo_personal'] ?? null,
            'correo_institucional' => $validated['correo_institucional'],
            'pertenece_eisc' => $validated['pertenece_eisc'],
            'ciclo_academico' => $validated['ciclo_academico'] ?? null,
            'activo' => $validated['activo'],
            'foto_url' => $validated['foto_url'] ?? $estudiante->foto_url,
        ]);

        // Actualizar usuario asociado
        $estudiante->user->update([
            'name' => $validated['nombres'] . ' ' . $validated['apellidos'],
            'email' => $validated['correo_institucional'],
        ]);

        // Log
        Log::info('Estudiante actualizado', [
            'estudiante_id' => $estudiante->id,
            'dni' => $estudiante->dni,
            'usuario' => Auth::user()->email,
        ]);

        DB::commit();

        return redirect()->route('estudiantes.index')
            ->with('success', '✅ Estudiante actualizado exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error al actualizar estudiante: ' . $e->getMessage());

        return back()->withErrors(['error' => '❌ Error al actualizar el estudiante: ' . $e->getMessage()])
            ->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        abort_unless(Auth::user()->can('estudiantes.eliminar'), 403);

        // Verificar que no tenga inscripciones activas
        $inscripcionesActivas = $estudiante->inscripciones()
            ->whereIn('estado', ['provisional', 'confirmada'])
            ->count();

        if ($inscripcionesActivas > 0) {
            return redirect()->route('estudiantes.index')
                ->with('error', "❌ No se puede eliminar un estudiante con {$inscripcionesActivas} inscripción(es) activa(s).");
        }

        DB::beginTransaction();

        try {
            // Eliminar foto si existe
            if ($estudiante->foto_url && Storage::disk('public')->exists($estudiante->foto_url)) {
                Storage::disk('public')->delete($estudiante->foto_url);
            }

            // Eliminar inscripciones canceladas/retiradas
            $estudiante->inscripciones()->whereIn('estado', ['cancelada', 'retirada'])->delete();

            // Guardar datos para log
            $estudianteDni = $estudiante->dni;
            $userId = $estudiante->user_id;

            // Eliminar estudiante
            $estudiante->delete();

            // Eliminar usuario asociado
            User::find($userId)->delete();

            // Log
            Log::info('Estudiante eliminado', [
                'estudiante_dni' => $estudianteDni,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return redirect()->route('estudiantes.index')
                ->with('success', '✅ Estudiante eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al eliminar estudiante: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al eliminar el estudiante.']);
        }
    }

    /**
     * Activar/Desactivar estudiante
     */
    public function toggleActivo(Estudiante $estudiante)
    {
        abort_unless(Auth::user()->can('estudiantes.editar'), 403);

        DB::beginTransaction();

        try {
            $estudiante->update(['activo' => !$estudiante->activo]);

            $estado = $estudiante->activo ? 'activado' : 'desactivado';

            // Log
            Log::info('Estado de estudiante cambiado', [
                'estudiante_id' => $estudiante->id,
                'nuevo_estado' => $estado,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return back()->with('success', "✅ Estudiante {$estado} exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al cambiar estado del estudiante: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al cambiar el estado.']);
        }
    }

    /**
     * Resetear contraseña del estudiante
     */
    public function resetPassword(Request $request, Estudiante $estudiante)
    {
        abort_unless(Auth::user()->can('estudiantes.editar'), 403);

        $request->validate([
            'nueva_password' => 'required|string|min:8|confirmed',
        ], [
            'nueva_password.required' => 'La nueva contraseña es obligatoria.',
            'nueva_password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'nueva_password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        DB::beginTransaction();

        try {
            $estudiante->user->update([
                'password' => Hash::make($request->nueva_password),
            ]);

            // Log
            Log::info('Contraseña de estudiante reseteada', [
                'estudiante_id' => $estudiante->id,
                'usuario' => Auth::user()->email,
            ]);

            DB::commit();

            return back()->with('success', '✅ Contraseña actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al resetear contraseña: ' . $e->getMessage());

            return back()->withErrors(['error' => '❌ Error al actualizar la contraseña.']);
        }
    }

    /**
     * Exportar lista de estudiantes
     */
    public function exportar(Request $request)
    {
        abort_unless(Auth::user()->can('reportes.academicos'), 403);

        // Implementar exportación con Maatwebsite/Excel
        return back()->with('info', 'ℹ️ Funcionalidad de exportación en desarrollo.');
    }
    /**
     * Mis cursos inscritos (para rol Estudiante)
     */
    /**
 * Mis cursos inscritos (para rol Estudiante)
 *//**
 * Mis cursos inscritos (para rol Estudiante)
 */
public function misCursos()
{
    $estudiante = auth()->user()->estudiante;
    
    $inscripciones = Inscripcion::with(['curso.modalidad', 'curso.categoria'])
        ->where('estudiante_id', $estudiante->id)
        ->get();

    // ⭐ NUEVO: Calcular promedios y asistencia para cada inscripción
    foreach ($inscripciones as $inscripcion) {
        // Calcular nota final si no existe
        if ($inscripcion->nota_final === null) {
            $calificaciones = $inscripcion->calificaciones()->with('evaluacion')->get();
            
            if ($calificaciones->count() > 0) {
                $notaFinal = 0;
                $pesoTotal = 0;
                
                foreach ($calificaciones as $calificacion) {
                    $peso = $calificacion->evaluacion->peso_porcentaje / 100;
                    $notaFinal += $calificacion->nota * $peso;
                    $pesoTotal += $peso;
                }
                
                $inscripcion->nota_final = $pesoTotal > 0 ? $notaFinal : null;
            }
        }

        // Calcular porcentaje de asistencia si no existe
       // Calcular porcentaje de asistencia si no existe
if ($inscripcion->porcentaje_asistencia === null) {
    // Contar SOLO sesiones finalizadas
    $totalSesiones = \App\Models\SesionCurso::where('curso_id', $inscripcion->curso_id)
        ->where('estado', 'finalizada') // Solo finalizadas
        ->count();
    
    // Si no hay sesiones finalizadas, usar el total de asistencias registradas
    if ($totalSesiones == 0) {
        $totalSesiones = $inscripcion->asistencias()->count();
    }
    
    $presentes = $inscripcion->asistencias()
        ->where('estado', 'presente')
        ->count();
    
    $inscripcion->porcentaje_asistencia = $totalSesiones > 0 
        ? round(($presentes / $totalSesiones) * 100, 1) 
        : 0;
}
    }

    // Calcular promedio general
    $promedioGeneral = $inscripciones->avg('nota_final') ?? 0;
    
    // Calcular porcentaje de asistencia general
    $porcentajeAsistencia = $inscripciones->avg('porcentaje_asistencia') ?? 0;

    // Sesiones disponibles
    $sesionesDisponibles = collect();
    
    foreach ($inscripciones as $inscripcion) {
        $sesiones = \App\Models\SesionCurso::where('curso_id', $inscripcion->curso_id)
            ->where('permite_asistencia', true)
            ->whereNotNull('fecha_inicio_asistencia')
            ->whereNotNull('fecha_fin_asistencia')
            ->where('fecha_inicio_asistencia', '<=', now())
            ->where('fecha_fin_asistencia', '>=', now())
            ->get()
            ->map(function($sesion) use ($inscripcion) {
                $sesion->minutos_restantes = max(0, now()->diffInMinutes($sesion->fecha_fin_asistencia, false));
                $sesion->asistencia_marcada = \App\Models\Asistencia::where('inscripcion_id', $inscripcion->id)
                    ->where('curso_id', $sesion->curso_id)
                    ->where('numero_sesion', $sesion->numero_sesion)
                    ->exists();
                return $sesion;
            });
        
        $sesionesDisponibles = $sesionesDisponibles->merge($sesiones);
    }

    return view('estudiantes.mis-cursos', compact(
        'inscripciones',
        'promedioGeneral',
        'porcentajeAsistencia',
        'sesionesDisponibles'
    ));
}

public function cursoDetalle(Curso $curso)
{
    try {
        \Log::info('=== INICIO cursoDetalle ===');
        \Log::info('Curso ID: ' . $curso->id);
        
        $user = Auth::user();
        \Log::info('Usuario: ' . $user->email);
        
        // Obtener estudiante
        $estudiante = \App\Models\Estudiante::where('correo_institucional', $user->email)
            ->orWhere('correo_personal', $user->email)
            ->first();

        if (!$estudiante) {
            \Log::error('Estudiante no encontrado para email: ' . $user->email);
            return redirect()->route('estudiantes.mis-cursos')
                ->with('error', 'No se encontró información del estudiante.');
        }

        \Log::info('Estudiante encontrado ID: ' . $estudiante->id);

        // Verificar que esté inscrito en el curso
        $inscripcion = $estudiante->inscripciones()
            ->where('curso_id', $curso->id)
            ->where('estado', 'confirmada')
            ->first();

        if (!$inscripcion) {
            \Log::error('No está inscrito en el curso ' . $curso->id);
            return redirect()->route('estudiantes.mis-cursos')
                ->with('error', 'No estás inscrito en este curso.');
        }

        \Log::info('Inscripción encontrada ID: ' . $inscripcion->id);

        // Obtener evaluaciones del curso con las calificaciones del estudiante
        $evaluaciones = \App\Models\Evaluacion::where('curso_id', $curso->id)
            ->where('activo', true)
            ->with(['calificaciones' => function($q) use ($inscripcion) {
                $q->where('inscripcion_id', $inscripcion->id);
            }])
            ->get();

        \Log::info('Evaluaciones encontradas: ' . $evaluaciones->count());

       // Obtener asistencias del estudiante
$asistencias = \App\Models\Asistencia::where('inscripcion_id', $inscripcion->id)
    ->orderBy('fecha_sesion', 'desc')
    ->get();

        \Log::info('Asistencias encontradas: ' . $asistencias->count());

        // Calcular estadísticas
        $totalAsistencias = $asistencias->count();
        $asistenciasPresente = $asistencias->where('estado', 'presente')->count();
        $porcentajeAsistencia = $totalAsistencias > 0 ? ($asistenciasPresente / $totalAsistencias) * 100 : 0;

        // Calcular promedio de calificaciones
        $calificaciones = \App\Models\Calificacion::where('inscripcion_id', $inscripcion->id)->get();
        $promedioFinal = $calificaciones->count() > 0 ? $calificaciones->avg('nota') : 0;

        \Log::info('Renderizando vista...');

        return view('estudiantes.curso-detalle', compact(
            'curso',
            'inscripcion',
            'evaluaciones',
            'asistencias',
            'porcentajeAsistencia',
            'promedioFinal',
            'totalAsistencias',
            'asistenciasPresente'
        ));

    } catch (\Exception $e) {
        \Log::error('Error en cursoDetalle: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        return redirect()->route('estudiantes.mis-cursos')
            ->with('error', 'Error al cargar el detalle del curso: ' . $e->getMessage());
    }
}
/**
 * Mis inscripciones (para rol Estudiante)
 */
public function misInscripciones()
{
    $user = Auth::user();
    
    if (!$user->hasRole('Estudiante')) {
        abort(403, 'Acceso denegado.');
    }

    // ✅ Buscar estudiante por email del usuario
    $estudiante = Estudiante::where('correo_institucional', $user->email)->first();

    if (!$estudiante) {
        return view('estudiantes.mis-inscripciones')->with([
            'mensaje' => 'No se encontró un perfil de estudiante asociado a tu cuenta.',
            'inscripciones' => collect(),
            'estadisticas' => [
                'total' => 0,
                'confirmadas' => 0,
                'pendientes' => 0,
                'canceladas' => 0,
            ],
        ]);
    }

    $inscripciones = $estudiante->inscripciones()
        ->with(['curso'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $estadisticas = [
        'total' => $estudiante->inscripciones()->count(),
        'confirmadas' => $estudiante->inscripciones()->where('estado', 'confirmada')->count(),
        'pendientes' => $estudiante->inscripciones()->where('estado', 'provisional')->count(),
        'canceladas' => $estudiante->inscripciones()->where('estado', 'cancelada')->count(),
    ];

    return view('estudiantes.mis-inscripciones', compact('inscripciones', 'estadisticas', 'estudiante'));
}

/**
 * Mis certificados (para rol Estudiante)
 */
public function misCertificados()
{
    $user = Auth::user();
    
    if (!$user->hasRole('Estudiante')) {
        abort(403, 'Acceso denegado.');
    }

    $estudiante = Estudiante::where('correo_institucional', $user->email)->first();

    if (!$estudiante) {
        return view('estudiantes.mis-certificados')->with([
            'mensaje' => 'No se encontró un perfil de estudiante asociado a tu cuenta.',
            'certificados' => collect(),
            'estadisticas' => [
                'total_certificados' => 0,
                'certificados_descargados' => 0,
            ],
        ]);
    }

    $certificados = \App\Models\Certificado::whereHas('inscripcion', function($query) use ($estudiante) {
        $query->where('estudiante_id', $estudiante->id);
    })
    ->with(['inscripcion.curso', 'inscripcion'])  // ← AQUÍ ESTÁ EL CAMBIO
    ->orderBy('created_at', 'desc')
    ->get();

    $estadisticas = [
        'total_certificados' => $certificados->count(),
        'certificados_descargados' => $certificados->sum('numero_veces_descargado'),
    ];

    return view('estudiantes.mis-certificados', compact('certificados', 'estadisticas', 'estudiante'));
}
/**
 * Mostrar cursos disponibles para inscripción
 *//**
 * Mostrar cursos disponibles para inscripción
 */
public function cursosDisponibles()
{
    $user = Auth::user();
    
    // Obtener estudiante
    $estudiante = Estudiante::where('correo_institucional', $user->email)
        ->orWhere('correo_personal', $user->email)
        ->first();

    if (!$estudiante) {
        return redirect()->route('dashboard')
            ->with('error', 'No se encontró información del estudiante.');
    }

    // Obtener cursos en convocatoria (SIN estado_publicacion)
    $cursos = Curso::where('estado', 'convocatoria')
        ->with(['categoria', 'modalidad'])
        ->withCount('inscripciones')
        ->get();

    // Verificar en qué cursos ya está inscrito
    $cursosInscritos = $estudiante->inscripciones()
        ->whereIn('estado', ['confirmada', 'provisional'])
        ->pluck('curso_id')
        ->toArray();

    return view('estudiantes.cursos-disponibles', compact('cursos', 'cursosInscritos', 'estudiante'));
}

/**
 * Mostrar formulario de inscripción
 */
public function mostrarInscripcion(Curso $curso)
{
    $user = Auth::user();
    
    // Obtener estudiante
    $estudiante = Estudiante::where('correo_institucional', $user->email)
        ->orWhere('correo_personal', $user->email)
        ->first();

    if (!$estudiante) {
        return redirect()->route('dashboard')
            ->with('error', 'No se encontró información del estudiante.');
    }

    // Verificar que el curso esté en convocatoria
    if ($curso->estado !== 'convocatoria') {
        return redirect()->route('estudiantes.cursos-disponibles')
            ->with('error', 'Este curso no está disponible para inscripción.');
    }

    // Verificar si ya está inscrito
    $yaInscrito = $estudiante->inscripciones()
        ->where('curso_id', $curso->id)
        ->whereIn('estado', ['confirmada', 'provisional'])
        ->exists();

    if ($yaInscrito) {
        return redirect()->route('estudiantes.mis-cursos')
            ->with('warning', 'Ya estás inscrito en este curso.');
    }

    // Verificar cupos disponibles
    $inscritosCount = $curso->inscripciones()
        ->where('estado', 'confirmada')
        ->count();

    $cuposDisponibles = $curso->cupos_maximos - $inscritosCount;

    return view('estudiantes.inscripcion-form', compact('curso', 'estudiante', 'cuposDisponibles'));
}

/**
 * Procesar inscripción directa (sin pago previo)
 */
public function inscribirse(Request $request, Curso $curso)
{
    $user = Auth::user();
    
    // Obtener estudiante
    $estudiante = Estudiante::where('correo_institucional', $user->email)
        ->orWhere('correo_personal', $user->email)
        ->first();

    if (!$estudiante) {
        return redirect()->route('dashboard')
            ->with('error', 'No se encontró información del estudiante.');
    }

    // Validar
    $request->validate([
        'acepta_terminos' => 'required|accepted'
    ], [
        'acepta_terminos.required' => 'Debes aceptar los términos y condiciones',
        'acepta_terminos.accepted' => 'Debes aceptar los términos y condiciones'
    ]);

    // Verificar que el curso esté disponible
    if ($curso->estado !== 'convocatoria') {
        return redirect()->route('estudiantes.cursos-disponibles')
            ->with('error', 'Este curso no está disponible para inscripción.');
    }

    // Verificar si ya está inscrito
    $yaInscrito = $estudiante->inscripciones()
        ->where('curso_id', $curso->id)
        ->whereIn('estado', ['confirmada', 'provisional'])
        ->exists();

    if ($yaInscrito) {
        return redirect()->route('estudiantes.mis-cursos')
            ->with('error', 'Ya estás inscrito en este curso.');
    }

    // Verificar cupos
   // Verificar cupos
// Verificar cupos
$inscritosCount = $curso->inscripciones()->where('estado', 'confirmada')->count();

if ($inscritosCount >= $curso->cupo_maximo) {
    return redirect()->route('estudiantes.cursos-disponibles')
        ->with('error', 'No hay cupos disponibles en este curso.');
}

    DB::beginTransaction();
    
    try {
        // Crear inscripción DIRECTA (sin pago)
        // Generar código único de inscripción
$codigoInscripcion = 'INS-' . date('Y') . '-' . str_pad($estudiante->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($curso->id, 3, '0', STR_PAD_LEFT);

// Crear inscripción DIRECTA (sin pago)
$inscripcion = \App\Models\Inscripcion::create([
    'codigo_inscripcion' => $codigoInscripcion,
    'curso_id' => $curso->id,
    'estudiante_id' => $estudiante->id,
    'fecha_inscripcion' => now(),
    'estado' => 'confirmada',
    'observaciones' => 'Inscripción directa - Acceso libre a clases'
]);

        Log::info('Inscripción creada', [
            'inscripcion_id' => $inscripcion->id,
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'tipo' => 'directa_sin_pago'
        ]);

        DB::commit();

        return redirect()->route('estudiantes.mis-cursos')
            ->with('success', '¡Inscripción exitosa! Ya puedes acceder a las clases del curso.');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Error al crear inscripción: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Error al procesar la inscripción. Intenta nuevamente.');
    }
}
/**
 * Marcar asistencia a una sesión
 */
public function marcarAsistencia($sesionId)
{
    $user = Auth::user();
    
    // Obtener estudiante
    $estudiante = Estudiante::where('correo_institucional', $user->email)
        ->orWhere('correo_personal', $user->email)
        ->first();

    if (!$estudiante) {
        return redirect()->back()
            ->with('error', 'No se encontró información del estudiante.');
    }

    // Obtener sesión
    $sesion = \App\Models\SesionCurso::findOrFail($sesionId);

    // Verificar que el estudiante esté inscrito en el curso
    $inscripcion = $estudiante->inscripciones()
        ->where('curso_id', $sesion->curso_id)
        ->where('estado', 'confirmada')
        ->first();

    if (!$inscripcion) {
        return redirect()->back()
            ->with('error', 'No estás inscrito en este curso.');
    }

    // Verificar si puede marcar asistencia
    if (!$sesion->puedeMarcarAsistencia()) {
        return redirect()->back()
            ->with('error', 'No puedes marcar asistencia en este momento.');
    }

    // Verificar si ya marcó asistencia
    $asistenciaExistente = \App\Models\Asistencia::where('inscripcion_id', $inscripcion->id)
        ->where('curso_id', $sesion->curso_id)
        ->where('numero_sesion', $sesion->numero_sesion)
        ->first();

    if ($asistenciaExistente) {
        return redirect()->back()
            ->with('warning', 'Ya marcaste tu asistencia para esta sesión.');
    }

    DB::beginTransaction();
    
    try {
        // Crear registro de asistencia
        \App\Models\Asistencia::create([
            'inscripcion_id' => $inscripcion->id,
            'curso_id' => $sesion->curso_id,
            'numero_sesion' => $sesion->numero_sesion,
            'fecha_sesion' => $sesion->fecha_sesion,
            'hora_registro' => now()->format('H:i:s'),
            'estado' => 'presente',
            'observaciones' => 'Asistencia marcada por el estudiante'
        ]);

        Log::info('Asistencia marcada', [
            'estudiante_id' => $estudiante->id,
            'sesion_id' => $sesion->id,
            'curso_id' => $sesion->curso_id
        ]);

        DB::commit();

        return redirect()->back()
            ->with('success', '✅ Asistencia marcada correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Error al marcar asistencia: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Error al marcar la asistencia. Intenta nuevamente.');
    }
}

public function iniciarEvaluacion($evaluacionId)
{
    $user = Auth::user();
    
    // Obtener estudiante
    $estudiante = Estudiante::where('correo_institucional', $user->email)
        ->orWhere('correo_personal', $user->email)
        ->first();

    if (!$estudiante) {
        return redirect()->back()
            ->with('error', 'No se encontró información del estudiante.');
    }

    // Obtener evaluación
    $evaluacion = \App\Models\Evaluacion::with('curso')->findOrFail($evaluacionId);

    // Verificar que el estudiante esté inscrito en el curso
    $inscripcion = $estudiante->inscripciones()
        ->where('curso_id', $evaluacion->curso_id)
        ->where('estado', 'confirmada')
        ->first();

    if (!$inscripcion) {
        return redirect()->back()
            ->with('error', 'No estás inscrito en este curso.');
    }

    // Validar que la evaluación esté activa
    if (!$evaluacion->activo) {
        return redirect()->back()
            ->with('error', 'Esta evaluación no está activa.');
    }

    // Validar fechas
    $ahora = now();
    
    if ($evaluacion->fecha_disponible && $ahora->lt(\Carbon\Carbon::parse($evaluacion->fecha_disponible))) {
        return redirect()->back()
            ->with('error', 'Esta evaluación aún no está disponible.');
    }

    if ($evaluacion->fecha_limite && $ahora->gt(\Carbon\Carbon::parse($evaluacion->fecha_limite))) {
        return redirect()->back()
            ->with('error', 'Esta evaluación ya venció.');
    }

    // Verificar intentos previos
    $intentosPrevios = \App\Models\IntentoEvaluacion::where('evaluacion_id', $evaluacion->id)
        ->where('inscripcion_id', $inscripcion->id)
        ->count();

    if ($intentosPrevios >= $evaluacion->numero_intentos_permitidos) {
        return redirect()->back()
            ->with('error', 'Ya has utilizado todos los intentos permitidos para esta evaluación.');
    }

    // Verificar si hay un intento en progreso
    $intentoEnProgreso = \App\Models\IntentoEvaluacion::where('evaluacion_id', $evaluacion->id)
        ->where('inscripcion_id', $inscripcion->id)
        ->where('estado', 'en_progreso')
        ->first();

    if ($intentoEnProgreso) {
        // Redirigir a continuar el intento existente
        return redirect()->route('estudiantes.evaluacion.resolver', $intentoEnProgreso->id);
    }

    DB::beginTransaction();
    
    try {
        // Crear nuevo intento
        $numeroIntento = $intentosPrevios + 1;
        
        $intento = \App\Models\IntentoEvaluacion::create([
            'inscripcion_id' => $inscripcion->id,
            'evaluacion_id' => $evaluacion->id,
            'numero_intento' => $numeroIntento,
            'fecha_inicio' => now(),
            'fecha_fin' => null,
            'tiempo_total_segundos' => 0,
            'nota_obtenida' => 0,
            'puntos_totales' => 0,
            'puntos_obtenidos' => 0,
            'estado' => 'en_progreso',
            'ip_address' => request()->ip()
        ]);

        Log::info('Intento de evaluación iniciado', [
            'estudiante_id' => $estudiante->id,
            'evaluacion_id' => $evaluacion->id,
            'intento_id' => $intento->id,
            'numero_intento' => $numeroIntento
        ]);

        DB::commit();

        // Redirigir a la vista de resolver evaluación
        return redirect()->route('estudiantes.evaluacion.resolver', $intento->id)
            ->with('success', 'Evaluación iniciada. ¡Buena suerte!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Error al iniciar evaluación: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Error al iniciar la evaluación. Intenta nuevamente.');
    }
}
/**
 * Mostrar vista para resolver evaluación
 */
public function resolverEvaluacion($intentoId)
{
    $user = Auth::user();
    
    // Obtener estudiante
    $estudiante = Estudiante::where('correo_institucional', $user->email)
        ->orWhere('correo_personal', $user->email)
        ->first();

    if (!$estudiante) {
        return redirect()->route('estudiantes.mis-cursos')
            ->with('error', 'No se encontró información del estudiante.');
    }

    // Obtener intento con relaciones
    $intento = \App\Models\IntentoEvaluacion::with([
        'evaluacion.preguntas.opciones',
        'inscripcion',
        'respuestas'
    ])->findOrFail($intentoId);

    // Verificar que el intento pertenece al estudiante
    if ($intento->inscripcion->estudiante_id !== $estudiante->id) {
        abort(403, 'No tienes permiso para acceder a esta evaluación.');
    }

    // Verificar que el intento esté en progreso
    if ($intento->estado !== 'en_progreso') {
        return redirect()->route('estudiantes.evaluacion.resultado', $intento->id)
            ->with('info', 'Esta evaluación ya fue finalizada.');
    }

    // Verificar si el tiempo expiró
    if ($intento->haExpirado()) {
        // Auto-finalizar
        return redirect()->route('estudiantes.evaluacion.finalizar', $intento->id);
    }

    $evaluacion = $intento->evaluacion;
    
    // Obtener preguntas (aleatorizadas si está configurado)
    $preguntas = $evaluacion->preguntas()
        ->with('opciones')
        ->orderBy('orden')
        ->get();

    if ($evaluacion->aleatorizar_preguntas) {
        $preguntas = $preguntas->shuffle();
    }

    // Calcular tiempo restante en segundos
    $tiempoRestanteSegundos = $intento->getTiempoRestanteSegundos();

    // Obtener respuestas ya guardadas
    $respuestasGuardadas = $intento->respuestas()
        ->get()
        ->keyBy('pregunta_id');

    return view('estudiantes.evaluaciones.resolver', compact(
        'intento',
        'evaluacion',
        'preguntas',
        'tiempoRestanteSegundos',
        'respuestasGuardadas'
    ));
}
public function guardarRespuesta(Request $request, $intentoId)
{
    $user = Auth::user();
    
    // Obtener estudiante
    $estudiante = Estudiante::where('correo_institucional', $user->email)
        ->orWhere('correo_personal', $user->email)
        ->first();

    if (!$estudiante) {
        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }

    // Obtener intento
    $intento = \App\Models\IntentoEvaluacion::with('inscripcion')
        ->findOrFail($intentoId);

    // Verificar que el intento pertenece al estudiante
    if ($intento->inscripcion->estudiante_id !== $estudiante->id) {
        return response()->json([
            'success' => false,
            'message' => 'No autorizado'
        ], 403);
    }

    // Verificar que el intento esté en progreso
    if ($intento->estado !== 'en_progreso') {
        return response()->json([
            'success' => false,
            'message' => 'El intento ya fue finalizado'
        ], 400);
    }

    // Validar datos recibidos
    $validated = $request->validate([
        'pregunta_id' => 'required|exists:preguntas_evaluacion,id',
        'opcion_id' => 'nullable|exists:opciones_pregunta,id',
        'respuesta_texto' => 'nullable|string|max:1000'
    ]);

    try {
        DB::beginTransaction();

        // Buscar o crear respuesta
        $respuesta = \App\Models\RespuestaEvaluacion::updateOrCreate(
            [
                'intento_id' => $intento->id,
                'pregunta_id' => $validated['pregunta_id']
            ],
            [
                'inscripcion_id' => $intento->inscripcion_id,
                'evaluacion_id' => $intento->evaluacion_id,
                'opcion_id' => $validated['opcion_id'] ?? null,
                'respuesta_texto' => $validated['respuesta_texto'] ?? null,
                'fecha_respuesta' => now()
                // NO calculamos es_correcta ni puntos_obtenidos hasta que finalice
            ]
        );

        DB::commit();

        Log::info('Respuesta guardada temporalmente', [
            'intento_id' => $intento->id,
            'pregunta_id' => $validated['pregunta_id'],
            'respuesta_id' => $respuesta->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Respuesta guardada correctamente',
            'respuesta_id' => $respuesta->id
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Error al guardar respuesta: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al guardar la respuesta'
        ], 500);
    }
}

public function finalizarEvaluacion(Request $request, $intentoId)
{
    try {
        DB::beginTransaction();
        
        // 🔍 Buscar el intento
        $intento = IntentoEvaluacion::with([
            'evaluacion.preguntas.opciones',
            'respuestas.pregunta',
            'respuestas.opcion',
            'inscripcion'
        ])->findOrFail($intentoId);
        
        // 🛡️ Verificar que pertenece al estudiante autenticado
        $estudiante = Estudiante::where('user_id', auth()->id())->firstOrFail();
        if ($intento->inscripcion->estudiante_id !== $estudiante->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para finalizar esta evaluación'
            ], 403);
        }
        
        // 🛡️ Verificar que no esté ya finalizado
        if ($intento->estado === 'finalizado') {
            return response()->json([
                'success' => false,
                'message' => 'Esta evaluación ya fue finalizada'
            ], 400);
        }
        
        // ⏱️ Calcular tiempo total
        $tiempoTotal = now()->diffInSeconds($intento->fecha_inicio);
        
        // 💾 GUARDAR RESPUESTAS DEL FORMULARIO
if ($request->has('respuestas')) {
    foreach ($request->respuestas as $preguntaId => $valor) {
        \App\Models\RespuestaEvaluacion::updateOrCreate(
            [
                'intento_id' => $intento->id,
                'pregunta_id' => $preguntaId
            ],
            [
                'inscripcion_id' => $intento->inscripcion_id,
                'evaluacion_id' => $intento->evaluacion_id,
                'opcion_id' => $valor,
                'fecha_respuesta' => now()
            ]
        );
    }
}

// 📊 Calificar evaluación
$resultado = $this->calificarEvaluacion($intento);
        
        // 💾 Actualizar intento
        $intento->update([
            'fecha_fin' => now(),
            'tiempo_total_segundos' => $tiempoTotal,
            'nota_obtenida' => $resultado['nota'],
            'puntos_totales' => $resultado['puntos_totales'],
            'puntos_obtenidos' => $resultado['puntos_obtenidos'],
            'estado' => 'finalizado'
        ]);
        
        // 📝 Registrar o actualizar calificación
        Calificacion::updateOrCreate(
            [
                'inscripcion_id' => $intento->inscripcion_id,
                'evaluacion_id' => $intento->evaluacion_id
            ],
            [
                'nota' => $resultado['nota'],
                'observaciones' => "Evaluación finalizada automáticamente. Intento #{$intento->numero_intento}",
                'fecha_registro' => now()
            ]
        );
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Evaluación finalizada correctamente',
            'data' => [
                'nota' => $resultado['nota'],
                'puntos_obtenidos' => $resultado['puntos_obtenidos'],
                'puntos_totales' => $resultado['puntos_totales'],
                'porcentaje' => round(($resultado['puntos_obtenidos'] / $resultado['puntos_totales']) * 100, 2),
                'tiempo_total' => gmdate('H:i:s', $tiempoTotal),
                'redirect' => route('estudiantes.evaluacion.resultado', $intento->id)
            ]
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al finalizar evaluación: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al finalizar la evaluación: ' . $e->getMessage()
        ], 500);
    }
}



/**
 * 🧮 Calificar evaluación según tipo de preguntas
 */
private function calificarEvaluacion($intento)
{
    $puntosObtenidos = 0;
    $puntosTotales = 0;
    
    foreach ($intento->evaluacion->preguntas as $pregunta) {
        $puntosTotales += $pregunta->puntos;
        
        // Buscar respuesta del estudiante
        $respuesta = $intento->respuestas()
            ->where('pregunta_id', $pregunta->id)
            ->first();
        
        if (!$respuesta) {
            continue; // Pregunta sin responder
        }
        
        $esCorrecta = false;
        
        // 📝 Calificar según tipo de pregunta
        switch ($pregunta->tipo_pregunta) {
            case 'multiple':
            case 'verdadero_falso':
                // Verificar si la opción seleccionada es correcta
                if ($respuesta->opcion_id) {
    $opcionSeleccionada = $pregunta->opciones()
        ->where('id', $respuesta->opcion_id)
        ->first();
                    
                    $esCorrecta = $opcionSeleccionada && $opcionSeleccionada->es_correcta;
                }
                break;
            
            case 'respuesta_corta':
                // Comparar texto (sin importar mayúsculas/minúsculas ni espacios extras)
                $respuestaEstudiante = trim(strtolower($respuesta->respuesta_texto ?? ''));
                $respuestaCorrecta = trim(strtolower($pregunta->respuesta_correcta ?? ''));
                
                $esCorrecta = $respuestaEstudiante === $respuestaCorrecta;
                break;
        }
        
        // 💰 Asignar puntos
        $puntosGanados = $esCorrecta ? $pregunta->puntos : 0;
        $puntosObtenidos += $puntosGanados;
        
        // 💾 Actualizar respuesta
        $respuesta->update([
            'es_correcta' => $esCorrecta,
            'puntos_obtenidos' => $puntosGanados
        ]);
    }
    
    // 📊 Calcular nota sobre 20
    $notaSobre20 = $puntosTotales > 0 
        ? round(($puntosObtenidos / $puntosTotales) * 20, 2)
        : 0;
    
    return [
        'nota' => $notaSobre20,
        'puntos_obtenidos' => $puntosObtenidos,
        'puntos_totales' => $puntosTotales
    ];
}
public function verResultado($intentoId)
{
    $intento = IntentoEvaluacion::with([
        'evaluacion.preguntas.opciones',
        'respuestas.pregunta.opciones',
        'respuestas.opcion',
        'inscripcion.curso'
    ])->findOrFail($intentoId);
    
    // 🛡️ Verificar que pertenece al estudiante
    $estudiante = Estudiante::where('user_id', auth()->id())->firstOrFail();
    if ($intento->inscripcion->estudiante_id !== $estudiante->id) {
        abort(403, 'No tienes permiso para ver este resultado');
    }
    
    // 🛡️ Verificar que esté finalizado
    if ($intento->estado !== 'finalizado') {
        return redirect()
            ->route('estudiantes.evaluacion.resolver', $intento->id)
            ->with('warning', 'Debes finalizar la evaluación primero');
    }
    
    // 📊 Calcular estadísticas
    $totalPreguntas = $intento->evaluacion->preguntas->count();
    $preguntasCorrectas = $intento->respuestas->where('es_correcta', true)->count();
    $porcentajeAciertos = $totalPreguntas > 0 
        ? round(($preguntasCorrectas / $totalPreguntas) * 100, 2)
        : 0;
    
    return view('estudiantes.evaluacion-resultado', compact(
        'intento',
        'totalPreguntas',
        'preguntasCorrectas',
        'porcentajeAciertos'
    ));
}
public function mostrarPago($inscripcion)
{
    // Obtener la inscripción
    $inscripcion = Inscripcion::findOrFail($inscripcion);
    
    // Verificar que pertenece al estudiante autenticado
    $estudiante = auth()->user()->estudiante;
    
    if (!$estudiante || $inscripcion->estudiante_id !== $estudiante->id) {
        abort(403, 'No tienes permiso para ver este pago.');
    }
    
    return view('estudiantes.pagos.mostrar', compact('inscripcion'));
}
public function registrarPago(Request $request, $inscripcion)
{
    try {
        // 1. Obtener inscripción
        $inscripcion = Inscripcion::findOrFail($inscripcion);
        
        // 2. Obtener estudiante
        $estudiante = auth()->user()->estudiante;
        
        if (!$estudiante || $inscripcion->estudiante_id !== $estudiante->id) {
            return redirect()->back()->with('error', 'No tienes permiso para este pago.');
        }

        // 3. Validar código
        $validated = $request->validate([
            'codigo_validacion' => 'required|regex:/^[0-9]{6}$/',
        ], [
            'codigo_validacion.required' => 'El código es requerido.',
            'codigo_validacion.regex' => 'Debe ser 6 dígitos.',
        ]);

        \Log::info('Pago iniciado', ['inscripcion_id' => $inscripcion->id, 'codigo' => $validated['codigo_validacion']]);

        // 4. Crear pago
        $pago = new Pago();
        $pago->inscripcion_id = $inscripcion->id;
        $pago->metodo_pago_id = 1;
        $pago->numero_operacion = $validated['codigo_validacion'];
        $pago->monto = $inscripcion->curso->costo_inscripcion;
        $pago->estado = 'pendiente';  // ✓ Usar 'pendiente' en lugar de 'pendiente_confirmacion'
        $pago->observaciones = 'Pago Yape - Código: ' . $validated['codigo_validacion'];
        $pago->fecha_pago = now();
        $pago->save();

        \Log::info('Pago guardado', ['pago_id' => $pago->id]);

        return redirect()->route('estudiantes.mis-cursos')
            ->with('success', '✓ Pago registrado. Será confirmado próximamente.');

    } catch (\Exception $e) {
        \Log::error('Error pago: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Error: ' . $e->getMessage())
            ->withInput();
    }
}
public function misPagos()
{
    $estudiante = auth()->user()->estudiante;
    
    if (!$estudiante) {
        return redirect()->route('dashboard')
            ->with('error', 'No se encontró información del estudiante.');
    }
    
    $pagos = Pago::whereHas('inscripcion', function($q) use ($estudiante) {
        $q->where('estudiante_id', $estudiante->id);
    })->with(['inscripcion.curso', 'metodoPago'])->get();
    
    return view('estudiantes.pagos.index', compact('pagos'));
}

}