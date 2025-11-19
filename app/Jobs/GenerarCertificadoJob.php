<?php

namespace App\Jobs;

use App\Models\Certificado;
use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerarCertificadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $inscripcion;
    
    // Configuración del Job
    public $tries = 3;              // Intentos máximos si falla
    public $timeout = 120;          // 2 minutos máximo de ejecución
    public $backoff = [60, 180];    // Espera 1min y 3min entre reintentos

    /**
     * Create a new job instance.
     */
    public function __construct(Inscripcion $inscripcion)
    {
        $this->inscripcion = $inscripcion;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("🎓 Iniciando generación de certificado para inscripción #{$this->inscripcion->id}");

            // 1. Validar que cumple requisitos
            if (!$this->cumpleRequisitos()) {
                Log::warning("❌ Inscripción #{$this->inscripcion->id} no cumple requisitos");
                return;
            }

            // 2. Generar número único de serie
            $numeroSerie = $this->generarNumeroSerie();

            // 3. Generar código QR
            $codigoQR = $this->generarCodigoQR($numeroSerie);

            // 4. Generar hash de verificación
            $hashVerificacion = $this->generarHash($numeroSerie);

            // 5. Crear registro de certificado
            $certificado = $this->crearCertificado($numeroSerie, $codigoQR, $hashVerificacion);

            // 6. Generar PDF
            $rutaPDF = $this->generarPDF($certificado);

            // 7. Actualizar certificado con ruta del PDF
            $certificado->update(['pdf_url' => $rutaPDF]);

            Log::info("✅ Certificado #{$certificado->id} generado exitosamente");

        } catch (\Exception $e) {
            Log::error("❌ Error generando certificado: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Re-lanzar la excepción para que Laravel reintente el job
            throw $e;
        }
    }

    /**
     * Validar que el estudiante cumple requisitos de certificación
     */
    private function cumpleRequisitos(): bool
    {
        $curso = $this->inscripcion->curso;

        // Verificar asistencia mínima
        $asistenciaMinima = $curso->asistencia_minima ?? 80;
        $asistenciaEstudiante = $this->inscripcion->porcentaje_asistencia ?? 0;

        if ($asistenciaEstudiante < $asistenciaMinima) {
            Log::info("Asistencia insuficiente: {$asistenciaEstudiante}% < {$asistenciaMinima}%");
            return false;
        }

        // Verificar nota mínima
        $notaMinima = $curso->nota_minima ?? 14;
        $notaEstudiante = $this->inscripcion->nota_final ?? 0;

        if ($notaEstudiante < $notaMinima) {
            Log::info("Nota insuficiente: {$notaEstudiante} < {$notaMinima}");
            return false;
        }

        return true;
    }

    /**
     * Generar número de serie único
     */
    private function generarNumeroSerie(): string
    {
        do {
            $year = now()->year;
            $random = strtoupper(Str::random(8));
            $numeroSerie = "CERT-{$year}-{$random}";
            
            // Verificar que no exista (usando codigo_certificado)
            $existe = Certificado::where('codigo_certificado', $numeroSerie)->exists();
        } while ($existe);

        return $numeroSerie;
    }
    /**
     * Generar código QR para validación
     */
    private function generarCodigoQR(string $numeroSerie): string
    {
        // URL de validación pública
        $urlValidacion = url("/certificados/validar/{$numeroSerie}");

        // Generar QR en formato PNG
        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($urlValidacion);

        // Convertir a base64 para embeber en PDF
        return base64_encode($qrCode);
    }

    /**
     * Generar hash SHA-256 para verificación
     */
    private function generarHash(string $numeroSerie): string
    {
        $data = $numeroSerie . $this->inscripcion->id . now()->timestamp;
        return hash('sha256', $data);
    }

    /**
     * Crear registro del certificado en BD
     */
    private function crearCertificado(string $numeroSerie, string $codigoQR, string $hash): Certificado
{
    return Certificado::create([
        'inscripcion_id' => $this->inscripcion->id,
        'codigo_certificado' => $numeroSerie,
        'codigo_qr' => $codigoQR,
        'fecha_emision' => now(),
        'pdf_url' => '', // Se actualizará después
        'firmado_por' => 'Director EISC',
        'estado' => 'emitido',
        'observaciones' => null,
    ]);
}

    /**
     * Generar PDF del certificado
     */
    private function generarPDF(Certificado $certificado): string
    {
        $inscripcion = $this->inscripcion->load(['estudiante.user', 'curso']);
        $estudiante = $inscripcion->estudiante;
        $curso = $inscripcion->curso;

        // Datos para la vista
        $data = [
            'certificado' => $certificado,
            'estudiante' => $estudiante,
            'curso' => $curso,
            'inscripcion' => $inscripcion,
            'fecha_emision' => $certificado->fecha_emision->format('d/m/Y'),
            'qr_code' => $certificado->codigo_qr,
        ];

        // Generar PDF con DomPDF
        $pdf = Pdf::loadView('certificados.plantilla-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('enable_php', true);

        // Guardar en storage
        $nombreArchivo = "certificado_{$certificado->codigo_certificado}.pdf";
        $rutaCompleta = "certificados/{$nombreArchivo}";

        Storage::disk('public')->put($rutaCompleta, $pdf->output());

        Log::info("📄 PDF generado: {$rutaCompleta}");

        return $rutaCompleta;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("💥 Job GenerarCertificadoJob falló permanentemente");
        Log::error("Inscripción ID: {$this->inscripcion->id}");
        Log::error("Error: " . $exception->getMessage());

        // Aquí podrías:
        // - Enviar notificación al administrador
        // - Crear un registro de error en BD
        // - Enviar email al equipo de desarrollo
    }
}