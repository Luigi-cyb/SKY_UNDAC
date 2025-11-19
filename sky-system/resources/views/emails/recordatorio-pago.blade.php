<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Pago</title>
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
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
            color: #fa709a;
            margin-bottom: 20px;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #721c24;
        }
        .payment-summary {
            background: linear-gradient(135deg, #fa709a15 0%, #fee14015 100%);
            border: 2px solid #fa709a;
            padding: 25px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .payment-amount {
            font-size: 48px;
            font-weight: bold;
            color: #fa709a;
            text-align: center;
            margin: 15px 0;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #fa709a;
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
            background-color: #fa709a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(250, 112, 154, 0.3);
        }
        .button:hover {
            background-color: #e95f88;
        }
        .button-secondary {
            background-color: #6c757d;
            box-shadow: 0 4px 6px rgba(108, 117, 125, 0.3);
        }
        .button-secondary:hover {
            background-color: #5a6268;
        }
        .payment-methods {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .payment-method {
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 8px 0;
            background-color: white;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        .payment-icon {
            font-size: 24px;
            margin-right: 12px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        .footer a {
            color: #fa709a;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 25px 0;
        }
        .countdown {
            text-align: center;
            padding: 20px;
            background-color: #fff3cd;
            border-radius: 8px;
            margin: 20px 0;
        }
        .countdown-number {
            font-size: 36px;
            font-weight: bold;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>⏰ Recordatorio de Pago</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Hola {{ $estudiante->nombre }} {{ $estudiante->apellido }},</p>
            
            <p>Te recordamos que tienes un pago pendiente para confirmar tu inscripción en el curso <strong>{{ $curso->nombre }}</strong>.</p>

            @if(isset($dias_restantes) && $dias_restantes <= 3)
            <!-- Alerta de Urgencia -->
            <div class="alert-danger">
                <strong>⚠️ ¡IMPORTANTE!</strong> Tu plazo de pago vence en <strong>{{ $dias_restantes }} día(s)</strong>. 
                Si no realizas el pago antes del {{ \Carbon\Carbon::parse($fecha_vencimiento)->format('d/m/Y') }}, 
                tu inscripción será cancelada automáticamente.
            </div>

            <!-- Contador de Días -->
            <div class="countdown">
                <div class="countdown-number">{{ $dias_restantes }}</div>
                <div style="color: #856404; font-weight: 600;">
                    {{ $dias_restantes == 1 ? 'día restante' : 'días restantes' }}
                </div>
            </div>
            @else
            <div class="alert-warning">
                <strong>📌 Nota:</strong> Por favor completa tu pago antes del 
                <strong>{{ \Carbon\Carbon::parse($fecha_vencimiento)->format('d/m/Y') }}</strong> 
                para asegurar tu lugar en el curso.
            </div>
            @endif

            <!-- Resumen de Pago -->
            <div class="payment-summary">
                <h3 style="margin-top: 0; color: #fa709a; text-align: center;">💳 Monto a Pagar</h3>
                <div class="payment-amount">
                    S/ {{ number_format($monto, 2) }}
                </div>
                <p style="text-align: center; color: #6c757d; margin: 0;">
                    Soles Peruanos
                </p>
            </div>

            <!-- Información del Curso -->
            <div class="info-box">
                <h3 style="margin-top: 0; color: #fa709a;">📚 Detalles del Curso</h3>
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
                    <span class="info-label">Inicio:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Número de Inscripción:</span>
                    <span class="info-value">#{{ str_pad($inscripcion->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Métodos de Pago -->
            <div class="payment-methods">
                <h3 style="margin-top: 0; color: #fa709a;">💰 Métodos de Pago Disponibles</h3>
                
                <div class="payment-method">
                    <span class="payment-icon">🏦</span>
                    <div>
                        <strong>Transferencia Bancaria</strong><br>
                        <span style="font-size: 13px; color: #6c757d;">
                            BCP, BBVA, Interbank, Scotiabank
                        </span>
                    </div>
                </div>

                <div class="payment-method">
                    <span class="payment-icon">💳</span>
                    <div>
                        <strong>Tarjeta de Crédito/Débito</strong><br>
                        <span style="font-size: 13px; color: #6c757d;">
                            Visa, Mastercard, American Express
                        </span>
                    </div>
                </div>

                <div class="payment-method">
                    <span class="payment-icon">📱</span>
                    <div>
                        <strong>Pago Móvil</strong><br>
                        <span style="font-size: 13px; color: #6c757d;">
                            Yape, Plin, BIM
                        </span>
                    </div>
                </div>

                <div class="payment-method">
                    <span class="payment-icon">🏪</span>
                    <div>
                        <strong>Pago en Efectivo</strong><br>
                        <span style="font-size: 13px; color: #6c757d;">
                            Oficina de Tesorería - UNDAC
                        </span>
                    </div>
                </div>
            </div>

            <!-- Datos Bancarios -->
            <div style="background-color: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #004085;">🏦 Datos para Transferencia</h4>
                <p style="margin: 5px 0; color: #004085; font-size: 14px;">
                    <strong>Banco:</strong> Banco de la Nación<br>
                    <strong>Cuenta Corriente:</strong> 0000-123456-78<br>
                    <strong>CCI:</strong> 018-000-012345678901<br>
                    <strong>Titular:</strong> Universidad Nacional Daniel Alcides Carrión
                </p>
                <p style="margin: 10px 0 0 0; color: #856404; font-size: 12px;">
                    ⚠️ Importante: Envía tu comprobante de pago a eisc@undac.edu.pe indicando tu DNI y código de inscripción
                </p>
            </div>

            <!-- Botones de Acción -->
            <div style="text-align: center;">
                <a href="{{ route('pagos.create', ['inscripcion_id' => $inscripcion->id]) }}" class="button">
                    💳 Realizar Pago Ahora
                </a>
                <br>
                <a href="{{ route('inscripciones.show', $inscripcion->id) }}" class="button button-secondary">
                    📋 Ver Mi Inscripción
                </a>
            </div>

            <div class="divider"></div>

            <!-- Preguntas Frecuentes -->
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px;">
                <h3 style="margin-top: 0; color: #fa709a;">❓ Preguntas Frecuentes</h3>
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #495057;">¿Qué pasa si no pago a tiempo?</strong>
                    <p style="margin: 5px 0; color: #6c757d; font-size: 14px;">
                        Tu inscripción será cancelada y perderás tu cupo en el curso.
                    </p>
                </div>

                <div style="margin-bottom: 15px;">
                    <strong style="color: #495057;">¿Puedo pagar en cuotas?</strong>
                    <p style="margin: 5px 0; color: #6c757d; font-size: 14px;">
                        Contacta con nuestra oficina para consultar opciones de financiamiento.
                    </p>
                </div>

                <div style="margin-bottom: 15px;">
                    <strong style="color: #495057;">¿Cómo confirmo mi pago?</strong>
                    <p style="margin: 5px 0; color: #6c757d; font-size: 14px;">
                        Envía tu comprobante por email o súbelo en tu portal de estudiante.
                    </p>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Contacto -->
            <div style="text-align: center; background-color: #fff3cd; padding: 15px; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #856404;">📞 ¿Necesitas Ayuda?</h4>
                <p style="margin: 5px 0; color: #856404; font-size: 14px;">
                    Estamos aquí para ayudarte:<br>
                    📧 <a href="mailto:eisc@undac.edu.pe" style="color: #856404;">eisc@undac.edu.pe</a><br>
                    📱 WhatsApp: +51 963 852 741<br>
                    ⏰ Horario: Lunes a Viernes, 8:00 AM - 5:00 PM
                </p>
            </div>

            <p style="margin-top: 20px; color: #6c757d; font-size: 14px; text-align: center;">
                Gracias por tu atención. Esperamos verte pronto en nuestras aulas. 🎓
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