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
use App\Models\Curso;           // ⭐ IMPORTANTE
use App\Models\Docente; 
use Illuminate\Support\Facades\Storage;

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
        // 🔍 BÚSQUEDA GENERAL (DNI, nombre, código, curso)
if ($request->filled('search')) {
    $searchTerm = $request->search;
    
    $query->where(function($q) use ($searchTerm) {
        // Buscar en código de certificado
        $q->where('codigo_certificado', 'like', '%' . $searchTerm . '%')
          // Buscar en datos del estudiante
          ->orWhereHas('inscripcion.estudiante', function($subQ) use ($searchTerm) {
              $subQ->where('nombres', 'like', '%' . $searchTerm . '%')
                   ->orWhere('apellidos', 'like', '%' . $searchTerm . '%')
                   ->orWhere('dni', 'like', '%' . $searchTerm . '%');
          })
          // Buscar en nombre del curso
          ->orWhereHas('inscripcion.curso', function($subQ) use ($searchTerm) {
              $subQ->where('nombre', 'like', '%' . $searchTerm . '%')
                   ->orWhere('codigo', 'like', '%' . $searchTerm . '%');
          });
    });
}

// Filtro por estado (mantener)
if ($request->filled('estado')) {
    $query->where('estado', $request->estado);
}
       // ⭐ ORDENAMIENTO ALFABÉTICO por apellidos del estudiante
$query->join('inscripciones', 'certificados.inscripcion_id', '=', 'inscripciones.id')
      ->join('estudiantes', 'inscripciones.estudiante_id', '=', 'estudiantes.id')
      ->orderBy('estudiantes.apellidos', 'asc')
      ->orderBy('estudiantes.nombres', 'asc')
      ->select('certificados.*'); // ⚠️ IMPORTANTE: solo seleccionar campos de certificados

