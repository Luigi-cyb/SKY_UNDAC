<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago - {{ $pago->codigo_pago }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }

        .comprobante-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 2px solid #2c3e50;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
        }

        .header {
            border-bottom: 3px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
        }

        .university-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .comprobante-title {
            font-size: 28px;
            font-weight: bold;
            color: #3498db;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .comprobante-numero {
            font-size: 16px;
            color: #555;
            font-weight: bold;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            background: #ecf0f1;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
        }

        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            padding: 8px;
            font-weight: bold;
            color: #555;
            width: 35%;
            border-bottom: 1px solid #ecf0f1;
        }

        .info-value {
            display: table-cell;
            padding: 8px;
            color: #2c3e50;
            border-bottom: 1px solid #ecf0f1;
        }

        .monto-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
            text-align: center;
        }

        .monto-label {
            color: white;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .monto-valor {
            color: white;
            font-size: 36px;
            font-weight: bold;
        }

        .estado-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }

        .estado-confirmado {
            background: #2ecc71;
            color: white;
        }

        .estado-pendiente {
            background: #f39c12;
            color: white;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
        }

        .footer-notes {
            font-size: 11px;
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .footer-signatures {
            display: table;
            width: 100%;
            margin-top: 50px;
        }

        .signature-block {
            display: table-cell;
            text-align: center;
            width: 50%;
        }

        .signature-line {
            border-top: 2px solid #2c3e50;
            margin: 0 auto 10px;
            width: 200px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
        }

        .signature-title {
            font-size: 10px;
            color: #7f8c8d;
            font-style: italic;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(52, 152, 219, 0.05);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }

        .decorative-corner {
            position: absolute;
            width: 50px;
            height: 50px;
        }

        .corner-top-left {
            top: 0;
            left: 0;
            border-top: 5px solid #3498db;
            border-left: 5px solid #3498db;
        }

        .corner-top-right {
            top: 0;
            right: 0;
            border-top: 5px solid #3498db;
            border-right: 5px solid #3498db;
        }

        .corner-bottom-left {
            bottom: 0;
            left: 0;
            border-bottom: 5px solid #3498db;
            border-left: 5px solid #3498db;
        }

        .corner-bottom-right {
            bottom: 0;
            right: 0;
            border-bottom: 5px solid #3498db;
            border-right: 5px solid #3498db;
        }

        .alert-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 12px;
            margin: 20px 0;
            font-size: 12px;
            color: #856404;
        }

        .verificacion-code {
            background: #f8f9fa;
            border: 2px dashed #bdc3c7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .code-display {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            padding: 10px;
            background: white;
            border: 1px solid #bdc3c7;
            display: inline-block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="comprobante-container">
        <!-- Marca de agua -->
        <div class="watermark">PAGADO</div>

        <!-- Decoraciones en esquinas -->
        <div class="decorative-corner corner-top-left"></div>
        <div class="decorative-corner corner-top-right"></div>
        <div class="decorative-corner corner-bottom-left"></div>
        <div class="decorative-corner corner-bottom-right"></div>

        <!-- Encabezado -->
        <div class="header">
            <div class="logo">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="48" fill="#3498db" stroke="#2c3e50" stroke-width="2"/>
                    <text x="50" y="60" font-size="35" fill="white" text-anchor="middle" font-weight="bold">UNDAC</text>
                </svg>
            </div>
            <div class="university-name">
                Universidad Nacional Daniel Alcides Carrión
            </div>
            <div class="school-name">
                Escuela de Ingeniería de Sistemas y Computación
            </div>
            <div class="comprobante-title">
                Comprobante de Pago
            </div>
            <div class="comprobante-numero">
                {{ $pago->codigo_pago }}
            </div>
        </div>

        <!-- Información del Estudiante -->
        <div class="info-section">
            <div class="section-title">
                📋 DATOS DEL ESTUDIANTE
            </div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nombre Completo:</div>
                    <div class="info-value">
                        {{ strtoupper($pago->inscripcion->estudiante->nombres . ' ' . $pago->inscripcion->estudiante->apellidos) }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">DNI:</div>
                    <div class="info-value">{{ $pago->inscripcion->estudiante->dni }}</div>
                </div>
                @if($pago->inscripcion->estudiante->codigo)
                <div class="info-row">
                    <div class="info-label">Código de Estudiante:</div>
                    <div class="info-value">{{ $pago->inscripcion->estudiante->codigo }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Correo Electrónico:</div>
                    <div class="info-value">{{ $pago->inscripcion->estudiante->correo_institucional ?? 'No registrado' }}</div>
                </div>
            </div>
        </div>

        <!-- Información del Curso -->
        <div class="info-section">
            <div class="section-title">
                📚 DATOS DEL CURSO
            </div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nombre del Curso:</div>
                    <div class="info-value">{{ $pago->inscripcion->curso->nombre }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Código:</div>
                    <div class="info-value">{{ $pago->inscripcion->curso->codigo }}</div>
                </div>
                @if($pago->inscripcion->curso->duracion_horas)
                <div class="info-row">
                    <div class="info-label">Duración:</div>
                    <div class="info-value">{{ $pago->inscripcion->curso->duracion_horas }} horas académicas</div>
                </div>
                @endif
                @if($pago->inscripcion->curso->fecha_inicio)
                <div class="info-row">
                    <div class="info-label">Fecha de Inicio:</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($pago->inscripcion->curso->fecha_inicio)->format('d/m/Y') }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Información del Pago -->
        <div class="info-section">
            <div class="section-title">
                💳 DATOS DEL PAGO
            </div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Método de Pago:</div>
                    <div class="info-value">{{ $pago->metodoPago->nombre }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha de Pago:</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                    </div>
                </div>
                @if($pago->numero_operacion)
                <div class="info-row">
                    <div class="info-label">Número de Operación:</div>
                    <div class="info-value">{{ $pago->numero_operacion }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Estado del Pago:</div>
                    <div class="info-value">
                        @if($pago->estado == 'confirmado')
                            <span class="estado-badge estado-confirmado">CONFIRMADO</span>
                        @else
                            <span class="estado-badge estado-pendiente">⏱ PENDIENTE</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Monto Total -->
        <div class="monto-section">
            <div class="monto-label">MONTO TOTAL PAGADO</div>
            <div class="monto-valor">S/ {{ number_format($pago->monto, 2) }}</div>
        </div>

        <!-- Observaciones -->
        @if($pago->observaciones)
        <div class="info-section">
            <div class="section-title">
                📝 OBSERVACIONES
            </div>
            <div style="padding: 10px; font-size: 12px; color: #555;">
                {{ $pago->observaciones }}
            </div>
        </div>
        @endif

        <!-- Código de Verificación -->
        <div class="verificacion-code">
            <div style="font-size: 14px; color: #555; margin-bottom: 10px;">
                <strong>Código de Verificación</strong>
            </div>
            <div class="code-display">
                {{ $pago->codigo_pago }}
            </div>
            <div style="font-size: 11px; color: #7f8c8d; margin-top: 10px;">
                Use este código para verificar la autenticidad del comprobante
            </div>
        </div>

        <!-- Alerta de Validez -->
        <div class="alert-box">
            <strong>⚠ IMPORTANTE:</strong> Este comprobante es válido únicamente con el código de verificación y puede ser validado en la oficina de Tesorería. 
            La Universidad Nacional Daniel Alcides Carrión no se hace responsable por comprobantes alterados o falsificados.
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <div class="footer-notes">
                <strong>Notas Importantes:</strong><br>
                • Este comprobante certifica el pago realizado por el concepto indicado.<br>
                • Para cualquier consulta o reclamo, presentar este documento en la oficina de Tesorería.<br>
                • El comprobante debe conservarse durante toda la duración del curso.<br>
                • Fecha de emisión: {{ $fecha_generacion }}<br>
                • Generado por: {{ $usuario_generador }}<br>
                • Documento generado electrónicamente por el Sistema SKY - EISC UNDAC.
            </div>

            <div class="footer-signatures">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">Tesorería EISC</div>
                    <div class="signature-title">Universidad Nacional Daniel Alcides Carrión</div>
                </div>
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">Coordinación de Cursos</div>
                    <div class="signature-title">Escuela de Ingeniería de Sistemas</div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #7f8c8d;">
                <strong>Universidad Nacional Daniel Alcides Carrión</strong><br>
                Av. San Juan 390, Cerro de Pasco - Perú<br>
                Teléfono: (063) 422-090 | Email: cursos@undac.edu.pe<br>
                www.undac.edu.pe
            </div>
        </div>
    </div>
</body>
</html>