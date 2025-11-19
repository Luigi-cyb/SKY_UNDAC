<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaNotificacion extends Model
{
    use HasFactory;

    protected $table = 'plantillas_notificaciones';

    protected $fillable = [
        'nombre',
        'asunto',
        'cuerpo',
        'tipo',
        'canal',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];
}