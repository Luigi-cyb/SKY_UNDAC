<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'inscripcion_id',
        'metodo_pago_id',
        'codigo_operacion',      // ⚠️ Campo original de tu BD
        'codigo_pago',           // ✅ Campo nuevo agregado
        'numero_operacion',      // ✅ Campo nuevo agregado
        'monto',
        'fecha_pago',
        'estado',
        'comprobante_url',       // ⚠️ Campo original de tu BD
        'observaciones',         // ⚠️ Campo original (no 'descripcion')
        'fecha_confirmacion',
        'registrado_por',        // ✅ Campo nuevo agregado
        'modificado_por',        // ✅ Campo nuevo agregado
        'confirmado_por',        // ✅ Campo nuevo agregado
        'rechazado_por',         // ✅ Campo nuevo agregado
        'fecha_rechazo',         // ✅ Campo nuevo agregado
        'motivo_rechazo',        // ✅ Campo nuevo agregado
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'date',
        'fecha_confirmacion' => 'datetime',
        'fecha_rechazo' => 'datetime',
    ];

    protected $appends = [
        'estado_badge',
        'estado_texto',
    ];

    // ==================== RELACIONES ====================

    /**
     * Relación con Inscripción
     */
    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class);
    }

    /**
     * Relación con Método de Pago
     */
    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class);
    }

    /**
     * Relación con Comprobante
     */
    public function comprobante(): HasOne
    {
        return $this->hasOne(Comprobante::class);
    }

    /**
     * Usuario que registró el pago
     */
    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    /**
     * Usuario que modificó el pago
     */
    public function modificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modificado_por');
    }

    /**
     * Usuario que confirmó el pago
     */
    public function confirmadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmado_por');
    }

    /**
     * Usuario que rechazó el pago
     */
    public function rechazadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rechazado_por');
    }

    // ==================== ACCESSORS ====================

    /**
     * Badge de color según el estado
     */
    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'confirmado' => 'bg-green-100 text-green-800 border border-green-200',
            'pendiente' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
            'rechazado' => 'bg-red-100 text-red-800 border border-red-200',
            default => 'bg-gray-100 text-gray-800 border border-gray-200',
        };
    }

    /**
     * Texto del estado en español
     */
    public function getEstadoTextoAttribute(): string
    {
        return match($this->estado) {
            'confirmado' => 'Confirmado',
            'pendiente' => 'Pendiente',
            'rechazado' => 'Rechazado',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener el código de pago o generar uno temporal
     */
    public function getCodigoDisplayAttribute(): string
    {
        return $this->codigo_pago ?? $this->codigo_operacion ?? 'SIN-CÓDIGO';
    }

    /**
     * Formatear monto con símbolo de moneda
     */
    public function getMontoFormateadoAttribute(): string
    {
        return 'S/. ' . number_format($this->monto, 2);
    }

    // ==================== SCOPES ====================

    /**
     * Scope para pagos confirmados
     */
    public function scopeConfirmados($query)
    {
        return $query->where('estado', 'confirmado');
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para pagos rechazados
     */
    public function scopeRechazados($query)
    {
        return $query->where('estado', 'rechazado');
    }

    /**
     * Scope para filtrar por período
     */
    public function scopePorPeriodo($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope para filtrar por mes y año
     */
    public function scopeDelMes($query, $mes = null, $anio = null)
    {
        $mes = $mes ?? now()->month;
        $anio = $anio ?? now()->year;
        
        return $query->whereMonth('fecha_pago', $mes)
                     ->whereYear('fecha_pago', $anio);
    }

    /**
     * Scope para pagos del día actual
     */
    public function scopeDelDia($query)
    {
        return $query->whereDate('fecha_pago', today());
    }

    /**
     * Scope para pagos de la semana actual
     */
    public function scopeDeLaSemana($query)
    {
        return $query->whereBetween('fecha_pago', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope para buscar por estudiante
     */
    public function scopePorEstudiante($query, $estudianteId)
    {
        return $query->whereHas('inscripcion', function($q) use ($estudianteId) {
            $q->where('estudiante_id', $estudianteId);
        });
    }

    /**
     * Scope para buscar por curso
     */
    public function scopePorCurso($query, $cursoId)
    {
        return $query->whereHas('inscripcion', function($q) use ($cursoId) {
            $q->where('curso_id', $cursoId);
        });
    }

    // ==================== MÉTODOS ====================

    /**
     * Verificar si el pago puede ser editado
     */
    public function esEditable(): bool
    {
        return $this->estado !== 'confirmado';
    }

    /**
     * Verificar si el pago puede ser eliminado
     */
    public function esEliminable(): bool
    {
        return $this->estado !== 'confirmado';
    }

    /**
     * Verificar si el pago puede ser confirmado
     */
    public function puedeConfirmarse(): bool
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si el pago puede ser rechazado
     */
    public function puedeRechazarse(): bool
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Obtener el tiempo transcurrido desde el registro
     */
    public function tiempoTranscurrido(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Verificar si el pago está vencido (más de 24 horas pendiente)
     */
    public function estaVencido(): bool
    {
        if ($this->estado !== 'pendiente') {
            return false;
        }

        return $this->created_at->addHours(24)->isPast();
    }

    /**
     * Obtener información completa del estudiante
     */
    public function getEstudianteInfo(): string
    {
        if ($this->inscripcion && $this->inscripcion->estudiante) {
            $estudiante = $this->inscripcion->estudiante;
            return $estudiante->nombres . ' ' . $estudiante->apellidos . ' - ' . $estudiante->dni;
        }
        return 'Sin información';
    }

    /**
     * Obtener información completa del curso
     */
    public function getCursoInfo(): string
    {
        if ($this->inscripcion && $this->inscripcion->curso) {
            return $this->inscripcion->curso->nombre;
        }
        return 'Sin información';
    }

    // ==================== EVENTOS ====================

    /**
     * Boot method para eventos del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Evento antes de crear
        static::creating(function ($pago) {
            // Generar código de pago si no existe
            if (empty($pago->codigo_pago)) {
                $pago->codigo_pago = 'PAY-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(8));
            }
        });

        // Evento después de confirmar
        static::updating(function ($pago) {
            // Si cambia a confirmado y no tiene fecha de confirmación
            if ($pago->isDirty('estado') && $pago->estado === 'confirmado' && !$pago->fecha_confirmacion) {
                $pago->fecha_confirmacion = now();
            }

            // Si cambia a rechazado y no tiene fecha de rechazo
            if ($pago->isDirty('estado') && $pago->estado === 'rechazado' && !$pago->fecha_rechazo) {
                $pago->fecha_rechazo = now();
            }
        });
    }
}