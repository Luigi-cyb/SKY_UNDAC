<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidacionCertificado extends Model
{
    use HasFactory;

    protected $table = 'validaciones_certificados';

    protected $fillable = [
        'certificado_id',
        'ip_validador',
        'user_agent',
        'fecha_validacion',
        'resultado',
    ];

    protected $casts = [
        'fecha_validacion' => 'datetime',
    ];

    // Relación: Validación pertenece a un certificado
    public function certificado()
    {
        return $this->belongsTo(Certificado::class, 'certificado_id');
    }
}