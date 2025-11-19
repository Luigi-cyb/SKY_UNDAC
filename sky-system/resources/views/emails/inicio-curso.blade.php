<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Curso</title>
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
            box-shadow: 0 2 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
            color: #11998e;
            margin-bottom: 20px;
        }
        .highlight-box {
            background: linear-gradient(135deg, #11998e15 0%, #38ef7d15 100%);
            border: 2px solid #11998e;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
            text-align: center;
        }
        .highlight-box h2 {
            margin: 0 0 10px 0;
            color: #11998e;
            font-size: 24px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #11998e;
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
            background-color: #11998e;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #0e8077;
        }
        .checklist {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .checklist-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .checklist-item:last-child {
            margin-bottom: 0;
        }
        .checklist-icon {
            font-size: 20px;
            margin-right: 10px;
            color: #11998e;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        .footer a {
            color: #11998e;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 25px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🚀 ¡Tu Curso Comienza Pronto!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">¡Hola {{ $estudiante->nombre }}!</p>
            
            <p>Te recordamos que el curso <strong>{{ $curso->nombre }}</strong> está próximo a iniciar. Prepárate para una experiencia de aprendizaje increíble.</p>

            <!-- Fecha Destacada -->
            <div class="highlight-box">
                <h2>📅 Inicio de Clases</h2>
                <p style="font-size: 32px; font-weight: bold; color: #11998e; margin: 10px 0;">
                    {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d') }}
                </p>
                <p style="font-size: 18px; margin: 0;">
                    {{ \Carbon\Carbon::parse($curso->fecha_inicio)->locale('es')->isoFormat('MMMM [de] YYYY') }}
                </p>
                <p style="font-size: 14px; color: #6c757d; margin-top: 10px;">
                    {{ \Carbon\Carbon::parse($curso->fecha_inicio)->locale('es')->isoFormat('dddd') }} a las {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('H:i') }}
                </p>
            </div>

            <!-- Información del Curso -->
            <div class="info-box">
                <h3 style="margin-top: 0; color: #11998e;">📚 Detalles del Curso</h3>
                <div class="info-row">
                    <span class="info-label">Curso:</span>
                    <span class="info-value">{{ $curso->nombre }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Código:</span>
                    <span class="info-value">{{ $curso->codigo }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Docente:</span>
                    <span class="info-value">{{ $docente->nombre ?? 'Por asignar' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Modalidad:</span>
                    <span class="info-value">
                        <span class="badge badge-success">{{ $curso->modalidad->nombre }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Duración:</span>
                    <span class="info-value">{{ $curso->duracion_horas }} horas</span>
                </div>
                @if($curso->horario)
                <div class="info-row">
                    <span class="info-label">Horario:</span>
                    <span class="info-value">{{ $curso->horario }}</span>
                </div>
                @endif
                @if($curso->aula)
                <div class="info-row">
                    <span class="info-label">Aula:</span>
                    <span class="info-value">{{ $curso->aula }}</span>
                </div>
                @endif
            </div>

            <div class="divider"></div>

            <!-- Checklist de Preparación -->
            <div class="checklist">
                <h3 style="color: #11998e; margin-top: 0;">✅ Antes del Inicio</h3>
                
                <div class="checklist-item">
                    <span class="checklist-icon">✓</span>
                    <div>
                        <strong>Accede a tu portal</strong><br>
                        <span style="color: #6c757d; font-size: 14px;">Revisa los materiales y recursos disponibles</span>
                    </div>
                </div>

                <div class="checklist-item">
                    <span class="checklist-icon">✓</span>
                    <div>
                        <strong>Descarga el sílabo</strong><br>
                        <span style="color: #6c757d; font-size: 14px;">Conoce los temas y objetivos del curso</span>
                    </div>
                </div>

                <div class="checklist-item">
                    <span class="checklist-icon">✓</span>
                    <div>
                        <strong>Prepara tus materiales</strong><br>
                        <span style="color: #6c757d; font-size: 14px;">Libreta, laptop y disposición para aprender</span>
                    </div>
                </div>

                @if($curso->modalidad->nombre == 'Virtual')
                <div class="checklist-item">
                    <span class="checklist-icon">✓</span>
                    <div>
                        <strong>Verifica tu conexión</strong><br>
                        <span style="color: #6c757d; font-size: 14px;">Asegúrate de tener buena conexión a internet</span>
                    </div>
                </div>
                @endif

                <div class="checklist-item">
                    <span class="checklist-icon">✓</span>
                    <div>
                        <strong>Revisa los requisitos</strong><br>
                        <span style="color: #6c757d; font-size: 14px;">Cumple con la asistencia mínima del {{ $curso->asistencia_minima ?? 75 }}%</span>
                    </div>
                </div>
            </div>

            <!-- Botón de Acción -->
            <div style="text-align: center;">
                <a href="{{ route('cursos.show', $curso->id) }}" class="button">
                    Ver Detalles del Curso
                </a>
            </div>

            <div class="divider"></div>

            <!-- Información de Contacto -->
            <div style="background-color: #e7f3f1; padding: 15px; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #11998e;">📞 ¿Necesitas Ayuda?</h4>
                <p style="margin: 0; color: #495057; font-size: 14px;">
                    Si tienes alguna pregunta o necesitas asistencia, contáctanos:
                </p>
                <p style="margin: 5px 0 0 0; color: #495057; font-size: 14px;">
                    📧 <a href="mailto:eisc@undac.edu.pe" style="color: #11998e;">eisc@undac.edu.pe</a><br>
                    📱 WhatsApp: +51 963 852 741
                </p>
            </div>

            <p style="margin-top: 20px; color: #6c757d; font-size: 14px; text-align: center;">
                ¡Nos vemos pronto! Estamos emocionados de tenerte en este curso. 🎓
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