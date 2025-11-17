<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'estudiante_id',
        'curso_id',
        'codigo_inscripcion',
        'fecha_inscripcion',
        'estado',
        'documentos_url',
        'pago_confirmado',
        'nota_final',
        'porcentaje_asistencia',
        'aprobado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inscripcion' => 'date',
        'pago_confirmado' => 'boolean',
        'aprobado' => 'boolean',
        'nota_final' => 'decimal:2',
        'porcentaje_asistencia' => 'integer',
    ];

    // Relación: Una inscripción pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Una inscripción pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación: Una inscripción tiene muchas asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'inscripcion_id');
    }

    // Relación: Una inscripción tiene muchas calificaciones
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'inscripcion_id');
    }

    // Relación: Una inscripción tiene muchos pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'inscripcion_id');
    }

    // Relación: Obtener el pago más reciente (singular)
    public function pago()
    {
        return $this->hasOne(Pago::class, 'inscripcion_id')->latestOfMany();
    }

    // Relación: Una inscripción puede tener un certificado
    public function certificado()
    {
        return $this->hasOne(Certificado::class, 'inscripcion_id');
    }
}