$certificados = $query->paginate(15);

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
    // Validar entrada
    $validated = $request->validate([
        'curso_id' => 'required|exists:cursos,id',
        'inscripciones' => 'required|array|min:1',
        'inscripciones.*' => 'required|integer|exists:inscripciones,id',
    ], [
        'inscripciones.required' => '❌ Debes seleccionar al menos un estudiante.',
        'inscripciones.min' => '❌ Debes seleccionar al menos un estudiante.',
    ]);

    DB::beginTransaction();

    try {
        $cursoId = $validated['curso_id'];
        $inscripcionesIds = $validated['inscripciones'];

        // Obtener curso
        $curso = Curso::findOrFail($cursoId);

        // Obtener inscripciones a procesar
        $inscripciones = Inscripcion::where('curso_id', $cursoId)
            ->whereIn('id', $inscripcionesIds)
            ->with([
                'estudiante.user',
                'curso',
                'calificaciones.evaluacion',
                'asistencias',
                'certificado'
            ])
            ->get();

        if ($inscripciones->isEmpty()) {
            return back()->with('error', 'No se encontraron inscripciones para generar certificados.');
        }

        $certificadosGenerados = 0;
        $errores = [];

        // Procesar cada inscripción
        foreach ($inscripciones as $inscripcion) {
            try {
                // 1. Calcular nota final
                $notaFinal = $this->calcularPromedio($inscripcion);
                
                // 2. Calcular asistencia
                $porcentajeAsistencia = $this->calcularAsistencia($inscripcion);

                \Log::info("Procesando certificado", [
                    'estudiante' => $inscripcion->estudiante->nombres,
                    'nota' => $notaFinal,
                    'asistencia' => $porcentajeAsistencia
                ]);

                // 3. Validar criterios
                if ($notaFinal < 11) {
                    $errores[] = "❌ {$inscripcion->estudiante->nombres}: Nota insuficiente ({$notaFinal})";
                    continue;
                }

                if ($porcentajeAsistencia < 75) {
                    $errores[] = "❌ {$inscripcion->estudiante->nombres}: Asistencia insuficiente ({$porcentajeAsistencia}%)";
                    continue;
                }

                // 4. Verificar si ya tiene certificado
                if ($inscripcion->certificado) {
                    $errores[] = "⚠️ {$inscripcion->estudiante->nombres}: Ya tiene certificado";
                    continue;
                }

                // 5. Generar código del certificado PRIMERO
                $numeroCertificado = 'CERT-' . date('Y') . '-' . str_pad($inscripcion->id, 6, '0', STR_PAD_LEFT);
                $codigoQr = 'QR-' . md5(uniqid($inscripcion->id, true));

                // 6. CREAR CERTIFICADO EN BD PRIMERO (sin PDF aún)
                $certificado = Certificado::create([
                    'inscripcion_id' => $inscripcion->id,
                    'codigo_certificado' => $numeroCertificado,
                    'codigo_qr' => $codigoQr,
                    'fecha_emision' => now(),
                    'pdf_url' => 'temp',  // Placeholder temporal
                    'firmado' => false,
                    'pdf_firmado_url' => null,
                    'estado' => 'pending',  // ✅ Cambié a "pending" (7 caracteres)
                    'firmado_por' => null,
                    'numero_veces_descargado' => 0,
                ]);

                // 7. AHORA generar el PDF con el certificado creado
                
                try {
                    $pdf = Pdf::loadView('certificados.plantilla-pdf', [
                        'certificado' => $certificado,  // ✅ OBJETO Certificado REAL, no stdClass
                        'inscripcion' => $inscripcion,
                        'estudiante' => $inscripcion->estudiante,
                        'curso' => $inscripcion->curso,
                        'notaEnLetras' => function($nota) { // ✅ FUNCIÓN, no string
                            if ($nota >= 19) return 'Excelente';
                            if ($nota >= 17) return 'Muy Bueno';
                            if ($nota >= 15) return 'Bueno';
                            if ($nota >= 11) return 'Aprobado';
                            return 'No Aprobado';
                        },
                        'notaFinal' => $notaFinal,
                        'porcentajeAsistencia' => $porcentajeAsistencia,
                    ]);

                    // Generar nombre del archivo
                    $nombreArchivo = 'cert_' . $codigoQr . '.pdf';
                    $rutaArchivo = 'public/certificados/' . $nombreArchivo;
                    
                    // Guardar el PDF
                    Storage::put($rutaArchivo, $pdf->output());

                    // Verificar que se guardó
                    if (!Storage::exists($rutaArchivo)) {
                        throw new \Exception("No se pudo guardar el PDF en: {$rutaArchivo}");
                    }

                    // 8. Actualizar certificado con la ruta del PDF
                    $certificado->update([
                        'pdf_url' => $rutaArchivo,
                    ]);

                    // 9. Actualizar inscripción con datos calculados
                    $inscripcion->update([
                        'nota_final' => round($notaFinal, 2),
                        'porcentaje_asistencia' => round($porcentajeAsistencia, 2),
                        'aprobado' => true,
                    ]);

                    $certificadosGenerados++;

                    \Log::info('✓ Certificado creado', [
                        'certificado_id' => $certificado->id,
                        'estudiante_id' => $inscripcion->estudiante_id,
                        'codigo' => $numeroCertificado,
                        'pdf_url' => $rutaArchivo
                    ]);

                } catch (\Exception $pdfError) {
                    // Si falla la generación del PDF, eliminar el certificado creado
                    $certificado->delete();
                    throw new \Exception("Error generando PDF: " . $pdfError->getMessage());
                }

            } catch (\Exception $e) {
                \Log::error("Error procesando inscripción {$inscripcion->id}: " . $e->getMessage());
                $errores[] = "Error procesando {$inscripcion->estudiante->nombres}: " . $e->getMessage();
            }
        }

        DB::commit();

        // Preparar mensaje
        $mensaje = "✅ Se generaron <strong>{$certificadosGenerados}</strong> certificado(s) pendiente(s) de firma.";
        
        if (count($errores) > 0) {
            $mensaje .= " ⚠️ " . count($errores) . " estudiante(s) no cumplen criterios.";
        }

        return redirect()->route('certificados.index')
            ->with('success', $mensaje);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error en generarMasivo: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());

        return redirect()->back()
            ->with('error', '❌ Error: ' . $e->getMessage())
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
    // 1️⃣ Si ya tiene nota_final en BD, usarla
    if ($inscripcion->nota_final && $inscripcion->nota_final > 0) {
        return (float) $inscripcion->nota_final;
    }

    // 2️⃣ Si no, calcular desde calificaciones
    $calificaciones = $inscripcion->calificaciones()
        ->with('evaluacion')
        ->whereHas('evaluacion', function($q) {
            $q->where('activo', true);
        })
        ->get();

    if ($calificaciones->isEmpty()) {
        \Log::warning("Sin calificaciones para inscripción {$inscripcion->id}");
        return 0;
    }

    $promedio = 0;
    $pesoTotal = 0;

    foreach ($calificaciones as $calificacion) {
        $peso = $calificacion->evaluacion->peso_porcentaje ?? 0;
        $notaCalif = $calificacion->nota ?? 0;
        
        $promedio += ($notaCalif * $peso) / 100;
        $pesoTotal += $peso;
    }

    // Ajuste si el peso no es 100%
    if ($pesoTotal > 0 && $pesoTotal < 100) {
        $promedio = ($promedio / $pesoTotal) * 100;
    }

    return round($promedio, 2);
}



    /**
     * 📅 Calcular porcentaje de asistencia
     */
  private function calcularAsistencia(Inscripcion $inscripcion): float
{
    // 1️⃣ Si ya tiene porcentaje_asistencia en BD, usarlo
    if ($inscripcion->porcentaje_asistencia && $inscripcion->porcentaje_asistencia > 0) {
        return (float) $inscripcion->porcentaje_asistencia;
    }

    // 2️⃣ Si no, calcular desde asistencias
    $asistencias = $inscripcion->asistencias;
    $totalAsistencias = $asistencias->count();

    if ($totalAsistencias == 0) {
        \Log::warning("Sin asistencias para inscripción {$inscripcion->id}");
        return 0;
    }

    $presentes = $asistencias
        ->whereIn('estado', ['presente', 'tardanza'])
        ->count();

    $porcentaje = ($presentes / $totalAsistencias) * 100;
    
    return round($porcentaje, 2);
}

    /**
     * 📈 Generar certificados masivos para un curso
     */
    /**
 * 📈 Generar certificados masivos para un curso
 */
