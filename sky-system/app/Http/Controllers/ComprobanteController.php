<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Pago;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ComprobanteController extends Controller
{
    public function index(Request $request)
    {
        $query = Comprobante::with(['pago.inscripcion.estudiante', 'pago.inscripcion.curso']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_comprobante', 'like', "%{$search}%")
                  ->orWhere('tipo_comprobante', 'like', "%{$search}%");
            });
        }

        $comprobantes = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('comprobantes.index', compact('comprobantes'));
    }

    public function create()
    {
        $pagos = Pago::with(['inscripcion.estudiante', 'inscripcion.curso'])
            ->where('estado', 'confirmado')
            ->whereDoesntHave('comprobante')
            ->get();

        return view('comprobantes.create', compact('pagos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pago_id' => 'required|exists:pagos,id',
            'tipo_comprobante' => 'required|in:Boleta,Factura,Recibo',
        ]);

        $numero = $this->generarNumeroComprobante();
        $validated['numero_comprobante'] = $numero;
        $validated['fecha_emision'] = now();

        $comprobante = Comprobante::create($validated);

        return redirect()->route('comprobantes.show', $comprobante)
            ->with('success', 'Comprobante generado exitosamente.');
    }

    public function show(Comprobante $comprobante)
    {
        $comprobante->load(['pago.inscripcion.estudiante', 'pago.inscripcion.curso', 'pago.metodoPago']);
        return view('comprobantes.show', compact('comprobante'));
    }

    public function edit(Comprobante $comprobante)
    {
        return view('comprobantes.edit', compact('comprobante'));
    }

    public function update(Request $request, Comprobante $comprobante)
    {
        $validated = $request->validate([
            'tipo_comprobante' => 'required|in:Boleta,Factura,Recibo',
        ]);

        $comprobante->update($validated);

        return redirect()->route('comprobantes.show', $comprobante)
            ->with('success', 'Comprobante actualizado.');
    }

    public function destroy(Comprobante $comprobante)
    {
        $comprobante->delete();
        return redirect()->route('comprobantes.index')
            ->with('success', 'Comprobante eliminado.');
    }

    public function pdf(Comprobante $comprobante)
    {
        $comprobante->load(['pago.inscripcion.estudiante', 'pago.inscripcion.curso', 'pago.metodoPago']);
        $pdf = Pdf::loadView('comprobantes.pdf', compact('comprobante'));
        return $pdf->download("comprobante_{$comprobante->numero_comprobante}.pdf");
    }

    public function descargar(Comprobante $comprobante)
    {
        return $this->pdf($comprobante);
    }

    public function reenviar(Comprobante $comprobante)
    {
        return back()->with('success', 'Comprobante reenviado exitosamente.');
    }

    private function generarNumeroComprobante()
    {
        $year = date('Y');
        $ultimo = Comprobante::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $numero = $ultimo ? intval(substr($ultimo->numero_comprobante, -6)) + 1 : 1;
        return "COMP-{$year}-" . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }
}