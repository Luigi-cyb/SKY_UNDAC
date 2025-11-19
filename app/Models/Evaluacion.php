<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';

    protected $fillable = [
        'curso_id',
        'nombre',
        'descripcion',
        'tipo',
        'peso_porcentaje',
        'fecha_evaluacion',
        'nota_maxima',
        'nota_minima_aprobacion',
        'criterios_evaluacion',
        'activo',
        'fecha_disponible',
        'fecha_limite',
        'duracion_minutos',
        'numero_intentos_permitidos',
        'mostrar_respuestas_correctas',
        'aleatorizar_preguntas',
        'requiere_aprobar',
        'instrucciones',
    ];

    protected $casts = [
        'fecha_evaluacion' => 'date',
        'fecha_disponible' => 'datetime',
        'fecha_limite' => 'datetime',
        'activo' => 'boolean',
        'mostrar_respuestas_correctas' => 'boolean',
        'aleatorizar_preguntas' => 'boolean',
        'requiere_aprobar' => 'boolean',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    /**
     * ✅ Relación con preguntas
     */
    public function preguntas(): HasMany
    {
        return $this->hasMany(PreguntaEvaluacion::class, 'evaluacion_id');
    }

    /**
     * ✅ Relación con intentos
     */
    public function intentos(): HasMany
    {
        return $this->hasMany(IntentoEvaluacion::class, 'evaluacion_id');
    }

    /**
     * ✅ Relación con respuestas
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(RespuestaEvaluacion::class, 'evaluacion_id');
    }
}