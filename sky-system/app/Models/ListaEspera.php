<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaEspera extends Model
{
    use HasFactory;

    protected $table = 'lista_espera';

    protected $fillable = [
        'estudiante_id',
        'curso_id',
        'posicion',
        'fecha_registro',
        'estado',
        'fecha_asignacion',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'fecha_asignacion' => 'datetime',
    ];

    // Relación: Lista de espera pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Lista de espera pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}