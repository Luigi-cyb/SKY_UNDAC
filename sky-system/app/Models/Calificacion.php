<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'inscripcion_id',
        'evaluacion_id',
        'nota',
        'observaciones',
        'evidencia_url',
        'fecha_registro',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'fecha_registro' => 'datetime',
    ];

    // Relación: Calificación pertenece a una inscripción
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }

    // Relación: Calificación pertenece a una evaluación
    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'evaluacion_id');
    }
}