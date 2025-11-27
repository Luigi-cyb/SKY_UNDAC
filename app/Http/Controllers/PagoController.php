<?php

namespace App\Http\Controllers;
use App\Models\Pago;
use App\Models\Inscripcion;
use App\Models\MetodoPago;
use App\Models\Comprobante;
use App\Http\Requests\PagoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // abort_unless(Auth::user()->can('pagos.ver'), 403); // COMENTADO TEMPORALMENTE

    try {
        $query = Pago::with([
            'inscripcion.estudiante',
            'inscripcion.curso',
            'metodoPago',
            'comprobante'
        ]);

        // Búsqueda por código de pago
        // 🔍 BÚSQUEDA GENERAL (DNI, nombre, código de pago, número de operación)
if ($request->filled('search')) {
    $searchTerm = $request->search;
    
    $query->where(function($q) use ($searchTerm) {
        // Buscar en código de pago
        $q->where('codigo_pago', 'like', '%' . $searchTerm . '%')
          // Buscar en número de operación
          ->orWhere('numero_operacion', 'like', '%' . $searchTerm . '%')
          // Buscar en datos del estudiante
          ->orWhereHas('inscripcion.estudiante', function($subQ) use ($searchTerm) {
              $subQ->where('nombres', 'like', '%' . $searchTerm . '%')
                   ->orWhere('apellidos', 'like', '%' . $searchTerm . '%')
                   ->orWhere('dni', 'like', '%' . $searchTerm . '%');
          });
    });
}

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por método de pago
        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago_id', $request->metodo_pago);
        }

        // NUEVO - Ordenamiento alfabético:
// Obtener pagos con relaciones
$allPagos = $query->get();

// Ordenar por apellidos del estudiante
$sortedPagos = $allPagos->sortBy(function($pago) {
    return $pago->inscripcion->estudiante->apellidos . ' ' . 
           $pago->inscripcion->estudiante->nombres;
});

