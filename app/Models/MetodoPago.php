<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $table = 'metodos_pago';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'requiere_comprobante',
        'activo',
    ];

    protected $casts = [
        'requiere_comprobante' => 'boolean',
        'activo' => 'boolean',
    ];

    // Relación: Un método de pago tiene muchos pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'metodo_pago_id');
    }
}