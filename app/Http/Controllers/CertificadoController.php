<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Models\Inscripcion;
use App\Models\ValidacionCertificado;
use App\Models\Notificacion;
use App\Http\Requests\CertificadoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\GenerarCertificadoJob;

use App\Models\Curso;

class CertificadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
public function index(Request $request)
{
    // abort_unless(Auth::user()->can('certificados.index'), 403);

    try {
        $query = Certificado::with(['inscripcion.estudiante', 'inscripcion.curso']);

        // Filtro por curso
        if ($request->filled('curso_id')) {
            $query->whereHas('inscripcion', function($q) use ($request) {
                $q->where('curso_id', $request->curso_id);
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_hasta);
        }

        $certificados = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas
$totalCertificados = Certificado::count();
$certificadosEmitidos = Certificado::where('estado', 'emitido')->count();
$certificadosPendientes = Certificado::where('estado', 'pendiente')->count();
$certificadosRevocados = Certificado::where('estado', 'revocado')->count();

// Cursos para filtro
$cursos = Curso::orderBy('nombre')->get();

return view('certificados.index', compact(
    'certificados', 
    'cursos',
    'totalCertificados',
    'certificadosEmitidos',
    'certificadosPendientes',
    'certificadosRevocados'
));

    } catch (\Exception $e) {
        \Log::error('Error en CertificadoController@index: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al cargar los certificados.');
    }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
{
    //  abort_unless(Auth::user()->can('certificados.create'), 403);

    try {
        // Obtener todos los cursos
        $cursos = \App\Models\Curso::withCount('inscripciones')->get();
        
        // Variables iniciales
        $cursoSeleccionado = null;
        $estudiantesAptos = collect([]);
        $estudiantesNoAptos = collect([]);
        $totalInscritos = 0;
        $yaCertificados = 0;
        $docentePrincipal = null;
        $porcentajeAsistenciaMinimo = 75;

        // Si se seleccionó un curso
        if ($request->filled('curso_id')) {
            $cursoSeleccionado = \App\Models\Curso::with('docentes')->findOrFail($request->curso_id);
            $docentePrincipal = $cursoSeleccionado->docentes->first();
            
            // Obtener inscripciones del curso
            $inscripciones = Inscripcion::with(['estudiante', 'curso', 'calificaciones', 'asistencias'])
                ->where('curso_id', $request->curso_id)
                ->where('estado', 'confirmada')
                ->get();
            
            $totalInscritos = $inscripciones->count();
            $yaCertificados = Certificado::whereHas('inscripcion', function($q) use ($request) {
                $q->where('curso_id', $request->curso_id);
            })->count();
            
            // Separar aptos y no aptos
            foreach ($inscripciones as $inscripcion) {
                $validacion = $this->validarCriteriosCertificacion($inscripcion);
                $promedio = $this->calcularPromedio($inscripcion);
                $asistencia = $this->calcularAsistencia($inscripcion);
                
                $inscripcion->nota_final = $promedio;
                $inscripcion->porcentaje_asistencia = $asistencia;
                
                // Verificar si ya tiene certificado
                if ($inscripcion->certificado()->exists()) {
                    continue; // Saltar si ya tiene certificado
                }
                
                if ($validacion['apto']) {
                    $estudiantesAptos->push($inscripcion);
                } else {
                    $estudiantesNoAptos->push($inscripcion);
                }
            }
        }

        return view('certificados.generar', compact(
            'cursos',
            'cursoSeleccionado',
            'estudiantesAptos',
            'estudiantesNoAptos',
            'totalInscritos',
            'yaCertificados',
            'docentePrincipal',
            'porcentajeAsistenciaMinimo'
        ));

    } catch (\Exception $e) {
        Log::error('Error en CertificadoController@create: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
    }
}

    /**
     * Store a newly created resource in storage.
     */
    /**
 * Store a newly created resource in storage.
 */
public function generarMasivo(Request $request)
{
    $request->validate([
        'curso_id' => 'required|exists:cursos,id',
        'inscripciones' => 'required|array|min:1',
        'inscripciones.*' => 'required|integer|exists:inscripciones,id'
    ]);

    DB::beginTransaction();

    try {
        $curso = Curso::findOrFail($request->curso_id);
        $contadorGenerados = 0;
        $errores = [];

        foreach ($request->inscripciones as $inscripcionId) {
            $inscripcion = Inscripcion::with('estudiante.user', 'curso', 'calificaciones.evaluacion')
                ->findOrFail($inscripcionId);

            // Calcular nota final
            $notaFinal = $inscripcion->nota_final;
            if (!$notaFinal) {
                $calificaciones = $inscripcion->calificaciones()->with('evaluacion')->get();
                
                if ($calificaciones->count() > 0) {
                    $notaFinal = 0;
                    $pesoTotal = 0;
                    
                    foreach ($calificaciones as $calificacion) {
                        $peso = $calificacion->evaluacion->peso_porcentaje / 100;
                        $notaFinal += $calificacion->nota * $peso;
                        $pesoTotal += $peso;
                    }
                    
                    $notaFinal = $pesoTotal > 0 ? $notaFinal : 0;
                } else {
                    $notaFinal = 0;
                }
            }

            // Calcular asistencia
            $porcentajeAsistencia = $inscripcion->porcentaje_asistencia;
            if (!$porcentajeAsistencia) {
                $totalSesiones = \App\Models\SesionCurso::where('curso_id', $inscripcion->curso_id)
                    ->where('estado', 'finalizada')
                    ->count();
                
                if ($totalSesiones == 0) {
                    $totalSesiones = $inscripcion->asistencias()->count();
                }
                
                $presentes = $inscripcion->asistencias()
                    ->where('estado', 'presente')
                    ->count();
                
                $porcentajeAsistencia = $totalSesiones > 0 
                    ? round(($presentes / $totalSesiones) * 100, 1) 
                    : 0;
            }

            // Validar criterios
            if ($notaFinal < 11) {
                $errores[] = "{$inscripcion->estudiante->nombres}: Nota insuficiente ({$notaFinal})";
                continue;
            }

            if ($porcentajeAsistencia < 75) {
                $errores[] = "{$inscripcion->estudiante->nombres}: Asistencia insuficiente ({$porcentajeAsistencia}%)";
                continue;
            }

            $certificadoExistente = Certificado::where('inscripcion_id', $inscripcion->id)->exists();
            if ($certificadoExistente) {
                $errores[] = "{$inscripcion->estudiante->nombres}: Ya tiene certificado";
                continue;
            }

            // ✅ Generar certificado SIN FIRMAR
            $numeroCertificado = 'CERT-' . date('Y') . '-' . str_pad($inscripcion->id, 6, '0', STR_PAD_LEFT);
            $codigoQr = 'QR-' . md5(uniqid($inscripcion->id, true));
            
            $certificado = Certificado::create([
                'inscripcion_id' => $inscripcion->id,
                'codigo_certificado' => $numeroCertificado,
                'codigo_qr' => $codigoQr,
                'fecha_emision' => now(),
                'pdf_url' => null, // ✅ Se generará cuando se firme
                'firmado' => false, // ✅ NO FIRMADO
                'pdf_firmado_url' => null,
                'estado' => 'pendiente', // ✅ Pendiente de firma
                'firmado_por' => null,
            ]);

            // Actualizar inscripción
            $inscripcion->update([
                'nota_final' => $notaFinal,
                'porcentaje_asistencia' => $porcentajeAsistencia,
                'aprobado' => true
            ]);

            $contadorGenerados++;

            \Log::info('Certificado generado (pendiente de firma)', [
                'certificado_id' => $certificado->id,
                'estudiante_id' => $inscripcion->estudiante_id,
            ]);
        }

        DB::commit();

        $mensaje = "✓ Se generaron {$contadorGenerados} certificados pendientes de firma.";
        if (count($errores) > 0) {
            $mensaje .= " ⚠️ " . count($errores) . " estudiante(s) no cumplen criterios.";
        }

        return redirect()->route('certificados.index')
            ->with('success', $mensaje);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al generar certificados: ' . $e->getMessage());

        return redirect()->back()
            ->with('error', 'Error: ' . $e->getMessage())
            ->withInput();
    }
}
    /**
     * Display the specified resource.
     */
    public function show(Certificado $certificado)
    {
         // abort_unless(Auth::user()->can('certificados.show'), 403);

        try {
            $certificado->load([
                'inscripcion.estudiante',
                'inscripcion.curso.modalidad',
                'inscripcion.curso.categoria',
                'inscripcion.calificaciones.evaluacion',
                'inscripcion.asistencias',
                'validaciones' => function ($query) {
                    $query->orderBy('fecha_validacion', 'desc')->limit(10);
                }
            ]);

            // 📊 Calcular estadísticas del estudiante
            $promedio = $this->calcularPromedio($certificado->inscripcion);
            $porcentajeAsistencia = $this->calcularAsistencia($certificado->inscripcion);

            $stats = [
                'promedio_final' => round($promedio, 2),
                'porcentaje_asistencia' => round($porcentajeAsistencia, 2),
                'total_validaciones' => $certificado->validaciones->count(),
                'revocado' => !is_null($certificado->fecha_revocacion),
            ];

            return view('certificados.show', compact('certificado', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error en CertificadoController@show: ' . $e->getMessage());
            return redirect()->route('certificados.index')
                ->with('error', '❌ Error al cargar el certificado.');
        }
    }

    /**
     * 📥 Descargar certificado en PDF
     */
    public function descargar(Certificado $certificado)
{
    $user = auth()->user();
    
    // ✅ Verificar permisos
    if ($user->hasAnyRole(['Administrador', 'Comité Académico', 'Docente'])) {
        // Permitir acceso
    } else {
        // Si es estudiante, verificar que sea su propio certificado
        $estudiante = $user->estudiante;
        
        if (!$estudiante) {
            abort(403, 'No se encontró perfil de estudiante.');
        }
        
        if ($certificado->inscripcion->estudiante_id !== $estudiante->id) {
            abort(403, 'No tienes permiso para ver este certificado.');
        }
    }

    // ✅ SI ESTÁ FIRMADO: Descargar el PDF firmado
    if ($certificado->firmado && $certificado->pdf_firmado_url) {
        $certificado->increment('numero_veces_descargado');
        $certificado->update(['ultima_descarga' => now()]);
        
        // Construir la ruta correcta
        $rutaCompleta = storage_path('app/' . $certificado->pdf_firmado_url);
        
        // Verificar si el archivo existe
        if (!file_exists($rutaCompleta)) {
            \Log::error('Archivo no encontrado', [
                'ruta_buscada' => $rutaCompleta,
                'pdf_firmado_url' => $certificado->pdf_firmado_url
            ]);
            
            return redirect()->back()->with('error', 'El archivo del certificado no se encuentra en el servidor.');
        }
        
        return response()->download($rutaCompleta, "Certificado-{$certificado->codigo_certificado}.pdf");
    }

    // ❌ Si no está firmado, redirigir a la vista pública (que mostrará "pendiente")
    return redirect()->route('certificado.publico', $certificado->codigo_qr);
}
    /**
     * 🌐 Validar certificado públicamente (sin autenticación)
     */
    public function validar($numero_serie)
    {
        try {
            $certificado = Certificado::where('numero_serie', $numero_serie)
                ->with([
                    'inscripcion.estudiante',
                    'inscripcion.curso.modalidad',
                    'inscripcion.curso.categoria'
                ])
                ->first();

            if (!$certificado) {
                return view('certificados.validar', [
                    'valido' => false,
                    'mensaje' => '❌ Certificado no encontrado. Verifique el número de serie.',
                    'numero_serie' => $numero_serie,
                ]);
            }

            // 🚫 Verificar si está revocado
            if ($certificado->fecha_revocacion) {
                return view('certificados.validar', [
                    'valido' => false,
                    'mensaje' => '⚠️ Este certificado ha sido revocado.',
                    'certificado' => $certificado,
                    'motivo_revocacion' => $certificado->motivo_revocacion,
                ]);
            }

            // 📝 Registrar validación
            ValidacionCertificado::create([
                'certificado_id' => $certificado->id,
                'fecha_validacion' => now(),
                'ip_validacion' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // 📊 Calcular datos
            $promedio = round($this->calcularPromedio($certificado->inscripcion), 2);
            $porcentajeAsistencia = round($this->calcularAsistencia($certificado->inscripcion), 2);

            Log::info("Certificado validado públicamente: {$certificado->id} - IP: " . request()->ip());

            return view('certificados.validar', [
                'valido' => true,
                'certificado' => $certificado,
                'promedio' => $promedio,
                'porcentaje_asistencia' => $porcentajeAsistencia,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al validar certificado: ' . $e->getMessage());
            return view('certificados.validar', [
                'valido' => false,
                'mensaje' => '❌ Error al validar el certificado. Intente nuevamente.',
            ]);
        }
    }

    /**
     * 🚫 Revocar certificado
     */
   
public function revocar(Request $request, Certificado $certificado)
{
    abort_unless(auth()->user()->hasRole('Administrador'), 403);

    $request->validate([
        'motivo' => 'nullable|string|max:500'
    ]);

    DB::beginTransaction();
    
    try {
        $certificado->update([
            'estado' => 'revocado',
            'observaciones' => $request->motivo ?? 'Certificado revocado por administrador'
        ]);

        \Log::info('Certificado revocado', [
            'certificado_id' => $certificado->id,
            'revocado_por' => auth()->user()->email
        ]);

        DB::commit();

        return redirect()->back()
            ->with('success', '✓ Certificado revocado correctamente');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al revocar: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', '✗ Error al revocar el certificado');
    }
}

public function restaurar(Certificado $certificado)
{
    abort_unless(auth()->user()->hasRole('Administrador'), 403);

    DB::beginTransaction();
    
    try {
        $certificado->update([
            'estado' => 'emitido',
            'observaciones' => 'Certificado restaurado por administrador'
        ]);

        \Log::info('Certificado restaurado', [
            'certificado_id' => $certificado->id,
            'restaurado_por' => auth()->user()->email
        ]);

        DB::commit();

        return redirect()->back()
            ->with('success', '✓ Certificado restaurado correctamente');

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', '✗ Error al restaurar el certificado');
    }
}
    /**
     * ✅ Validar criterios de certificación
     */
    private function validarCriteriosCertificacion(Inscripcion $inscripcion): array
    {
        $curso = $inscripcion->curso;
        $razones = [];
        $apto = true;

        // 📊 Validar nota mínima
        $promedio = $this->calcularPromedio($inscripcion);
        $notaMinima = $curso->nota_minima_aprobacion ?? 10.5;
        
        if ($promedio < $notaMinima) {
            $razones[] = "Promedio insuficiente ({$promedio} < {$notaMinima})";
            $apto = false;
        }

        // 📅 Validar asistencia mínima
        $porcentajeAsistencia = $this->calcularAsistencia($inscripcion);
        $asistenciaMinima = $curso->asistencia_minima_porcentaje ?? 70;
        
        if ($porcentajeAsistencia < $asistenciaMinima) {
            $razones[] = "Asistencia insuficiente ({$porcentajeAsistencia}% < {$asistenciaMinima}%)";
            $apto = false;
        }

        // ✅ Validar que tenga calificaciones
        if ($inscripcion->calificaciones->isEmpty()) {
            $razones[] = "No tiene calificaciones registradas";
            $apto = false;
        }

        // ✅ Validar que el curso esté finalizado
        if ($curso->estado !== 'finalizado') {
            $razones[] = "El curso no ha finalizado";
            $apto = false;
        }

        return [
            'apto' => $apto,
            'razones' => $razones,
            'promedio' => round($promedio, 2),
            'porcentaje_asistencia' => round($porcentajeAsistencia, 2),
        ];
    }

    /**
     * 📊 Calcular promedio ponderado del estudiante
     */
    private function calcularPromedio(Inscripcion $inscripcion): float
    {
        $calificaciones = $inscripcion->calificaciones()
            ->with('evaluacion')
            ->whereHas('evaluacion', function ($q) {
                $q->where('activo', true);
            })
            ->get();

        if ($calificaciones->isEmpty()) {
            return 0;
        }

        $promedio = 0;
        $pesoTotal = 0;

        foreach ($calificaciones as $calificacion) {
            $promedio += ($calificacion->nota * $calificacion->evaluacion->peso_porcentaje) / 100;
            $pesoTotal += $calificacion->evaluacion->peso_porcentaje;
        }

        // Si el peso total no es 100%, ajustar proporcionalmente
        if ($pesoTotal > 0 && $pesoTotal < 100) {
            $promedio = ($promedio / $pesoTotal) * 100;
        }

        return $promedio;
    }

    /**
     * 📅 Calcular porcentaje de asistencia
     */
    private function calcularAsistencia(Inscripcion $inscripcion): float
    {
        $totalSesiones = $inscripcion->asistencias()->count();
        
        if ($totalSesiones == 0) {
            return 0;
        }

        $presentes = $inscripcion->asistencias()
            ->whereIn('estado', ['presente', 'tardanza'])
            ->count();

        return ($presentes / $totalSesiones) * 100;
    }

    /**
     * 📈 Generar certificados masivos para un curso
     */
    /**
 * 📈 Generar certificados masivos para un curso
 */
public function generar(Request $request)
{
    // abort_unless(Auth::user()->can('certificados.generar'), 403);

    try {
        // Obtener cursos finalizados o en curso
        $cursos = Curso::whereIn('estado', ['finalizado', 'en_curso'])
            ->withCount('inscripciones')
            ->orderBy('nombre')
            ->get();

        // Si se seleccionó un curso
        if ($request->filled('curso_id')) {
            $cursoSeleccionado = Curso::with(['inscripciones.estudiante'])
                ->findOrFail($request->curso_id);

            // Obtener inscripciones confirmadas
            $inscripciones = Inscripcion::where('curso_id', $request->curso_id)
                ->where('estado', 'confirmada')
                ->with(['estudiante', 'calificaciones', 'asistencias'])
                ->get();

            // Calcular datos de cada inscripción
            foreach ($inscripciones as $inscripcion) {
                // Calcular nota final
                $calificaciones = $inscripcion->calificaciones;
                $inscripcion->nota_final = $calificaciones->count() > 0 ? $calificaciones->avg('nota') : 0;

                // Calcular porcentaje de asistencia
                $asistencias = $inscripcion->asistencias;
                $totalAsistencias = $asistencias->count();
                $presente = $asistencias->where('estado', 'presente')->count();
                $inscripcion->porcentaje_asistencia = $totalAsistencias > 0 ? ($presente / $totalAsistencias) * 100 : 0;

                // Verificar si ya tiene certificado
                $inscripcion->tiene_certificado = Certificado::where('inscripcion_id', $inscripcion->id)->exists();
            }

            // Separar aptos y no aptos
            $estudiantesAptos = $inscripciones->filter(function ($inscripcion) {
                return $inscripcion->nota_final >= 10.5 
                    && $inscripcion->porcentaje_asistencia >= 75 
                    && !$inscripcion->tiene_certificado;
            });

            $estudiantesNoAptos = $inscripciones->filter(function ($inscripcion) {
                return ($inscripcion->nota_final < 10.5 
                    || $inscripcion->porcentaje_asistencia < 75)
                    && !$inscripcion->tiene_certificado;
            });

            $totalInscritos = $inscripciones->count();
            $yaCertificados = $inscripciones->where('tiene_certificado', true)->count();
            $porcentajeAsistenciaMinimo = 75;
            $docentePrincipal = null;

            return view('certificados.generar', compact(
                'cursos',
                'cursoSeleccionado',
                'estudiantesAptos',
                'estudiantesNoAptos',
                'totalInscritos',
                'yaCertificados',
                'porcentajeAsistenciaMinimo',
                'docentePrincipal'
            ));
        }

        return view('certificados.generar', compact('cursos'));

    } catch (\Exception $e) {
        \Log::error('Error en CertificadoController@generar: ' . $e->getMessage());
        return redirect()->route('certificados.index')
            ->with('error', 'Error al cargar el formulario de certificados.');
    }
}

    /**
     * 🔍 Buscar certificado por número de serie (AJAX)
     */
    public function buscar(Request $request)
    {
        try {
            $numeroSerie = $request->get('numero_serie', '');
            
            $certificado = Certificado::where('numero_serie', 'like', "%{$numeroSerie}%")
                ->with(['inscripcion.estudiante', 'inscripcion.curso'])
                ->first();

            if (!$certificado) {
                return response()->json(['found' => false]);
            }

            return response()->json([
                'found' => true,
                'data' => $certificado,
            ]);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de certificado: ' . $e->getMessage());
            return response()->json(['error' => 'Error en la búsqueda'], 500);
        }
    }
  /**
 * Ver certificado público (sin autenticación)
 */
public function verPublico($codigo_qr)
{
    try {
        $certificado = Certificado::where('codigo_qr', $codigo_qr)
            ->with([
                'inscripcion.estudiante',
                'inscripcion.curso.categoria',
                'inscripcion.curso.modalidad',
                'inscripcion.calificaciones.evaluacion'
            ])
            ->firstOrFail();

        // ✅ VERIFICAR SI ESTÁ FIRMADO
        if (!$certificado->firmado || empty($certificado->pdf_firmado_url)) {
            return view('certificados.publico-pendiente', [
                'mensaje' => 'Este certificado está en proceso de firma digital.',
                'codigo' => $certificado->codigo_certificado
            ]);
        }

        // Verificar que esté emitido
        if ($certificado->estado === 'revocado') {
            return view('certificados.publico-revocado', [
                'mensaje' => 'Este certificado ha sido revocado.',
                'codigo' => $certificado->codigo_certificado
            ]);
        }

        $estudiante = $certificado->inscripcion->estudiante;
        $curso = $certificado->inscripcion->curso;
        $notaFinal = $certificado->inscripcion->calificaciones->avg('nota') ?? 0;
        $porcentajeAsistencia = $certificado->inscripcion->porcentaje_asistencia ?? 0;

        return view('certificados.publico', compact(
            'certificado',
            'estudiante',
            'curso',
            'notaFinal',
            'porcentajeAsistencia'
        ));

    } catch (\Exception $e) {
        return view('certificados.publico-error', [
            'mensaje' => 'Certificado no encontrado.',
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * 📥 Descargar PDF sin firmar (solo admin)
 */


private function notaEnLetras($nota)
{
    if ($nota >= 19) return 'Excelente';
    if ($nota >= 17) return 'Muy Bueno';
    if ($nota >= 15) return 'Bueno';
    if ($nota >= 11) return 'Aprobado';
    return 'No Aprobado';
}
public function descargarPDF(Certificado $certificado)
{
    $user = auth()->user();
    
    // Verificar permisos
    if (!$user->hasAnyRole(['Administrador', 'Comité Académico', 'Docente'])) {
        $estudiante = $user->estudiante;
        
        if (!$estudiante || $certificado->inscripcion->estudiante_id !== $estudiante->id) {
            abort(403, 'No tienes permiso para descargar este certificado.');
        }
    }

    // ✅ SI ESTÁ FIRMADO: Descargar el PDF firmado
    if ($certificado->firmado && $certificado->pdf_firmado_url) {
        $certificado->increment('numero_veces_descargado');
        $certificado->update(['ultima_descarga' => now()]);
        
        return response()->download(storage_path('app/' . $certificado->pdf_firmado_url));
    }

    // ❌ Si no está firmado: generar PDF temporal sin firma
    $certificado->increment('numero_veces_descargado');
    $certificado->update(['ultima_descarga' => now()]);

    $notaEnLetras = function($nota) {
        if ($nota >= 19) return 'Excelente';
        if ($nota >= 17) return 'Muy Bueno';
        if ($nota >= 15) return 'Bueno';
        if ($nota >= 11) return 'Aprobado';
        return 'No Aprobado';
    };

    $pdf = \PDF::loadView('certificados.plantilla-pdf', [
        'certificado' => $certificado,
        'inscripcion' => $certificado->inscripcion,
        'estudiante' => $certificado->inscripcion->estudiante,
        'curso' => $certificado->inscripcion->curso,
        'notaEnLetras' => $notaEnLetras,
    ]);

    return $pdf->download("Certificado-{$certificado->codigo_certificado}.pdf");
}


/**
 * 📥 Descargar PDF sin firmar (solo admin)
 */
public function descargarPDFSinFirmar(Certificado $certificado)
{
    abort_unless(auth()->user()->hasRole('Administrador'), 403);

    $notaEnLetras = function($nota) {
        if ($nota >= 19) return 'Excelente';
        if ($nota >= 17) return 'Muy Bueno';
        if ($nota >= 15) return 'Bueno';
        if ($nota >= 11) return 'Aprobado';
        return 'No Aprobado';
    };

    $pdf = \PDF::loadView('certificados.plantilla-pdf', [
        'certificado' => $certificado,
        'inscripcion' => $certificado->inscripcion,
        'estudiante' => $certificado->inscripcion->estudiante,
        'curso' => $certificado->inscripcion->curso,
        'notaEnLetras' => $notaEnLetras,
    ]);

    return $pdf->download("Certificado-SIN-FIRMAR-{$certificado->codigo_certificado}.pdf");
}

/**
 * 📤 Subir PDF firmado por el administrador
 */
public function subirPDFFirmado(Request $request, Certificado $certificado)
{
    abort_unless(auth()->user()->hasRole('Administrador'), 403);

    $request->validate([
        'pdf_firmado' => 'required|file|mimes:pdf|max:5120',
    ]);

    DB::beginTransaction();

    try {
        $archivo = $request->file('pdf_firmado');
        $nombreArchivo = 'cert_firmado_' . $certificado->codigo_qr . '.pdf';
        
        \Log::info('Intentando guardar archivo', [
            'nombre_original' => $archivo->getClientOriginalName(),
            'nombre_destino' => $nombreArchivo,
            'size' => $archivo->getSize(),
        ]);
        
        // Guardar en storage/app/public/certificados
        $rutaArchivo = $archivo->storeAs('public/certificados', $nombreArchivo);
        
        // Verificar que se guardó
        $rutaCompleta = storage_path('app/' . $rutaArchivo);
        
        \Log::info('Archivo guardado', [
            'ruta_relativa' => $rutaArchivo,
            'ruta_completa' => $rutaCompleta,
            'existe' => file_exists($rutaCompleta),
            'permisos' => file_exists($rutaCompleta) ? substr(sprintf('%o', fileperms($rutaCompleta)), -4) : 'N/A'
        ]);

        if (!file_exists($rutaCompleta)) {
            throw new \Exception("El archivo no se guardó correctamente en: {$rutaCompleta}");
        }

        // Actualizar certificado
        $certificado->update([
            'pdf_firmado_url' => $rutaArchivo,
            'firmado' => true,
            'fecha_firmado' => now(),
            'firmado_por_user_id' => auth()->id(),
            'estado' => 'emitido',
        ]);

        \Log::info('Certificado actualizado en BD', [
            'certificado_id' => $certificado->id,
            'pdf_firmado_url' => $rutaArchivo,
        ]);

        DB::commit();

        return redirect()->route('certificados.index')
            ->with('success', '✓ Certificado firmado subido correctamente. Ahora es visible públicamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al subir PDF firmado', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', '✗ Error: ' . $e->getMessage());
    }

}
}