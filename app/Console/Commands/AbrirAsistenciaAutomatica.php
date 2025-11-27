<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SesionCurso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AbrirAsistenciaAutomatica extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asistencia:abrir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Abre automáticamente la asistencia para sesiones que inician en este momento';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ahora = Carbon::now();
        $this->info("🔍 Buscando sesiones para abrir asistencia... (" . $ahora->format('Y-m-d H:i:s') . ")");

        // Buscar sesiones que deben iniciar ahora
        $sesiones = SesionCurso::where('fecha_sesion', $ahora->toDateString())
            ->where('estado', 'programada')
            ->where('permite_asistencia', false)
            ->get();

        if ($sesiones->isEmpty()) {
            $this->info("✓ No hay sesiones programadas para iniciar en este momento.");
            return 0;
        }

        $sesionesAbiertas = 0;

        foreach ($sesiones as $sesion) {
            // Parsear hora de inicio de la sesión
            $horaInicio = Carbon::parse($sesion->fecha_sesion . ' ' . $sesion->hora_inicio);
            
            // Verificar si la hora actual está dentro del rango de inicio (±5 minutos de tolerancia)
            if ($ahora->between($horaInicio->copy()->subMinutes(5), $horaInicio->copy()->addMinutes(10))) {
                
                // Actualizar sesión: activar asistencia
                $sesion->update([
                    'permite_asistencia' => true,
                    'fecha_inicio_asistencia' => $ahora,
                    'fecha_fin_asistencia' => $ahora->copy()->addMinutes(15), // 15 minutos para marcar
                ]);

                $sesionesAbiertas++;
                
                $this->info("✅ Asistencia abierta: Sesión #{$sesion->numero_sesion} - {$sesion->titulo}");
                $this->info("   📅 Curso: {$sesion->curso->nombre}");
                $this->info("   ⏰ Ventana de asistencia: 15 minutos");
                
                Log::info("Asistencia abierta automáticamente", [
                    'sesion_id' => $sesion->id,
                    'curso_id' => $sesion->curso_id,
                    'fecha_apertura' => $ahora->toDateTimeString(),
                    'fecha_cierre' => $sesion->fecha_fin_asistencia
                ]);
            }
        }

        if ($sesionesAbiertas > 0) {
            $this->info("🎉 Total de sesiones procesadas: {$sesionesAbiertas}");
        } else {
            $this->info("⏳ Hay sesiones programadas pero aún no es hora de abrirlas.");
        }

        return 0;
    }
}
