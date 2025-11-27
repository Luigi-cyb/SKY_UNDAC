<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntentoEvaluacion extends Model
{
    use HasFactory;

    protected $table = 'intentos_evaluacion';

    protected $fillable = [
        'inscripcion_id',
        'evaluacion_id',
        'numero_intento',
        'fecha_inicio',
        'fecha_fin',
        'tiempo_total_segundos',
        'nota_obtenida',
        'puntos_totales',
        'puntos_obtenidos',
        'estado',
        'ip_address'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'nota_obtenida' => 'decimal:2',
        'puntos_totales' => 'decimal:2',
        'puntos_obtenidos' => 'decimal:2'
    ];

    // Relaciones
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class);
    }

    // ✅ NUEVO: Relación con respuestas
    public function respuestas()
    {
        return $this->hasMany(RespuestaEvaluacion::class, 'intento_id');
    }

    // Métodos auxiliares existentes
    public function estaEnProgreso()
    {
        return $this->estado === 'en_progreso';
    }

    public function estaFinalizado()
    {
        return $this->estado === 'finalizado';
    }

    public function calcularTiempoTotal()
    {
        if ($this->fecha_inicio && $this->fecha_fin) {
            return $this->fecha_inicio->diffInSeconds($this->fecha_fin);
        }
        return null;
    }

    public function getTiempoFormateado()
    {
        if (!$this->tiempo_total_segundos) return 'N/A';
        
        $horas = floor($this->tiempo_total_segundos / 3600);
        $minutos = floor(($this->tiempo_total_segundos % 3600) / 60);
        $segundos = $this->tiempo_total_segundos % 60;
        
        if ($horas > 0) {
            return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
        }
        return sprintf('%02d:%02d', $minutos, $segundos);
    }

    // ✅ NUEVOS MÉTODOS PARA GESTIÓN DE TIEMPO
    public function getTiempoTranscurridoMinutos()
    {
        if ($this->fecha_inicio) {
            $inicio = \Carbon\Carbon::parse($this->fecha_inicio);
            $ahora = $this->fecha_fin ? \Carbon\Carbon::parse($this->fecha_fin) : now();
            return $inicio->diffInMinutes($ahora);
        }
        return 0;
    }

    public function getTiempoRestanteMinutos()
    {
        if ($this->evaluacion && $this->fecha_inicio && $this->estaEnProgreso()) {
            $duracionTotal = $this->evaluacion->duracion_minutos;
            $transcurrido = $this->getTiempoTranscurridoMinutos();
            return max(0, $duracionTotal - $transcurrido);
        }
        return 0;
    }

    public function getTiempoRestanteSegundos()
    {
        return $this->getTiempoRestanteMinutos() * 60;
    }

    public function haExpirado()
    {
        return $this->estaEnProgreso() && $this->getTiempoRestanteMinutos() <= 0;
    }

    public function getPorcentajeTiempoUsado()
    {
        if ($this->evaluacion) {
            $duracionTotal = $this->evaluacion->duracion_minutos;
            $transcurrido = $this->getTiempoTranscurridoMinutos();
            return min(100, ($transcurrido / $duracionTotal) * 100);
        }
        return 0;
    }
}