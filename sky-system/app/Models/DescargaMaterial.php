<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescargaMaterial extends Model
{
    use HasFactory;

    protected $table = 'descargas_materiales';

    protected $fillable = [
        'material_id',
        'estudiante_id',
        'fecha_descarga',
        'ip_descarga',
    ];

    protected $casts = [
        'fecha_descarga' => 'datetime',
    ];

    // Relación: Descarga pertenece a un material
    public function material()
    {
        return $this->belongsTo(MaterialCurso::class, 'material_id');
    }

    // Relación: Descarga pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }
}