// Paginar manualmente
$page = $request->get('page', 1);
$perPage = 15;
$pagos = new \Illuminate\Pagination\LengthAwarePaginator(
    $sortedPagos->forPage($page, $perPage)->values(),
    $sortedPagos->count(),
    $perPage,
    $page,
    ['path' => $request->url(), 'query' => $request->except('page')]
);

        // Estadísticas
        $totalPagos = Pago::count();
        $pagosConfirmados = Pago::where('estado', 'confirmado')->count();
        $pagosPendientes = Pago::where('estado', 'pendiente')->count();
        $pagosRechazados = Pago::where('estado', 'rechazado')->count();
        $montoTotal = Pago::where('estado', 'confirmado')->sum('monto');
        $montoPendiente = Pago::where('estado', 'pendiente')->sum('monto');

        $metodosPago = MetodoPago::where('activo', true)->get();

        return view('pagos.index', compact('pagos', 'totalPagos', 'pagosConfirmados', 'pagosPendientes', 'pagosRechazados', 'montoTotal', 'montoPendiente', 'metodosPago'));

    } catch (\Exception $e) {
        Log::error('Error al listar pagos: ' . $e->getMessage());
        return back()->with('error', 'Error al cargar los pagos: ' . $e->getMessage());
    }
}
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        abort_unless(Auth::user()->can('pagos.registrar'), 403);

        try {
            $inscripcion_id = $request->inscripcion_id;
            
            // Obtener inscripciones pendientes de pago
            $inscripciones = Inscripcion::with(['estudiante.user', 'curso'])
                ->where(function($query) {
                    $query->where('estado', 'provisional')
                          ->orWhere('pago_confirmado', false);
                })
                ->whereDoesntHave('pagos', function($query) {
                    $query->where('estado', 'confirmado');
                })
                ->get();
            
            $metodosPago = MetodoPago::where('activo', true)->get();

            // Si viene de una inscripción específica, cargar sus datos
            $inscripcionSeleccionada = null;
            if ($inscripcion_id) {
                $inscripcionSeleccionada = Inscripcion::with(['estudiante', 'curso'])
                    ->find($inscripcion_id);
            }

            return view('pagos.create', compact(
                'inscripciones', 
                'metodosPago', 
                'inscripcion_id',
                'inscripcionSeleccionada'
            ));

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de pago: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PagoRequest $request)
{
    // abort_unless(Auth::user()->can('pagos.registrar'), 403); // COMENTADO

    DB::beginTransaction();
    try {
        $validated = $request->validated();

        $inscripcion = Inscripcion::with('curso')->findOrFail($validated['inscripcion_id']);

        // Validar que la inscripción no tenga ya un pago confirmado
        $pagoExistente = Pago::where('inscripcion_id', $inscripcion->id)
            ->where('estado', 'confirmado')
            ->first();

        if ($pagoExistente) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Esta inscripción ya tiene un pago confirmado.');
        }

        // Generar código de pago único
        $codigoPago = $this->generarCodigoPagoUnico();

        // Crear pago
        $pago = Pago::create([
            'inscripcion_id' => $validated['inscripcion_id'],
            'metodo_pago_id' => $validated['metodo_pago_id'],
            'codigo_pago' => $codigoPago,
            'monto' => $validated['monto'],
            'fecha_pago' => $validated['fecha_pago'],
            'numero_operacion' => $validated['numero_operacion'] ?? null,
            'estado' => 'pendiente',
            'descripcion' => $validated['descripcion'] ?? null,
            'registrado_por' => Auth::id(),
        ]);

        DB::commit();

        Log::info('Pago registrado exitosamente', [
            'pago_id' => $pago->id,
            'codigo' => $codigoPago,
        ]);

        return redirect()->route('pagos.index')
            ->with('success', '✅ Pago registrado exitosamente. Código: ' . $codigoPago);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al registrar pago: ' . $e->getMessage());
        return back()
            ->withInput()
            ->with('error', 'Error al registrar el pago: ' . $e->getMessage());
    }
}
    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        abort_unless(Auth::user()->can('pagos.ver'), 403);

        try {
            $pago->load([
                'inscripcion.estudiante.user',
                'inscripcion.curso',
                'metodoPago',
                'comprobante'
            ]);

            // 📊 Historial de cambios del pago
            $historial = DB::table('pagos')
                ->where('inscripcion_id', $pago->inscripcion_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('pagos.show', compact('pago', 'historial'));

        } catch (\Exception $e) {
            Log::error('Error al mostrar pago: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        abort_unless(Auth::user()->can('pagos.editar'), 403);

        try {
            // ✅ No permitir editar pagos confirmados
            if ($pago->estado === 'confirmado') {
                return back()->with('error', 'No se puede editar un pago ya confirmado.');
            }

            $pago->load(['inscripcion.estudiante', 'inscripcion.curso']);
            $metodosPago = MetodoPago::where('activo', true)->get();

            return view('pagos.edit', compact('pago', 'metodosPago'));

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PagoRequest $request, Pago $pago)
    {
        abort_unless(Auth::user()->can('pagos.editar'), 403);

        DB::beginTransaction();
        try {
            // ✅ No permitir editar pagos confirmados
            if ($pago->estado === 'confirmado') {
                DB::rollBack();
                return back()->with('error', 'No se puede editar un pago ya confirmado.');
            }

            $validated = $request->validated();

            // 📝 Guardar cambios
            $pago->update([
                'metodo_pago_id' => $validated['metodo_pago_id'],
                'monto' => $validated['monto'],
                'fecha_pago' => $validated['fecha_pago'],
                'numero_operacion' => $validated['numero_operacion'] ?? null,
                'descripcion' => $validated['descripcion'] ?? null,
                'modificado_por' => Auth::id(),
            ]);

            DB::commit();

            Log::info('Pago actualizado', [
                'pago_id' => $pago->id,
                'usuario' => Auth::user()->email
            ]);

            return redirect()->route('pagos.show', $pago)
                ->with('success', '✅ Pago actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar pago: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        abort_unless(Auth::user()->can('pagos.eliminar'), 403);

        DB::beginTransaction();
        try {
            // ✅ No permitir eliminar pagos confirmados
            if ($pago->estado === 'confirmado') {
                DB::rollBack();
                return back()->with('error', 'No se puede eliminar un pago confirmado.');
            }

            // 🗑️ Eliminar comprobante si existe
            if ($pago->comprobante) {
                $pago->comprobante->delete();
            }

            $codigoPago = $pago->codigo_pago;
            $pago->delete();

            DB::commit();

            Log::info('Pago eliminado', [
                'codigo' => $codigoPago,
                'usuario' => Auth::user()->email
            ]);

            return redirect()->route('pagos.index')
                ->with('success', '✅ Pago eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar pago: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar pago
     */
    public function confirmar(Pago $pago)
    {
        abort_unless(Auth::user()->can('pagos.confirmar'), 403);

        DB::beginTransaction();
        try {
            // ✅ Validar que el pago esté pendiente
            if ($pago->estado !== 'pendiente') {
                DB::rollBack();
                return back()->with('error', 'Solo se pueden confirmar pagos pendientes.');
            }

            // 💰 Confirmar pago
            $pago->update([
                'estado' => 'confirmado',
                'fecha_confirmacion' => now(),
                'confirmado_por' => Auth::id(),
            ]);

            // 📝 Actualizar inscripción
            $pago->inscripcion->update([
                'pago_confirmado' => true,
                'estado' => 'confirmada',
                'fecha_confirmacion' => now(),
            ]);

            // 📄 Generar comprobante automáticamente
            $comprobante = $this->generarComprobante($pago);

            // 📧 Enviar notificación con comprobante
            $this->enviarNotificacionPagoConfirmado($pago, $comprobante);

            DB::commit();

            Log::info('Pago confirmado exitosamente', [
                'pago_id' => $pago->id,
                'comprobante' => $comprobante->numero_comprobante,
                'usuario' => Auth::user()->email
            ]);

            return redirect()->back()
                ->with('success', '✅ Pago confirmado exitosamente. Comprobante generado.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al confirmar pago: ' . $e->getMessage());
            return back()->with('error', 'Error al confirmar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar pago
     */
    public function rechazar(Request $request, Pago $pago)
    {
        abort_unless(Auth::user()->can('pagos.confirmar'), 403);

        DB::beginTransaction();
        try {
            // ✅ Validar motivo
            $request->validate([
                'motivo' => 'required|string|min:10|max:500'
            ], [
                'motivo.required' => 'Debe especificar el motivo del rechazo',
                'motivo.min' => 'El motivo debe tener al menos 10 caracteres',
            ]);

            // ✅ Validar que el pago esté pendiente
            if ($pago->estado !== 'pendiente') {
                DB::rollBack();
                return back()->with('error', 'Solo se pueden rechazar pagos pendientes.');
            }

            // ❌ Rechazar pago
            $pago->update([
                'estado' => 'rechazado',
                'motivo_rechazo' => $request->motivo,
                'rechazado_por' => Auth::id(),
                'fecha_rechazo' => now(),
            ]);

            // 📧 Notificar al estudiante
            $this->enviarNotificacionPagoRechazado($pago, $request->motivo);

            DB::commit();

            Log::info('Pago rechazado', [
                'pago_id' => $pago->id,
                'motivo' => $request->motivo,
                'usuario' => Auth::user()->email
            ]);

            return redirect()->back()
                ->with('success', '✅ Pago rechazado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al rechazar pago: ' . $e->getMessage());
            return back()->with('error', 'Error al rechazar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Generar comprobante de pago
     */
   private function generarComprobante(Pago $pago): Comprobante
{
    try {
        $pago->load('inscripcion.estudiante');
        
        $numeroComprobante = $this->generarNumeroComprobanteUnico();
        $ultimoNumero = Comprobante::where('serie', 'COMP-' . date('Y'))->max('numero') ?? 0;
        
        $estudiante = $pago->inscripcion->estudiante;
        
        // Calcular IGV (18%)
        $subtotal = round($pago->monto / 1.18, 2);
        $igv = round($pago->monto - $subtotal, 2);

        $comprobante = Comprobante::create([
            'pago_id' => $pago->id,
            'numero_comprobante' => $numeroComprobante,
            'serie' => 'COMP-' . date('Y'),
            'numero' => (string)($ultimoNumero + 1),
            'ruc_dni' => $estudiante->dni,
            'razon_social' => $estudiante->nombres . ' ' . $estudiante->apellidos,
            'fecha_emision' => now()->format('Y-m-d'),
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $pago->monto,
            'monto_total' => $pago->monto,
            'tipo_comprobante' => 'recibo',
            'emitido_por' => Auth::id(),
        ]);

        Log::info('Comprobante generado', ['comprobante_id' => $comprobante->id]);
        return $comprobante;

    } catch (\Exception $e) {
        Log::error('Error al generar comprobante: ' . $e->getMessage());
        throw $e;
    }
}
    /**
     * Descargar comprobante en PDF
     */
    public function descargarComprobante(Pago $pago)
{
    // abort_unless(Auth::user()->can('comprobantes.descargar'), 403); // ← COMENTADO

    try {
        $pago->load([
            'inscripcion.estudiante',
            'inscripcion.curso',
            'metodoPago',
            'comprobante'
        ]);

        // Validar que exista comprobante
        if (!$pago->comprobante) {
            return back()->with('error', 'Este pago no tiene comprobante generado.');
        }

        // Datos adicionales para el PDF
        $datos = [
            'pago' => $pago,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
            'usuario_generador' => Auth::user()->name,
        ];

        // Generar PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pagos.comprobante', $datos);
        
        // Descargar con nombre descriptivo
        $nombreArchivo = 'comprobante-' . $pago->comprobante->numero_comprobante . '.pdf';

        // Registrar descarga
        Log::info('Comprobante descargado', [
            'pago_id' => $pago->id,
            'comprobante' => $pago->comprobante->numero_comprobante,
            'usuario' => Auth::user()->email
        ]);

        return $pdf->download($nombreArchivo);

    } catch (\Exception $e) {
        Log::error('Error al descargar comprobante: ' . $e->getMessage());
        return back()->with('error', 'Error al generar el comprobante: ' . $e->getMessage());
    }
}
    /**
     * Reenviar comprobante por email
     */
    public function reenviarComprobante(Pago $pago)
    {
        abort_unless(Auth::user()->can('comprobantes.descargar'), 403);

        try {
            $pago->load([
                'inscripcion.estudiante.user',
                'comprobante'
            ]);

            if (!$pago->comprobante) {
                return back()->with('error', 'Este pago no tiene comprobante generado.');
            }

            $this->enviarNotificacionPagoConfirmado($pago, $pago->comprobante);

            return back()->with('success', '✅ Comprobante reenviado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al reenviar comprobante: ' . $e->getMessage());
            return back()->with('error', 'Error al reenviar el comprobante.');
        }
    }

    /**
     * Búsqueda avanzada de pagos
     */
    public function buscar(Request $request)
    {
        abort_unless(Auth::user()->can('pagos.ver'), 403);

        try {
            $termino = $request->get('q');

            $pagos = Pago::with([
                'inscripcion.estudiante.user',
                'inscripcion.curso',
                'metodoPago'
            ])
            ->where('codigo_pago', 'like', '%' . $termino . '%')
            ->orWhere('numero_operacion', 'like', '%' . $termino . '%')
            ->orWhereHas('inscripcion.estudiante', function($query) use ($termino) {
                $query->where('nombres', 'like', '%' . $termino . '%')
                      ->orWhere('apellidos', 'like', '%' . $termino . '%')
                      ->orWhere('dni', 'like', '%' . $termino . '%');
            })
            ->limit(10)
            ->get();

            return response()->json($pagos);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de pagos: ' . $e->getMessage());
            return response()->json(['error' => 'Error en la búsqueda'], 500);
        }
    }

    /**
     * Reportes de pagos
     */
    public function reportes(Request $request)
    {
        abort_unless(Auth::user()->can('reportes.ver'), 403);

        try {
            $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
            $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

            // 📊 Estadísticas detalladas
            $estadisticas = [
                'total_ingresos' => Pago::where('estado', 'confirmado')
                    ->whereBetween('fecha_confirmacion', [$fechaInicio, $fechaFin])
                    ->sum('monto'),
                
                'pagos_por_metodo' => Pago::where('estado', 'confirmado')
                    ->whereBetween('fecha_confirmacion', [$fechaInicio, $fechaFin])
                    ->with('metodoPago')
                    ->get()
                    ->groupBy('metodo_pago_id')
                    ->map(function($pagos) {
                        return [
                            'metodo' => $pagos->first()->metodoPago->nombre,
                            'cantidad' => $pagos->count(),
                            'monto_total' => $pagos->sum('monto')
                        ];
                    }),

                'pagos_por_estado' => Pago::whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->groupBy('estado')
                    ->selectRaw('estado, count(*) as cantidad, sum(monto) as monto_total')
                    ->get(),

                'pagos_diarios' => Pago::where('estado', 'confirmado')
                    ->whereBetween('fecha_confirmacion', [$fechaInicio, $fechaFin])
                    ->groupBy(DB::raw('DATE(fecha_confirmacion)'))
                    ->selectRaw('DATE(fecha_confirmacion) as fecha, count(*) as cantidad, sum(monto) as monto')
                    ->orderBy('fecha', 'asc')
                    ->get(),
            ];

            return view('pagos.reportes', compact('estadisticas', 'fechaInicio', 'fechaFin'));

        } catch (\Exception $e) {
            Log::error('Error al generar reportes de pagos: ' . $e->getMessage());
            return back()->with('error', 'Error al generar los reportes.');
        }
    }

    /**
     * Conciliación de pagos
     */
    public function conciliar(Request $request)
    {
        abort_unless(Auth::user()->can('pagos.conciliar'), 403);

        DB::beginTransaction();
        try {
            $fecha = $request->get('fecha', now()->format('Y-m-d'));

            // 📊 Pagos del día
            $pagosDia = Pago::whereDate('fecha_pago', $fecha)
                ->where('estado', 'confirmado')
                ->with(['inscripcion.curso', 'metodoPago'])
                ->get();

            $resumen = [
                'total_pagos' => $pagosDia->count(),
                'monto_total' => $pagosDia->sum('monto'),
                'por_metodo' => $pagosDia->groupBy('metodo_pago_id')->map(function($pagos) {
                    return [
                        'metodo' => $pagos->first()->metodoPago->nombre,
                        'cantidad' => $pagos->count(),
                        'monto' => $pagos->sum('monto')
                    ];
                })
            ];

            DB::commit();

            return view('pagos.conciliacion', compact('pagosDia', 'resumen', 'fecha'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en conciliación: ' . $e->getMessage());
            return back()->with('error', 'Error al realizar la conciliación.');
        }
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Generar código de pago único
     */
    private function generarCodigoPagoUnico(): string
    {
        do {
            $codigo = 'PAY-' . date('Y') . '-' . strtoupper(Str::random(8));
        } while (Pago::where('codigo_pago', $codigo)->exists());

        return $codigo;
    }

    /**
     * Generar número de comprobante único
     */
    private function generarNumeroComprobanteUnico(): string
    {
        $ultimoComprobante = Comprobante::orderBy('id', 'desc')->first();
        $numero = $ultimoComprobante ? ($ultimoComprobante->id + 1) : 1;
        
        return 'COMP-' . date('Y') . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Enviar notificación de pago registrado
     */
    private function enviarNotificacionPagoRegistrado(Pago $pago): void
    {
        try {
            $estudiante = $pago->inscripcion->estudiante;
            
            // TODO: Implementar envío real de email
            Log::info('Notificación de pago registrado enviada', [
                'pago_id' => $pago->id,
                'estudiante' => $estudiante->user->email
            ]);

        } catch (\Exception $e) {
            Log::warning('Error al enviar notificación de pago registrado: ' . $e->getMessage());
        }
    }

    /**
     * Enviar notificación de pago confirmado
     */
    private function enviarNotificacionPagoConfirmado(Pago $pago, Comprobante $comprobante): void
    {
        try {
            $estudiante = $pago->inscripcion->estudiante;
            
            // TODO: Implementar envío real de email con PDF adjunto
            Log::info('Notificación de pago confirmado enviada', [
                'pago_id' => $pago->id,
                'comprobante' => $comprobante->numero_comprobante,
                'estudiante' => $estudiante->user->email
            ]);

        } catch (\Exception $e) {
            Log::warning('Error al enviar notificación de pago confirmado: ' . $e->getMessage());
        }
    }

    /**
     * Enviar notificación de pago rechazado
     */
    private function enviarNotificacionPagoRechazado(Pago $pago, string $motivo): void
    {
        try {
            $estudiante = $pago->inscripcion->estudiante;
            
            // TODO: Implementar envío real de email
            Log::info('Notificación de pago rechazado enviada', [
                'pago_id' => $pago->id,
                'motivo' => $motivo,
                'estudiante' => $estudiante->user->email
            ]);

        } catch (\Exception $e) {
            Log::warning('Error al enviar notificación de pago rechazado: ' . $e->getMessage());
        }
    }
public function pagosPendientes()
{
    // Obtener solo pagos pendientes
    $pagos = Pago::with([
        'inscripcion.estudiante.user',
        'inscripcion.curso',
        'metodoPago'
    ])
    ->where('estado', 'pendiente')
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    return view('admin.pagos.pendientes', compact('pagos'));
}

public function confirmarManual(Request $request, Pago $pago)
{
    DB::beginTransaction();
    
    try {
        $pago->update([
            'estado' => 'confirmado',
            'fecha_confirmacion' => now(),
            'confirmado_por' => auth()->id(),
        ]);

        // Actualizar inscripción
        $pago->inscripcion->update([
            'pago_confirmado' => true,
        ]);

        DB::commit();

        return redirect()->back()
            ->with('success', '✓ Pago confirmado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al confirmar pago: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', '✗ Error al confirmar el pago.');
    }
}

public function rechazarManual(Request $request, Pago $pago)
{
    $request->validate([
        'motivo_rechazo' => 'required|string|max:500'
    ]);

    DB::beginTransaction();
    
    try {
        $pago->update([
            'estado' => 'rechazado',
            'rechazado_por' => auth()->id(),
            'fecha_rechazo' => now(),
            'motivo_rechazo' => $request->motivo_rechazo
        ]);

        DB::commit();

        return redirect()->back()
            ->with('success', '✓ Pago rechazado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al rechazar pago: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', '✗ Error al rechazar el pago.');
    }
}

}