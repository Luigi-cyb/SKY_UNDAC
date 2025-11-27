<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Evaluacion;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(Auth::user()->can('evaluaciones.ver'), 403);

        $evaluaciones = Evaluacion::with('curso')->where('activo', true)->get();

        return view('calificaciones.index', compact('evaluaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        abort_unless(Auth::user()->can('evaluaciones.calificar'), 403);

        $evaluacion_id = $request->evaluacion_id;
        $evaluacion = Evaluacion::with('curso.inscripciones.estudiante')->findOrFail($evaluacion_id);
        
        // Solo inscripciones confirmadas
        $inscripciones = $evaluacion->curso->inscripciones()
            ->where('estado', 'confirmada')
            ->with('estudiante')
            ->get();

        return view('calificaciones.create', compact('evaluacion', 'inscripciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('evaluaciones.calificar'), 403);

        $validated = $request->validate([
            'evaluacion_id' => 'required|exists:evaluaciones,id',
            'calificaciones' => 'required|array',
            'calificaciones.*.inscripcion_id' => 'required|exists:inscripciones,id',
            'calificaciones.*.nota' => 'required|numeric|min:0|max:20',
            'calificaciones.*.observaciones' => 'nullable|string',
        ]);

        $evaluacion = Evaluacion::findOrFail($validated['evaluacion_id']);

        foreach ($validated['calificaciones'] as $calificacionData) {
            Calificacion::updateOrCreate(
                [
                    'evaluacion_id' => $validated['evaluacion_id'],
                    'inscripcion_id' => $calificacionData['inscripcion_id'],
                ],
                [
                    'nota' => $calificacionData['nota'],
                    'observaciones' => $calificacionData['observaciones'] ?? null,
                    'fecha_registro' => now(),
                ]
            );
        }

        return redirect()->route('calificaciones.index')
            ->with('success', 'Calificaciones registradas exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($evaluacion_id)
    {
        abort_unless(Auth::user()->can('evaluaciones.ver'), 403);

        $evaluacion = Evaluacion::with('curso')->findOrFail($evaluacion_id);
        $calificaciones = Calificacion::where('evaluacion_id', $evaluacion_id)
            ->with('inscripcion.estudiante')
            ->get();

        return view('calificaciones.show', compact('evaluacion', 'calificaciones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($evaluacion_id)
    {
        abort_unless(Auth::user()->can('evaluaciones.editar'), 403);

        $evaluacion = Evaluacion::with('curso')->findOrFail($evaluacion_id);
        $calificaciones = Calificacion::where('evaluacion_id', $evaluacion_id)
            ->with('inscripcion.estudiante')
            ->get();

        return view('calificaciones.edit', compact('evaluacion', 'calificaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $evaluacion_id)
    {
        abort_unless(Auth::user()->can('evaluaciones.editar'), 403);

        $validated = $request->validate([
            'calificaciones' => 'required|array',
            'calificaciones.*.id' => 'required|exists:calificaciones,id',
            'calificaciones.*.nota' => 'required|numeric|min:0|max:20',
            'calificaciones.*.observaciones' => 'nullable|string',
        ]);

        foreach ($validated['calificaciones'] as $calificacionData) {
            $calificacion = Calificacion::find($calificacionData['id']);
            $calificacion->update([
                'nota' => $calificacionData['nota'],
                'observaciones' => $calificacionData['observaciones'] ?? null,
                'fecha_registro' => now(),
            ]);
        }

        return redirect()->route('calificaciones.index')
            ->with('success', 'Calificaciones actualizadas exitosamente.');
    }

    /**
     * Calcular promedio final del estudiante
     */
    public function promedioEstudiante($inscripcion_id)
    {
        abort_unless(Auth::user()->can('evaluaciones.ver'), 403);

        $inscripcion = Inscripcion::with(['estudiante', 'curso', 'calificaciones.evaluacion'])
            ->findOrFail($inscripcion_id);

        $calificaciones = $inscripcion->calificaciones;
        $promedio = 0;
        $pesoTotal = 0;

        foreach ($calificaciones as $calificacion) {
            if ($calificacion->evaluacion->activo) {
                $promedio += ($calificacion->nota * $calificacion->evaluacion->peso_porcentaje) / 100;
                $pesoTotal += $calificacion->evaluacion->peso_porcentaje;
            }
        }

        return view('calificaciones.promedio', compact('inscripcion', 'calificaciones', 'promedio', 'pesoTotal'));
    }
}