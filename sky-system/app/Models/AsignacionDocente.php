<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionDocente extends Model
{
    use HasFactory;

    protected $table = 'asignaciones_docentes';

    protected $fillable = [
        'docente_id',
        'curso_id',
        'tipo_asignacion',
        'carga_horaria',
        'fecha_asignacion',
        'activo',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'activo' => 'boolean',
        'carga_horaria' => 'integer',
    ];

    // Relación: Asignación pertenece a un docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }

    // Relación: Asignación pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}