public function generar(Request $request)
{
    try {
        // 1️⃣ Obtener TODOS los cursos disponibles
        $cursos = Curso::whereIn('estado', ['finalizado', 'en_curso', 'activo'])
            ->with('inscripciones')
            ->orderBy('nombre')
            ->get()
            ->map(function($curso) {
                $curso->inscripciones_count = $curso->inscripciones->count();
                return $curso;
            });

        // Variables por defecto
        $cursoSeleccionado = null;
        $estudiantesAptos = collect();
        $estudiantesNoAptos = collect();
        $totalInscritos = 0;
        $yaCertificados = 0;
        $porcentajeAsistenciaMinimo = 75;
        $docentePrincipal = null;

        // 2️⃣ SI hay curso_id, cargar sus datos
        if ($request->filled('curso_id')) {
            $cursoSeleccionado = Curso::findOrFail($request->curso_id);

            // 3️⃣ Obtener todas las inscripciones del curso
            $todasInscripciones = Inscripcion::where('curso_id', $cursoSeleccionado->id)
                ->with([
                    'estudiante',
                    'certificado',
                    'calificaciones.evaluacion',
                    'asistencias',
                    'pago' // ✅ AGREGAR RELACIÓN CON PAGO
                ])
                ->get();

            $totalInscritos = $todasInscripciones->count();

            \Log::info("Procesando curso {$cursoSeleccionado->id}: {$totalInscritos} inscripciones");

            // 4️⃣ Procesar cada inscripción
            foreach ($todasInscripciones as $inscripcion) {
                // ✅ Si ya tiene certificado, contar y saltar
                if ($inscripcion->certificado) {
                    $yaCertificados++;
                    \Log::info("Saltando: ya tiene certificado - {$inscripcion->estudiante->nombres}");
                    continue;
                }

                // Calcular nota final
                $notaFinal = $this->calcularPromedio($inscripcion);
                
                // Calcular asistencia
                $porcentajeAsistencia = $this->calcularAsistencia($inscripcion);

                // Guardar en la inscripción para mostrar
                $inscripcion->nota_final = $notaFinal;
                $inscripcion->porcentaje_asistencia = $porcentajeAsistencia;

                // 5️⃣ ✅ VALIDAR TODOS LOS CRITERIOS (incluyendo PAGO)
                $cumpleNota = $notaFinal >= 11;
                $cumpleAsistencia = $porcentajeAsistencia >= $porcentajeAsistenciaMinimo;
                
                // ✅ NUEVO: Validar que tenga pago confirmado
                $cumplePago = ($inscripcion->pago_confirmado == true) || 
                             ($inscripcion->pago && $inscripcion->pago->estado === 'confirmado');

                \Log::info("Validación completa", [
                    'estudiante' => $inscripcion->estudiante->nombres,
                    'cumple_nota' => $cumpleNota ? '✓' : '✗' . " ({$notaFinal} >= 11)",
                    'cumple_asistencia' => $cumpleAsistencia ? '✓' : '✗' . " ({$porcentajeAsistencia}% >= {$porcentajeAsistenciaMinimo}%)",
                    'cumple_pago' => $cumplePago ? '✓' : '✗',
                    'pago_confirmado_bd' => $inscripcion->pago_confirmado,
                    'pago_estado' => $inscripcion->pago ? $inscripcion->pago->estado : 'sin pago',
                ]);

                // ✅ SOLO SI CUMPLE **TODOS** LOS REQUISITOS
                if ($cumpleNota && $cumpleAsistencia && $cumplePago) {
                    $estudiantesAptos->push($inscripcion);
                    \Log::info("✅ APTO: {$inscripcion->estudiante->nombres}");
                } else {
                    // Guardar razones de rechazo para debug
                    $razones = [];
                    if (!$cumpleNota) $razones[] = "Nota insuficiente ({$notaFinal})";
                    if (!$cumpleAsistencia) $razones[] = "Asistencia insuficiente ({$porcentajeAsistencia}%)";
                    if (!$cumplePago) $razones[] = "Pago no confirmado";
                    
                    $inscripcion->razon_no_apto = implode(', ', $razones);
                    $estudiantesNoAptos->push($inscripcion);
                    
                    \Log::info("❌ NO APTO: {$inscripcion->estudiante->nombres} - Razones: " . implode(', ', $razones));
                }
            }

            // 6️⃣ Obtener docente principal
            $docentePrincipal = Docente::whereHas('asignaciones', function($q) use ($cursoSeleccionado) {
                $q->where('curso_id', $cursoSeleccionado->id)
                  ->where('activo', true);
            })->first();

            \Log::info("RESUMEN DEL CURSO:", [
                'total_inscritos' => $totalInscritos,
                'aptos' => $estudiantesAptos->count(),
                'no_aptos' => $estudiantesNoAptos->count(),
                'ya_certificados' => $yaCertificados,
            ]);
        }

        return view('certificados.generar', [
            'cursos' => $cursos,
            'cursoSeleccionado' => $cursoSeleccionado,
            'estudiantesAptos' => $estudiantesAptos,
            'estudiantesNoAptos' => $estudiantesNoAptos,
            'totalInscritos' => $totalInscritos,
            'yaCertificados' => $yaCertificados,
            'porcentajeAsistenciaMinimo' => $porcentajeAsistenciaMinimo,
            'docentePrincipal' => $docentePrincipal,
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en CertificadoController@generar: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return redirect()->route('certificados.index')
            ->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
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
    \Log::info('🔵 INICIO subirPDFFirmado', [
        'usuario_id' => auth()->id(),
        'usuario_nombre' => auth()->user()->name,
        'certificado_id' => $certificado->id,
        'certificado_codigo' => $certificado->codigo_certificado,
    ]);

    // ✅ Verificar permisos
    abort_unless(auth()->user()->hasRole('Administrador'), 403);
    \Log::info('✅ Permisos verificados');

    // ✅ Validar archivo
    try {
        $validated = $request->validate([
            'pdf_firmado' => 'required|file|mimes:pdf|max:5120',
        ]);
        \Log::info('✅ Validación pasada');
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('❌ Error de validación', ['errores' => $e->errors()]);
        return redirect()->back()
            ->with('error', 'Error de validación: ' . json_encode($e->errors()));
    }

    DB::beginTransaction();

    try {
        $archivo = $request->file('pdf_firmado');
        
        \Log::info('📄 Archivo recibido', [
            'nombre_original' => $archivo->getClientOriginalName(),
            'tamaño' => $archivo->getSize(),
            'mime_type' => $archivo->getMimeType(),
        ]);

        // ✅ Generar nombre único
        $nombreArchivo = 'cert_firmado_' . $certificado->codigo_qr . '.pdf';
        
        \Log::info('📝 Nombre generado: ' . $nombreArchivo);

        // ✅ Guardar en storage/public/certificados
        // ⚠️ IMPORTANTE: Guardar en 'public' disk
        $rutaArchivo = $archivo->storeAs(
            'certificados',
            $nombreArchivo,
            'public'
        );

        \Log::info('💾 Guardado en storage', [
            'ruta_relativa' => $rutaArchivo,
        ]);

        // ✅ Verificar que existe - USAR storage_path CON 'public'
        $rutaCompleta = storage_path('app/public/' . $rutaArchivo);
        
        \Log::info('🔍 Verificando archivo', [
            'ruta_completa' => $rutaCompleta,
            'existe' => file_exists($rutaCompleta),
            'tamaño' => file_exists($rutaCompleta) ? filesize($rutaCompleta) : 0,
        ]);

        if (!file_exists($rutaCompleta)) {
            throw new \Exception("Archivo no encontrado después de guardar: {$rutaCompleta}");
        }

        \Log::info('✅ Archivo existe en disco');

        // ✅ Actualizar certificado EN LA BD
        // ⚠️ IMPORTANTE: Guardar la ruta relativa 'public/certificados/...'
        \Log::info('📝 Actualizando certificado en BD');
        
        $rutaGuardar = 'public/' . $rutaArchivo;
        
        $actualizado = $certificado->update([
            'pdf_firmado_url' => $rutaGuardar,  // ✅ public/certificados/cert_firmado_...
            'firmado' => true,
            'fecha_firmado' => now(),
            'firmado_por_user_id' => auth()->id(),
            'estado' => 'emitido',
        ]);

        \Log::info('📝 Resultado de update()', [
            'actualizado' => $actualizado,
            'certificado_id' => $certificado->id,
            'ruta_guardada' => $rutaGuardar,
        ]);

        // ✅ Verificar que se actualizó
        $certificadoVerificacion = Certificado::find($certificado->id);
        
        \Log::info('✅ Verificación POST-UPDATE', [
            'firmado' => $certificadoVerificacion->firmado,
            'pdf_firmado_url' => $certificadoVerificacion->pdf_firmado_url,
            'estado' => $certificadoVerificacion->estado,
            'fecha_firmado' => $certificadoVerificacion->fecha_firmado,
        ]);

        DB::commit();

        \Log::info('✅ ÉXITO - Transacción completada');

        return redirect()->route('certificados.index')
            ->with('success', '✓ Certificado firmado subido correctamente');

    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('❌ ERROR EN subirPDFFirmado', [
            'error' => $e->getMessage(),
            'archivo' => $e->getFile(),
            'línea' => $e->getLine(),
        ]);

        return redirect()->back()
            ->with('error', '❌ Error: ' . $e->getMessage());
    }
}
}