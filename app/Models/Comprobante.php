<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comprobante extends Model
{
    use HasFactory;

    protected $fillable = [
        'pago_id',
        'tipo_comprobante',
        'serie',
        'numero',
        'numero_comprobante',     // ✅ Campo nuevo agregado
        'ruc_dni',
        'razon_social',
        'subtotal',
        'igv',
        'total',
        'monto_total',            // ✅ Campo nuevo agregado
        'fecha_emision',
        'xml_url',
        'pdf_url',
        'codigo_sunat',
        'estado_sunat',
        'observaciones',
        'emitido_por',            // ✅ Campo nuevo agregado
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
        'monto_total' => 'decimal:2',
    ];

    protected $appends = [
        'tipo_comprobante_texto',
        'estado_sunat_badge',
    ];

    // ==================== RELACIONES ====================

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }

    public function emitidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emitido_por');
    }

    // ==================== ACCESSORS ====================

    public function getTipoComprobanteTextoAttribute(): string
    {
        return match($this->tipo_comprobante) {
            'recibo' => 'Recibo',
            'factura' => 'Factura',
            'boleta' => 'Boleta',
            default => 'Comprobante',
        };
    }

    public function getEstadoSunatBadgeAttribute(): string
    {
        return match($this->estado_sunat) {
            'aceptado' => 'bg-green-100 text-green-800',
            'observado' => 'bg-yellow-100 text-yellow-800',
            'rechazado' => 'bg-red-100 text-red-800',
            'enviado' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // ==================== MUTATORS ====================

    // Sincronizar monto_total con total
    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = $value;
        $this->attributes['monto_total'] = $value;
    }

    // ==================== SCOPES ====================

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_comprobante', $tipo);
    }

    public function scopeAceptados($query)
    {
        return $query->where('estado_sunat', 'aceptado');
    }

    public function scopeDelMes($query, $mes = null, $anio = null)
    {
        $mes = $mes ?? now()->month;
        $anio = $anio ?? now()->year;
        
        return $query->whereMonth('fecha_emision', $mes)
                     ->whereYear('fecha_emision', $anio);
    }

    // ==================== MÉTODOS ====================

    public function getNumeroCompleto(): string
    {
        return $this->serie . '-' . str_pad($this->numero, 8, '0', STR_PAD_LEFT);
    }
}