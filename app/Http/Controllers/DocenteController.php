<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // abort_unless(Auth::user()->can('docentes.index'), 403); // ✅ COMENTADO

        try {
            $query = Docente::with(['user', 'asignaciones.curso'])
                ->withCount(['asignaciones as cursos_activos' => function ($q) {
                    $q->where('activo', true);
                }]);

            // 🔍 Búsqueda avanzada
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('dni', 'like', "%{$search}%")
                        ->orWhere('nombres', 'like', "%{$search}%")
                        ->orWhere('apellidos', 'like', "%{$search}%")
                        ->orWhere('correo_institucional', 'like', "%{$search}%")
                        ->orWhere('correo_personal', 'like', "%{$search}%");
                });
            }

            // 📊 Filtro por estado
            if ($request->filled('estado')) {
                $query->where('activo', $request->estado);
            }

            // 🎓 Filtro por especialidad
            if ($request->filled('especialidad')) {
                $query->where('especialidades', 'like', "%{$request->especialidad}%");
            }

            // 🔢 Ordenamiento
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            
            $allowedSorts = ['dni', 'nombres', 'apellidos', 'created_at', 'correo_institucional'];
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $docentes = $query->paginate(15)->withQueryString();

            // 📈 Estadísticas
            $stats = [
                'total' => Docente::count(),
                'activos' => Docente::where('activo', true)->count(),
                'inactivos' => Docente::where('activo', false)->count(),
                'con_asignaciones' => Docente::whereHas('asignaciones', function ($q) {
                    $q->where('activo', true);
                })->count(),
            ];

            return view('docentes.index', compact('docentes', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error en DocenteController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la lista de docentes.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // abort_unless(Auth::user()->can('docentes.create'), 403); // ✅ COMENTADO

        return view('docentes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // abort_unless(Auth::user()->can('docentes.store'), 403); // ✅ COMENTADO

        // ✅ Validación inline (sin DocenteRequest)
        $validated = $request->validate([
            'dni' => 'required|string|size:8|regex:/^[0-9]{8}$/|unique:docentes,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'correo_institucional' => 'required|email|max:100|unique:docentes,correo_institucional|unique:users,email',
            'correo_personal' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'nullable|date|before:-18 years',
            'sexo' => 'nullable|in:M,F',
            'direccion' => 'nullable|string|max:255',
            'formacion_academica' => 'nullable|string',
            'experiencia_profesional' => 'nullable|string',
            'especialidades' => 'nullable|string',
            'curriculum_vitae' => 'nullable|file|mimes:pdf|max:5120',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // 👤 Crear usuario
            $user = User::create([
                'name' => trim($validated['nombres'] . ' ' . $validated['apellidos']),
                'email' => $validated['correo_institucional'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);

            // 🎭 Asignar rol de Docente
            $user->assignRole('Docente');

            // 📄 Procesar CV si existe
            $cvPath = null;
            if ($request->hasFile('curriculum_vitae')) {
                $cvPath = $request->file('curriculum_vitae')->store('docentes/cv', 'public');
            }

            // 🎓 Crear docente
            $docente = Docente::create([
                'user_id' => $user->id,
                'dni' => $validated['dni'],
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'sexo' => $validated['sexo'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'correo_personal' => $validated['correo_personal'] ?? null,
                'correo_institucional' => $validated['correo_institucional'],
                'formacion_academica' => $validated['formacion_academica'] ?? null,
                'experiencia_profesional' => $validated['experiencia_profesional'] ?? null,
                'especialidades' => $validated['especialidades'] ?? null,
                'curriculum_vitae' => $cvPath,
                'activo' => true,
            ]);

            DB::commit();

            Log::info("Docente creado: {$docente->id} - {$docente->nombres} {$docente->apellidos}");

            return redirect()->route('docentes.index')
                ->with('success', '✅ Docente creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear docente: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al crear el docente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Docente $docente)
    {
        // abort_unless(Auth::user()->can('docentes.show'), 403); // ✅ COMENTADO

        try {
            $docente->load([
                'user',
                'asignaciones' => function ($query) {
                    $query->with('curso')->orderBy('created_at', 'desc');
                },
                'asignaciones.curso.inscripciones',
            ]);

            // 📊 Estadísticas del docente
            $stats = [
                'total_cursos' => $docente->asignaciones->count(),
                'cursos_activos' => $docente->asignaciones->where('activo', true)->count(),
                'cursos_finalizados' => $docente->asignaciones->where('activo', false)->count(),
                'total_estudiantes' => $docente->asignaciones->sum(function ($asignacion) {
                    return $asignacion->curso->inscripciones->count();
                }),
            ];

            // 📅 Cursos actuales
            $cursosActuales = $docente->asignaciones()
                ->where('activo', true)
                ->with(['curso.inscripciones.estudiante'])
                ->get();

            // 📜 Historial de cursos
            $historialCursos = $docente->asignaciones()
                ->where('activo', false)
                ->with('curso')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('docentes.show', compact('docente', 'stats', 'cursosActuales', 'historialCursos'));

        } catch (\Exception $e) {
            Log::error('Error en DocenteController@show: ' . $e->getMessage());
            return redirect()->route('docentes.index')
                ->with('error', 'Error al cargar la información del docente.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Docente $docente)
    {
        // abort_unless(Auth::user()->can('docentes.edit'), 403); // ✅ COMENTADO

        return view('docentes.edit', compact('docente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Docente $docente)
    {
        // abort_unless(Auth::user()->can('docentes.update'), 403); // ✅ COMENTADO

        // ✅ Validación inline
        $validated = $request->validate([
            'dni' => 'required|string|size:8|regex:/^[0-9]{8}$/|unique:docentes,dni,' . $docente->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'correo_institucional' => 'required|email|max:100|unique:docentes,correo_institucional,' . $docente->id . '|unique:users,email,' . $docente->user_id,
            'correo_personal' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'nullable|date|before:-18 years',
            'sexo' => 'nullable|in:M,F',
            'direccion' => 'nullable|string|max:255',
            'formacion_academica' => 'nullable|string',
            'experiencia_profesional' => 'nullable|string',
            'especialidades' => 'nullable|string',
            'activo' => 'required|boolean',
            'curriculum_vitae' => 'nullable|file|mimes:pdf|max:5120',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // 📄 Procesar nuevo CV si existe
            if ($request->hasFile('curriculum_vitae')) {
                // Eliminar CV anterior si existe
                if ($docente->curriculum_vitae) {
                    Storage::disk('public')->delete($docente->curriculum_vitae);
                }
                $validated['curriculum_vitae'] = $request->file('curriculum_vitae')
                    ->store('docentes/cv', 'public');
            }

            // 🔄 Actualizar docente
            $docente->update($validated);

            // 👤 Actualizar usuario relacionado
            $docente->user->update([
                'name' => trim($validated['nombres'] . ' ' . $validated['apellidos']),
                'email' => $validated['correo_institucional'],
            ]);

            // 🔑 Actualizar contraseña si se proporcionó
            if (isset($validated['password'])) {
                $docente->user->update([
                    'password' => Hash::make($validated['password'])
                ]);
            }

            DB::commit();

            Log::info("Docente actualizado: {$docente->id} - {$docente->nombres} {$docente->apellidos}");

            return redirect()->route('docentes.index')
                ->with('success', '✅ Docente actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar docente: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar el docente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Docente $docente)
    {
        // abort_unless(Auth::user()->can('docentes.destroy'), 403); // ✅ COMENTADO

        DB::beginTransaction();

        try {
            // ✅ Verificar que no tenga asignaciones activas
            $asignacionesActivas = $docente->asignaciones()->where('activo', true)->count();
            
            if ($asignacionesActivas > 0) {
                return redirect()->route('docentes.index')
                    ->with('error', '❌ No se puede eliminar un docente con ' . $asignacionesActivas . ' asignación(es) activa(s).');
            }

            // 📄 Eliminar CV si existe
            if ($docente->curriculum_vitae) {
                Storage::disk('public')->delete($docente->curriculum_vitae);
            }

            // 🗑️ Eliminar usuario y docente
            $userId = $docente->user_id;
            $nombreCompleto = $docente->nombres . ' ' . $docente->apellidos;
            
            $docente->delete();
            User::find($userId)?->delete();

            DB::commit();

            Log::warning("Docente eliminado: {$nombreCompleto}");

            return redirect()->route('docentes.index')
                ->with('success', '✅ Docente eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar docente: ' . $e->getMessage());
            
            return redirect()->route('docentes.index')
                ->with('error', '❌ Error al eliminar el docente: ' . $e->getMessage());
        }
    }

    /**
     * 🔄 Cambiar estado del docente (activar/desactivar)
     */
    public function toggleStatus(Docente $docente)
    {
        // abort_unless(Auth::user()->can('docentes.edit'), 403); // ✅ COMENTADO

        try {
            $docente->update(['activo' => !$docente->activo]);

            $estado = $docente->activo ? 'activado' : 'desactivado';
            
            Log::info("Docente {$estado}: {$docente->id}");

            return redirect()->back()
                ->with('success', "✅ Docente {$estado} exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del docente: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al cambiar el estado del docente.');
        }
    }

    /**
     * 📊 Reporte de carga académica del docente
     */
    public function cargaAcademica(Docente $docente)
    {
        // abort_unless(Auth::user()->can('reportes.academicos'), 403); // ✅ COMENTADO

        try {
            $cursos = $docente->asignaciones()
                ->where('activo', true)
                ->with([
                    'curso.inscripciones' => function ($query) {
                        $query->where('estado', 'confirmada');
                    },
                    'curso.modalidad',
                    'curso.categoria'
                ])
                ->get();

            $totalHoras = $cursos->sum(function ($asignacion) {
                return $asignacion->curso->duracion_horas ?? 0;
            });

            $totalEstudiantes = $cursos->sum(function ($asignacion) {
                return $asignacion->curso->inscripciones->count();
            });

            return view('reportes.carga-docente', compact('docente', 'cursos', 'totalHoras', 'totalEstudiantes'));

        } catch (\Exception $e) {
            Log::error('Error en reporte de carga académica: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al generar el reporte.');
        }
    }

    /**
     * 📥 Descargar CV del docente
     */
    public function descargarCV(Docente $docente)
    {
        // abort_unless(Auth::user()->can('docentes.show'), 403); // ✅ COMENTADO

        try {
            if (!$docente->curriculum_vitae || !Storage::disk('public')->exists($docente->curriculum_vitae)) {
                return redirect()->back()
                    ->with('error', '❌ El docente no tiene un CV cargado.');
            }

            $fileName = "CV_{$docente->nombres}_{$docente->apellidos}.pdf";
            
            return Storage::disk('public')->download($docente->curriculum_vitae, $fileName);

        } catch (\Exception $e) {
            Log::error('Error al descargar CV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al descargar el CV.');
        }
    }

    /**
     * 📧 Reenviar credenciales de acceso
     */
    public function reenviarCredenciales(Docente $docente)
    {
        // abort_unless(Auth::user()->can('docentes.edit'), 403); // ✅ COMENTADO

        try {
            // Generar nueva contraseña temporal
            $passwordTemporal = 'UNDAC' . rand(1000, 9999);
            
            $docente->user->update([
                'password' => Hash::make($passwordTemporal)
            ]);

            Log::info("Credenciales reenviadas al docente: {$docente->id}");

            return redirect()->back()
                ->with('success', '✅ Credenciales enviadas al correo institucional.');

        } catch (\Exception $e) {
            Log::error('Error al reenviar credenciales: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al reenviar las credenciales.');
        }
    }

    /**
     * 🔍 Búsqueda rápida de docentes (para AJAX)
     */
    public function buscar(Request $request)
    {
        // abort_unless(Auth::user()->can('docentes.index'), 403); // ✅ COMENTADO

        try {
            $search = $request->get('q', '');
            
            $docentes = Docente::where('activo', true)
                ->where(function ($query) use ($search) {
                    $query->where('dni', 'like', "%{$search}%")
                        ->orWhere('nombres', 'like', "%{$search}%")
                        ->orWhere('apellidos', 'like', "%{$search}%");
                })
                ->limit(10)
                ->get(['id', 'dni', 'nombres', 'apellidos', 'correo_institucional']);

            return response()->json($docentes);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de docentes: ' . $e->getMessage());
            return response()->json(['error' => 'Error en la búsqueda'], 500);
        }
    }

    /**
     * Mis cursos asignados (para rol Docente)
     */
    public function misCursos()
{
    $user = Auth::user();
    
    if (!$user->hasRole('Docente')) {
        abort(403, 'Acceso denegado.');
    }

    $docente = Docente::where('correo_institucional', $user->email)->first();

    if (!$docente) {
        return view('docentes.mis-cursos')->with([
            'mensaje' => 'No se encontró un perfil de docente asociado a tu cuenta.',
            'cursosActivos' => collect(),
            'cursosFinalizados' => collect(),
        ]);
    }

    // ✅ Obtener cursos activos con conteo de inscripciones
    $cursosActivos = \App\Models\Curso::whereHas('asignacionesDocentes', function($query) use ($docente) {
        $query->where('docente_id', $docente->id)
              ->where('activo', true);
    })
    ->whereIn('estado', ['convocatoria', 'en_curso'])
    ->with(['modalidad', 'categoria'])
    ->withCount(['inscripciones' => function ($query) {
        $query->whereIn('estado', ['Confirmada', 'confirmada', 'En Curso']);
    }])
    ->get();

    // ✅ CORRECCIÓN: Agregar withCount también a cursos finalizados
    $cursosFinalizados = \App\Models\Curso::whereHas('asignacionesDocentes', function($query) use ($docente) {
        $query->where('docente_id', $docente->id);
    })
    ->where('estado', 'finalizado')
    ->with(['modalidad', 'categoria'])
    ->withCount(['inscripciones' => function ($query) {
        $query->whereIn('estado', ['Confirmada', 'confirmada', 'En Curso']);
    }])
    ->orderBy('fecha_fin', 'desc')
    ->take(5)
    ->get();

    return view('docentes.mis-cursos', compact('cursosActivos', 'cursosFinalizados', 'docente'));
}
}