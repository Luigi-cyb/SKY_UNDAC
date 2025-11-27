<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaEvaluacion extends Model
{
    use HasFactory;

    protected $table = 'respuestas_evaluacion';

    protected $fillable = [
        'inscripcion_id',
        'evaluacion_id',
        'intento_id',          // ✅ IMPORTANTE: para asociar al intento
        'pregunta_id',
        'opcion_id',
        'respuesta_texto',
        'es_correcta',
        'puntos_obtenidos',
        'fecha_respuesta'
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
        'puntos_obtenidos' => 'decimal:2',
        'fecha_respuesta' => 'datetime'
    ];

    // Relaciones
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class);
    }

    public function intento()
    {
        return $this->belongsTo(IntentoEvaluacion::class, 'intento_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(PreguntaEvaluacion::class, 'pregunta_id');
    }

    public function opcion()
    {
        return $this->belongsTo(OpcionPregunta::class, 'opcion_id');
    }
}