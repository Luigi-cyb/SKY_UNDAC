<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'objetivos',
        'competencias',
        'perfil_ingreso',
        'perfil_egreso',
        'categoria_id',
        'modalidad_id',
        'nivel',
        'horas_academicas',
        'cupo_minimo',
        'cupo_maximo',
        'fecha_inicio',
        'fecha_fin',
        'costo_inscripcion',
        'nota_minima_aprobacion',
        'asistencia_minima_porcentaje',
        'estado',
        'silabo_url',
        'temario',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'costo_inscripcion' => 'decimal:2',
        'nota_minima_aprobacion' => 'decimal:2',
        'horas_academicas' => 'integer',
        'cupo_minimo' => 'integer',
        'cupo_maximo' => 'integer',
        'asistencia_minima_porcentaje' => 'integer',
    ];

    // Relación: Un curso pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaCurso::class, 'categoria_id');
    }

    // Relación: Un curso pertenece a una modalidad
    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'modalidad_id');
    }

    // Relación: Un curso tiene muchas inscripciones
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'curso_id');
    }

    // Relación: Un curso tiene muchos estudiantes en lista de espera
    public function listaEspera()
    {
        return $this->hasMany(ListaEspera::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas asignaciones de docentes
    public function asignacionesDocentes()
    {
        return $this->hasMany(AsignacionDocente::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'curso_id');
    }

    // Relación: Un curso tiene muchos materiales
    public function materiales()
    {
        return $this->hasMany(MaterialCurso::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas encuestas
    public function encuestas()
    {
        return $this->hasMany(Encuesta::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'curso_id');
    }
}