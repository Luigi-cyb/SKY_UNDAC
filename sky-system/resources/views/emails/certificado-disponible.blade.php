<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado Disponible</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            color: #f5576c;
            margin-bottom: 20px;
        }
        .certificate-preview {
            background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);
            border: 3px dashed #f5576c;
            padding: 30px;
            margin: 25px 0;
            border-radius: 12px;
            text-align: center;
        }
        .certificate-icon {
            font-size: 80px;
            margin-bottom: 15px;
        }
        .certificate-preview h2 {
            margin: 10px 0;
            color: #f5576c;
            font-size: 24px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #f5576c;
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
            padding: 14px 35px;
            background-color: #f5576c;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(245, 87, 108, 0.3);
        }
        .button:hover {
            background-color: #e04556;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 25px 0;
        }
        .stat-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #f5576c;
            margin: 5px 0;
        }
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
        }
        .achievement-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin: 10px 5px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        .footer a {
            color: #f5576c;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 25px 0;
        }
        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 ¡Certificado Disponible!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">¡Felicitaciones {{ $estudiante->nombre }} {{ $estudiante->apellido }}!</p>
            
            <p style="font-size: 16px;">
                Nos complace informarte que has completado exitosamente el curso 
                <strong>{{ $curso->nombre }}</strong> y tu certificado digital ya está disponible para descarga.
            </p>

            <!-- Certificate Preview -->
            <div class="certificate-preview">
                <div class="certificate-icon">🏆</div>
                <h2>Certificado Digital</h2>
                <p style="color: #6c757d; margin: 10px 0;">
                    Has demostrado dedicación y compromiso en tu aprendizaje
                </p>
                <div class="achievement-badge">✨ Aprobado con Éxito</div>
            </div>

            <!-- Estadísticas del Curso -->
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value">{{ $certificado->nota_final ?? 'N/A' }}</div>
                    <div class="stat-label">Nota Final</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $certificado->asistencia ?? 'N/A' }}%</div>
                    <div class="stat-label">Asistencia</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $curso->duracion_horas }}h</div>
                    <div class="stat-label">Duración</div>
                </div>
            </div>

            <!-- Información del Certificado -->
            <div class="info-box">
                <h3 style="margin-top: 0; color: #f5576c;">📄 Detalles del Certificado</h3>
                <div class="info-row">
                    <span class="info-label">Código:</span>
                    <span class="info-value"><strong>{{ $certificado->codigo }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Curso:</span>
                    <span class="info-value">{{ $curso->nombre }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de Emisión:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($certificado->created_at)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Periodo:</span>
                    <span class="info-value">
                        {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Formato:</span>
                    <span class="info-value">PDF con firma digital y código QR</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Mensaje de Éxito -->
            <div class="alert-success">
                <strong>✅ ¡Todo listo!</strong> Tu certificado cuenta con firma digital y código QR para validación oficial.
            </div>

            <!-- Botones de Acción -->
            <div style="text-align: center;">
                <a href="{{ route('certificados.descargar', $certificado->id) }}" class="button">
                    📥 Descargar Certificado
                </a>
            </div>

            <p style="text-align: center; color: #6c757d; font-size: 14px; margin-top: 10px;">
                También puedes acceder a tu certificado desde tu <a href="{{ route('dashboard') }}" style="color: #f5576c;">portal de estudiante</a>
            </p>

            <div class="divider"></div>

            <!-- Información de Validación -->
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h4 style="margin-top: 0; color: #856404;">🔐 Validación del Certificado</h4>
                <p style="margin: 0; color: #856404; font-size: 14px;">
                    Tu certificado incluye un código QR único que permite la validación pública de su autenticidad. 
                    Cualquier persona o institución puede verificar tu certificado escaneando el código QR o ingresando 
                    el código <strong>{{ $certificado->codigo }}</strong> en nuestro portal de validación.
                </p>
            </div>

            <div class="divider"></div>

            <!-- Próximos Pasos -->
            <h3 style="color: #f5576c;">🚀 ¿Qué Sigue?</h3>
            <ul style="color: #495057; line-height: 1.8;">
                <li>Descarga tu certificado y guárdalo en un lugar seguro</li>
                <li>Comparte tu logro en redes sociales con el hashtag <strong>#UNDACEISC</strong></li>
                <li>Explora otros cursos disponibles en nuestro catálogo</li>
                <li>Aplica tus nuevos conocimientos en proyectos reales</li>
            </ul>

            <!-- Encuesta de Satisfacción -->
            <div style="background-color: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #004085;">💬 Tu Opinión Importa</h4>
                <p style="margin: 0 0 10px 0; color: #004085; font-size: 14px;">
                    Ayúdanos a mejorar completando una breve encuesta sobre tu experiencia en el curso.
                </p>
                <a href="{{ route('encuestas.responder', ['curso_id' => $curso->id]) }}" 
                   style="display: inline-block; padding: 8px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">
                    Responder Encuesta
                </a>
            </div>

            <p style="margin-top: 25px; color: #6c757d; font-size: 14px; text-align: center;">
                Estamos orgullosos de tu dedicación y esfuerzo. ¡Sigue adelante! 🌟
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