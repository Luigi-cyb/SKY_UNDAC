<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\PlantillaNotificacion;
use App\Models\User;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\Estudiante;
use App\Models\Docente;
use Illuminate\Support\Facades\DB;

class NotificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        abort_unless(Auth::user()->can('notificaciones.ver'), 403);

        $notificaciones = Notificacion::with('destinatario')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notificaciones.index', compact('notificaciones'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->can('notificaciones.enviar'), 403);

        $plantillas = PlantillaNotificacion::where('activa', true)->get();
        $cursos = Curso::whereIn('estado', ['convocatoria', 'en_curso'])->get();

        return view('notificaciones.create', compact('plantillas', 'cursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('notificaciones.enviar'), 403);

        $validated = $request->validate([
            'tipo_destinatario' => 'required|in:individual,curso,rol,todos',
            'destinatario_id' => 'nullable|integer',
            'curso_id' => 'nullable|exists:cursos,id',
            'rol_nombre' => 'nullable|string',
            'tipo' => 'required|in:email,sistema,ambos',
            'asunto' => 'required|string|max:200',
            'mensaje' => 'required|string',
            'prioridad' => 'required|in:baja,normal,alta',
        ]);

        // Obtener destinatarios
        $destinatarios = $this->obtenerDestinatarios(
            $validated['tipo_destinatario'],
            $validated['destinatario_id'] ?? null,
            $validated['curso_id'] ?? null,
            $validated['rol_nombre'] ?? null
        );

        // Crear notificaciones
        foreach ($destinatarios as $destinatario) {
            $notificacion = Notificacion::create([
                'user_id' => $destinatario->id,
                'tipo' => $validated['tipo'],
                'asunto' => $validated['asunto'],
                'mensaje' => $validated['mensaje'],
                'prioridad' => $validated['prioridad'],
                'leida' => false,
            ]);

            // Enviar email si corresponde
            if (in_array($validated['tipo'], ['email', 'ambos'])) {
                $this->enviarEmail($notificacion, $destinatario);
            }
        }

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificaciones enviadas exitosamente a ' . count($destinatarios) . ' destinatario(s).');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notificacion $notificacion)
    {
        abort_unless(Auth::user()->can('notificaciones.ver'), 403);

        $notificacion->load('destinatario');

        return view('notificaciones.show', compact('notificacion'));
    }

    /**
     * Marcar como leída
     */
    public function marcarLeida(Notificacion $notificacion)
    {
        $user = Auth::user();

        // Solo el destinatario puede marcar como leída
        if ($notificacion->user_id !== $user->id && !$user->can('notificaciones.ver')) {
            abort(403);
        }

        $notificacion->update([
            'leida' => true,
            'fecha_lectura' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Notificación marcada como leída.');
    }

    /**
     * Marcar todas como leídas
     */
    public function marcarTodasLeidas()
    {
        $user = Auth::user();

        Notificacion::where('user_id', $user->id)
            ->where('leida', false)
            ->update([
                'leida' => true,
                'fecha_lectura' => now(),
            ]);

        return redirect()->back()
            ->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }

    /**
     * Notificaciones del usuario autenticado
     */
    public function misNotificaciones()
    {
        $user = Auth::user();

        $notificaciones = Notificacion::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $noLeidas = Notificacion::where('user_id', $user->id)
            ->where('leida', false)
            ->count();

        return view('notificaciones.mis-notificaciones', compact('notificaciones', 'noLeidas'));
    }

    /**
     * Obtener destinatarios según tipo
     */
    private function obtenerDestinatarios($tipo, $destinatarioId, $cursoId, $rolNombre)
    {
        switch ($tipo) {
            case 'individual':
                return User::where('id', $destinatarioId)->get();
            
            case 'curso':
                $curso = Curso::findOrFail($cursoId);
                $estudiantesIds = $curso->inscripciones()
                    ->where('estado', 'confirmada')
                    ->pluck('estudiante_id');
                return User::whereHas('estudiante', function($query) use ($estudiantesIds) {
                    $query->whereIn('id', $estudiantesIds);
                })->get();
            
            case 'rol':
                return User::role($rolNombre)->get();
            
            case 'todos':
                return User::all();
            
            default:
                return collect();
        }
    }

    /**
     * Enviar email
     */
    private function enviarEmail($notificacion, $destinatario)
    {
        try {
            Mail::send('emails.notificacion', ['notificacion' => $notificacion], function($message) use ($destinatario, $notificacion) {
                $message->to($destinatario->email)
                    ->subject($notificacion->asunto);
            });

            $notificacion->update(['enviada' => true]);
        } catch (\Exception $e) {
            \Log::error('Error enviando email: ' . $e->getMessage());
        }
    }

    /**
     * Crear notificación automática
     */
    public static function crearNotificacionAutomatica($userId, $tipo, $asunto, $mensaje, $prioridad = 'normal')
    {
        return Notificacion::create([
            'user_id' => $userId,
            'tipo' => 'sistema',
            'asunto' => $asunto,
            'mensaje' => $mensaje,
            'prioridad' => $prioridad,
            'leida' => false,
        ]);
    }

    /**
     * Enviar notificación de inscripción confirmada
     */
    public static function notificarInscripcionConfirmada($inscripcion)
    {
        $user = $inscripcion->estudiante->user;
        
        self::crearNotificacionAutomatica(
            $user->id,
            'sistema',
            'Inscripción confirmada',
            "Tu inscripción al curso {$inscripcion->curso->nombre} ha sido confirmada exitosamente.",
            'alta'
        );
    }

    /**
     * Enviar notificación de certificado disponible
     */
    public static function notificarCertificadoDisponible($certificado)
    {
        $user = $certificado->inscripcion->estudiante->user;
        
        self::crearNotificacionAutomatica(
            $user->id,
            'ambos',
            'Certificado disponible',
            "Tu certificado del curso {$certificado->inscripcion->curso->nombre} está disponible para descarga.",
            'alta'
        );
    }

    /**
     * Enviar recordatorio de pago pendiente
     */
    public static function notificarPagoPendiente($inscripcion)
    {
        $user = $inscripcion->estudiante->user;
        
        self::crearNotificacionAutomatica(
            $user->id,
            'ambos',
            'Pago pendiente',
            "Recuerda completar el pago de tu inscripción al curso {$inscripcion->curso->nombre}.",
            'alta'
        );
    }
    /**
     * Obtener notificaciones no leídas (API para header)
     */
    public function noLeidas()
    {
        $user = Auth::user();
        
        $notificaciones = Notificacion::where('user_id', $user->id)
            ->where('leida', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'count' => $notificaciones->count(),
            'notificaciones' => $notificaciones
        ]);
    }

    /**
     * Personalizar contenido con variables
     */
    private function personalizarContenido($contenido, $destinatario)
    {
        $reemplazos = [
            '{nombre}' => $destinatario->name ?? '',
            '{email}' => $destinatario->email ?? '',
            '{fecha}' => now()->format('d/m/Y'),
        ];

        return str_replace(array_keys($reemplazos), array_values($reemplazos), $contenido);
    }

    /**
     * Enviar notificación de inicio de curso
     */
    public static function notificarInicioCurso($curso)
    {
        $inscripciones = $curso->inscripciones()
            ->where('estado', 'confirmada')
            ->with('estudiante.user')
            ->get();

        foreach ($inscripciones as $inscripcion) {
            self::crearNotificacionAutomatica(
                $inscripcion->estudiante->user->id,
                'ambos',
                'Inicio de curso próximo',
                "El curso {$curso->nombre} iniciará el " . \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y'),
                'alta'
            );
        }
    }

    /**
     * Enviar recordatorio de asistencia baja
     */
    public static function notificarAsistenciaBaja($inscripcion, $porcentaje)
    {
        $user = $inscripcion->estudiante->user;
        
        self::crearNotificacionAutomatica(
            $user->id,
            'sistema',
            '⚠️ Alerta de asistencia',
            "Tu asistencia en el curso {$inscripcion->curso->nombre} es del {$porcentaje}%. Se requiere mínimo 75%.",
            'urgente'
        );
    }

    /**
     * Eliminar notificaciones antiguas (mantenimiento)
     */
    public function limpiarAntiguas()
    {
        abort_unless(Auth::user()->hasRole('Administrador'), 403);

        // Eliminar notificaciones leídas con más de 90 días
        $eliminadas = Notificacion::where('leida', true)
            ->where('fecha_lectura', '<', now()->subDays(90))
            ->delete();

        return redirect()->back()
            ->with('success', "Se eliminaron {$eliminadas} notificaciones antiguas.");
    }
}