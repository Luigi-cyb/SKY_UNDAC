<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaCurso extends Model
{
    use HasFactory;

    protected $table = 'categorias_cursos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación: Una categoría tiene muchos cursos
    public function cursos()
    {
        return $this->hasMany(Curso::class, 'categoria_id');
    }
}