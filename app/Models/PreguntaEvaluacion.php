<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntaEvaluacion extends Model
{
    use HasFactory;

    protected $table = 'preguntas_evaluacion';

    protected $fillable = [
        'evaluacion_id',
        'numero_pregunta',
        'enunciado',
        'tipo_pregunta',
        'puntos',
        'obligatoria',
        'imagen_url',
        'orden',
        'respuesta_correcta', // 🆕 NUEVO - Para preguntas de respuesta corta
    ];

    protected $casts = [
        'puntos' => 'decimal:2',
        'obligatoria' => 'boolean'
    ];

    // 🆕 ACCESSOR: Alias para texto_pregunta
    public function getTextoPreguntaAttribute()
    {
        return $this->enunciado;
    }

    // 🆕 MUTATOR: Alias para texto_pregunta
    public function setTextoPreguntaAttribute($value)
    {
        $this->attributes['enunciado'] = $value;
    }

    // Relaciones
    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class);
    }

    public function opciones()
    {
        return $this->hasMany(OpcionPregunta::class, 'pregunta_id')->orderBy('orden');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaEvaluacion::class, 'pregunta_id');
    }

    /**
     * 🆕 Obtener la opción correcta (para preguntas de opción múltiple)
     */
    public function opcionCorrecta()
    {
        return $this->hasOne(OpcionPregunta::class, 'pregunta_id')
            ->where('es_correcta', true);
    }

    /**
     * 🆕 Verificar si la respuesta del estudiante es correcta
     */
    public function esRespuestaCorrecta($respuesta)
    {
        if ($this->tipo_pregunta === 'respuesta_corta') {
            return strtolower(trim($respuesta)) === strtolower(trim($this->respuesta_correcta));
        }

        if (in_array($this->tipo_pregunta, ['multiple', 'verdadero_falso'])) {
            $opcionCorrecta = $this->opcionCorrecta;
            return $opcionCorrecta && $opcionCorrecta->id == $respuesta;
        }

        return false;
    }

    /**
     * 🆕 Obtener estadísticas de respuestas de esta pregunta
     */
    public function estadisticas()
    {
        $totalRespuestas = $this->respuestas()->count();
        $respuestasCorrectas = $this->respuestas()
            ->where('es_correcta', true)
            ->count();

        return [
            'total_respuestas' => $totalRespuestas,
            'correctas' => $respuestasCorrectas,
            'incorrectas' => $totalRespuestas - $respuestasCorrectas,
            'porcentaje_acierto' => $totalRespuestas > 0 
                ? round(($respuestasCorrectas / $totalRespuestas) * 100, 2) 
                : 0,
        ];
    }

    // Métodos auxiliares (ya existentes)
    public function getTipoPreguntaTexto()
    {
        return match($this->tipo_pregunta) {
            'multiple' => 'Opción Múltiple',
            'verdadero_falso' => 'Verdadero/Falso',
            'respuesta_corta' => 'Respuesta Corta',
            'corta' => 'Respuesta Corta', // 🆕 Alias
            default => 'Desconocido'
        };
    }

    public function getOpcionCorrecta()
    {
        return $this->opciones()->where('es_correcta', true)->first();
    }

    /**
     * 🆕 Verificar si la pregunta tiene respuestas de estudiantes
     */
    public function tieneRespuestas()
    {
        return $this->respuestas()->exists();
    }

    /**
     * 🆕 Scope: Ordenar por orden
     */
    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden');
    }

    /**
     * 🆕 Scope: Filtrar por tipo de pregunta
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_pregunta', $tipo);
    }

    /**
     * 🆕 Boot method para eventos del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Al eliminar una pregunta, eliminar sus opciones
        static::deleting(function ($pregunta) {
            $pregunta->opciones()->delete();
        });
    }
}