<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;

    protected $table = 'certificados';

    protected $fillable = [
        'inscripcion_id',
        'codigo_certificado',
        'codigo_qr',
        'fecha_emision',
        'pdf_url',
        'firma_digital',
        'firmado_por',
        'estado',
        'observaciones',
        'numero_veces_descargado',
        'ultima_descarga',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'numero_veces_descargado' => 'integer',
        'ultima_descarga' => 'datetime',
    ];

    // Relación: Certificado pertenece a una inscripción
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }

    // Relación: Un certificado tiene muchas validaciones
    public function validaciones()
    {
        return $this->hasMany(ValidacionCertificado::class, 'certificado_id');
    }
}