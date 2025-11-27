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
        'firmado',                    // ✅ NUEVO
        'pdf_firmado_url',           // ✅ NUEVO
        'fecha_firmado',             // ✅ NUEVO
        'firmado_por_user_id',       // ✅ NUEVO
        'firma_digital',
        'firmado_por',
        'estado',
        'observaciones',
        'numero_veces_descargado',
        'ultima_descarga',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'firmado' => 'boolean',       // ✅ NUEVO
        'fecha_firmado' => 'datetime', // ✅ NUEVO
        'numero_veces_descargado' => 'integer',
        'ultima_descarga' => 'datetime',
    ];

    // ✅ NUEVO: Relación con el usuario que firmó
    public function firmadoPorUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'firmado_por_user_id');
    }

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }

    public function validaciones()
    {
        return $this->hasMany(ValidacionCertificado::class, 'certificado_id');
    }

    // ✅ NUEVO: Helper para verificar si está firmado
    public function estaFirmado()
    {
        return $this->firmado && !empty($this->pdf_firmado_url);
    }
}