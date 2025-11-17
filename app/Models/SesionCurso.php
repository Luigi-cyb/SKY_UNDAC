<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SesionCurso extends Model
{
    use HasFactory;

    protected $table = 'sesiones_curso';

    protected $fillable = [
        'curso_id',
        'numero_sesion',
        'titulo',
        'descripcion',
        'objetivos',
        'fecha_sesion',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'enlace_clase_vivo',
        'enlace_grabacion',
        'plataforma_vivo',
        'estado',
        'visible',
        'permite_asistencia',
        'fecha_inicio_asistencia',
        'fecha_fin_asistencia',
    ];

    protected $casts = [
        'fecha_sesion' => 'date',
        'visible' => 'boolean',
        'permite_asistencia' => 'boolean',
        'fecha_inicio_asistencia' => 'datetime',
        'fecha_fin_asistencia' => 'datetime',
    ];

    // Relaciones
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

   public function asistencias()
{
    return $this->hasMany(Asistencia::class)
                ->where('asistencias.curso_id', '=', $this->curso_id)
                ->where('asistencias.numero_sesion', '=', $this->numero_sesion);
}

    // Métodos auxiliares
    public function estaEnVivo()
    {
        $now = now();
        
        $fechaSesion = Carbon::parse($this->fecha_sesion);
        $horaInicio = Carbon::parse($this->hora_inicio);
        $horaFin = Carbon::parse($this->hora_fin);
        
        $inicioSesion = $fechaSesion->copy()->setTimeFrom($horaInicio);
        $finSesion = $fechaSesion->copy()->setTimeFrom($horaFin);

        return $now->between($inicioSesion, $finSesion) && $this->estado === 'en_vivo';
    }

    public function yaFinalizo()
    {
        $now = now();
        
        $fechaSesion = Carbon::parse($this->fecha_sesion);
        $horaFin = Carbon::parse($this->hora_fin);
        
        $finSesion = $fechaSesion->copy()->setTimeFrom($horaFin);

        return $now->greaterThan($finSesion) || $this->estado === 'finalizada';
    }

    public function puedeMarcarAsistencia()
    {
        if (!$this->permite_asistencia) {
            return false;
        }

        $now = now();
        
        if ($this->fecha_inicio_asistencia && $this->fecha_fin_asistencia) {
            return $now->between(
                Carbon::parse($this->fecha_inicio_asistencia),
                Carbon::parse($this->fecha_fin_asistencia)
            );
        }

        $fechaSesion = Carbon::parse($this->fecha_sesion);
        $horaInicio = Carbon::parse($this->hora_inicio);
        $horaFin = Carbon::parse($this->hora_fin);
        
        $inicioPermitido = $fechaSesion->copy()->setTimeFrom($horaInicio)->subMinutes(30);
        $finPermitido = $fechaSesion->copy()->setTimeFrom($horaFin);

        return $now->between($inicioPermitido, $finPermitido);
    }

    // Métodos públicos
    public function getHoraInicioFormateada()
    {
        return Carbon::parse($this->hora_inicio)->format('H:i');
    }

    public function getHoraFinFormateada()
    {
        return Carbon::parse($this->hora_fin)->format('H:i');
    }

    public function getEstadoColor()
    {
        return match($this->estado) {
            'programada' => 'gray',
            'en_vivo' => 'green',
            'finalizada' => 'blue',
            'cancelada' => 'red',
            default => 'gray'
        };
    }

    public function getEstadoTexto()
    {
        return match($this->estado) {
            'programada' => 'Programada',
            'en_vivo' => 'En Vivo',
            'finalizada' => 'Finalizada',
            'cancelada' => 'Cancelada',
            default => 'Desconocido'
        };
    }
}