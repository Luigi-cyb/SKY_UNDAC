<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCurso extends Model
{
    use HasFactory;

    protected $table = 'materiales_curso';

    protected $fillable = [
        'curso_id',
        'docente_id',
        'titulo',
        'descripcion',
        'tipo',
        'archivo_url',
        'enlace_externo',
        'numero_sesion',
        'fecha_publicacion',
        'visible',
        'numero_descargas',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'visible' => 'boolean',
        'numero_descargas' => 'integer',
        'numero_sesion' => 'integer',
    ];

    // Relación: Material pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación: Material pertenece a un docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }

    // Relación: Un material tiene muchas descargas
    public function descargas()
    {
        return $this->hasMany(DescargaMaterial::class, 'material_id');
    }
}