<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $table = 'encuestas';

    protected $fillable = [
        'curso_id',
        'titulo',
        'descripcion',
        'preguntas',
        'fecha_inicio',
        'fecha_fin',
        'anonima',
        'activa',
    ];

    protected $casts = [
        'preguntas' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'anonima' => 'boolean',
        'activa' => 'boolean',
    ];

    // Relación: Encuesta pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación: Una encuesta tiene muchas respuestas
    public function respuestas()
    {
        return $this->hasMany(RespuestaEncuesta::class, 'encuesta_id');
    }
}