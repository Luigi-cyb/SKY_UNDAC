<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $fillable = [
        'user_id',
        'dni',
        'codigo_estudiante',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'telefono_emergencia',
        'direccion',
        'correo_personal',
        'correo_institucional',
        'pertenece_eisc',
        'ciclo_academico',
        'foto_url',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'pertenece_eisc' => 'boolean',
        'activo' => 'boolean',
    ];

    // Relación: Un estudiante pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: Un estudiante tiene muchas inscripciones
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'estudiante_id');
    }

    // Relación: Un estudiante puede estar en lista de espera de varios cursos
    public function listaEspera()
    {
        return $this->hasMany(ListaEspera::class, 'estudiante_id');
    }

    // Relación: Un estudiante tiene muchas descargas de materiales
    public function descargasMateriales()
    {
        return $this->hasMany(DescargaMaterial::class, 'estudiante_id');
    }

    // Relación: Un estudiante tiene muchas respuestas a encuestas
    public function respuestasEncuestas()
    {
        return $this->hasMany(RespuestaEncuesta::class, 'estudiante_id');
    }

    // Accessor: Nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}