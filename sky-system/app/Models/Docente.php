<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';

    protected $fillable = [
        'user_id',
        'dni',
        'codigo_docente',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'direccion',
        'correo_personal',
        'correo_institucional',
        'formacion_academica',
        'experiencia_profesional',
        'especialidades',
        'cv_url',
        'foto_url',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    // Relación: Un docente pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: Un docente tiene muchas asignaciones a cursos
    public function asignaciones()
    {
        return $this->hasMany(AsignacionDocente::class, 'docente_id');
    }

    // Relación: Un docente puede subir muchos materiales
    public function materiales()
    {
        return $this->hasMany(MaterialCurso::class, 'docente_id');
    }

    // Accessor: Nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}