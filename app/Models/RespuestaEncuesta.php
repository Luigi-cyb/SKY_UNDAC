<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaEncuesta extends Model
{
    use HasFactory;

    protected $table = 'respuestas_encuestas';

    protected $fillable = [
        'encuesta_id',
        'estudiante_id',
        'respuestas',
        'fecha_respuesta',
    ];

    protected $casts = [
        'respuestas' => 'array',
        'fecha_respuesta' => 'datetime',
    ];

    // Relación: Respuesta pertenece a una encuesta
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'encuesta_id');
    }

    // Relación: Respuesta pertenece a un estudiante (nullable si es anónima)
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }
}