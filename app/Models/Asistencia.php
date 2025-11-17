<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'inscripcion_id',
        'curso_id',
        'numero_sesion',
        'fecha_sesion',
        'hora_registro',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_sesion' => 'date',
        'hora_registro' => 'datetime',
        'numero_sesion' => 'integer',
    ];

    // Relación: Asistencia pertenece a una inscripción
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }

    // Relación: Asistencia pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}