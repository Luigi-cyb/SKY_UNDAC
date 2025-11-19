<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionPregunta extends Model
{
    use HasFactory;

    protected $table = 'opciones_pregunta';

    protected $fillable = [
        'pregunta_id',
        'texto_opcion',
        'es_correcta',
        'orden'
    ];

    protected $casts = [
        'es_correcta' => 'boolean'
    ];

    // Relaciones
    public function pregunta()
    {
        return $this->belongsTo(PreguntaEvaluacion::class, 'pregunta_id');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaEvaluacion::class, 'opcion_id');
    }
}