<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción Confirmada</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #212529;
            text-align: right;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #5568d3;
        }
        .alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✅ Inscripción Confirmada</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">¡Hola {{ $estudiante->nombre }} {{ $estudiante->apellido }}!</p>
            
            <p>Nos complace confirmar tu inscripción en el siguiente curso extracurricular:</p>

            <!-- Información del Curso -->
            <div class="info-box">
                <h3 style="margin-top: 0; color: #667eea;">📚 Información del Curso</h3>
                <div class="info-row">
                    <span class="info-label">Curso:</span>
                    <span class="info-value">{{ $curso->nombre }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Código:</span>
                    <span class="info-value">{{ $curso->codigo }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Modalidad:</span>
                    <span class="info-value">{{ $curso->modalidad->nombre }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Duración:</span>
                    <span class="info-value">{{ $curso->duracion_horas }} horas académicas</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de Inicio:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de Fin:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</span>
                </div>
            </div>

            <!-- Información de la Inscripción -->
            <div class="info-box">
                <h3 style="margin-top: 0; color: #667eea;">📋 Datos de tu Inscripción</h3>
                <div class="info-row">
                    <span class="info-label">Número de Inscripción:</span>
                    <span class="info-value">#{{ str_pad($inscripcion->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de Inscripción:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($inscripcion->created_at)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado:</span>
                    <span class="info-value" style="color: #28a745; font-weight: bold;">{{ ucfirst($inscripcion->estado) }}</span>
                </div>
            </div>

            @if(isset($pago))
            <!-- Información del Pago -->
            <div class="info-box">
                <h3 style="margin-top: 0; color: #667eea;">💳 Información de Pago</h3>
                <div class="info-row">
                    <span class="info-label">Monto:</span>
                    <span class="info-value">S/ {{ number_format($pago->monto, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Método de Pago:</span>
                    <span class="info-value">{{ $pago->metodoPago->nombre }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado:</span>
                    <span class="info-value" style="color: #28a745; font-weight: bold;">{{ ucfirst($pago->estado) }}</span>
                </div>
            </div>
            @endif

            <div class="divider"></div>

            <!-- Próximos Pasos -->
            <h3 style="color: #667eea;">🎯 Próximos Pasos</h3>
            <ol style="padding-left: 20px; color: #495057;">
                <li style="margin-bottom: 10px;">Accede a tu portal de estudiante para ver los detalles completos del curso</li>
                <li style="margin-bottom: 10px;">Revisa el cronograma de clases y los materiales disponibles</li>
                <li style="margin-bottom: 10px;">Prepárate para el inicio del curso el {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</li>
                <li style="margin-bottom: 10px;">Mantente atento a las notificaciones sobre el curso</li>
            </ol>

            <!-- Botón de Acción -->
            <div style="text-align: center;">
                <a href="{{ route('dashboard') }}" class="button">
                    Ir a Mi Portal
                </a>
            </div>

            <!-- Alerta Importante -->
            <div class="alert">
                <strong>⚠️ Importante:</strong> Guarda este correo para tu referencia. Puedes descargar tu comprobante de inscripción desde tu portal de estudiante.
            </div>

            <div class="divider"></div>

            <p style="color: #6c757d; font-size: 14px;">
                Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos a través de nuestros canales oficiales.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Universidad Nacional Daniel Alcides Carrión</strong></p>
            <p>Escuela de Ingeniería de Sistemas y Computación</p>
            <p>
                📧 Email: <a href="mailto:eisc@undac.edu.pe">eisc@undac.edu.pe</a><br>
                🌐 Web: <a href="https://www.undac.edu.pe">www.undac.edu.pe</a><br>
                📞 Teléfono: (063) 421-256
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #adb5bd;">
                © {{ date('Y') }} UNDAC - EISC. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>