<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago - {{ $pago->numero_comprobante }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 30px;
            background: #f8fafc;
        }
        
        .comprobante-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 30px;
            position: relative;
        }
        
        .header-content {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 20px;
            align-items: center;
        }
        
        .logo-section {
            text-align: center;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #1e40af;
            font-weight: bold;
        }
        
        .institution-info h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .institution-info p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .comprobante-type {
            position: absolute;
            top: 30px;
            right: 30px;
            background: #fbbf24;
            color: #92400e;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .comprobante-number {
            background: #eff6ff;
            padding: 20px 30px;
            border-bottom: 3px solid #3b82f6;
        }
        
        .comprobante-number-content {
            display: flex;
            justify-between;
            align-items: center;
        }
        
        .number-label {
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .number-value {
            font-size: 24px;
            color: #1e40af;
            font-weight: bold;
        }
        
        .date-value {
            color: #475569;
            font-size: 14px;
        }
        
        .content-section {
            padding: 30px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .info-block h3 {
            color: #1e40af;
            font-size: 14px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #64748b;
            font-size: 11px;
        }
        
        .info-value {
            color: #1e3a8a;
            font-weight: 600;
            font-size: 12px;
        }
        
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        .detail-table thead {
            background: #1e40af;
            color: white;
        }
        
        .detail-table th {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        
        .detail-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .detail-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .totals-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #3b82f6;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .payment-method {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
        }
        
        .payment-method h4 {
            color: #1e40af;
            font-size: 12px;
            margin-bottom: 8px;
        }
        
        .payment-method p {
            color: #475569;
            font-size: 11px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .notes-section {
            background: #fffbeb;
            border: 1px dashed #fbbf24;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .notes-section h4 {
            color: #92400e;
            font-size: 12px;
            margin-bottom: 8px;
        }
        
        .notes-section p {
            color: #78350f;
            font-size: 11px;
            line-height: 1.6;
        }
        
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            border-top: 3px solid #3b82f6;
            text-align: center;
        }
        
        .footer-text {
            color: #64748b;
            font-size: 10px;
            margin-bottom: 10px;
        }
        
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 10px;
            color: #475569;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e1;
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
            margin: 0 auto 10px;
        }
    </style>
</head>
<body>
    <div class="comprobante-container">
        <!-- Encabezado -->
        <div class="header">
            <div class="comprobante-type">COMPROBANTE DE PAGO</div>
            <div class="header-content">
                <div class="logo-section">
                    <div class="logo">SKY</div>
                </div>
                <div class="institution-info">
                    <h1>Universidad Nacional Daniel Alcides Carrión</h1>
                    <p>Escuela de Ingeniería de Sistemas y Computación</p>
                    <p>RUC: 20123456789 | Cerro de Pasco - Perú</p>
                </div>
            </div>
        </div>
        
        <!-- Número de comprobante -->
        <div class="comprobante-number">
            <div class="comprobante-number-content">
                <div>
                    <div class="number-label">Número de Comprobante</div>
                    <div class="number-value">{{ $pago->numero_comprobante }}</div>
                     </div>
                <div style="text-align: right;">
                    <div class="number-label">Fecha de Emisión</div>
                    <div class="date-value">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</div>
                    <div class="status-badge {{ $pago->estado == 'confirmado' ? 'status-paid' : 'status-pending' }}">
                        {{ $pago->estado == 'confirmado' ? 'PAGADO' : 'PENDIENTE' }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenido principal -->
        <div class="content-section">
            <!-- Información del estudiante y curso -->
            <div class="info-grid">
                <div class="info-block">
                    <h3>Información del Estudiante</h3>
                    <div class="info-row">
                        <span class="info-label">Nombre Completo:</span>
                        <span class="info-value">{{ $pago->estudiante_nombre }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">DNI:</span>
                        <span class="info-value">{{ $pago->estudiante_dni }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Código:</span>
                        <span class="info-value">{{ $pago->estudiante_codigo }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Correo:</span>
                        <span class="info-value">{{ $pago->estudiante_email }}</span>
                    </div>
                </div>
                
                <div class="info-block">
                    <h3>Información del Curso</h3>
                    <div class="info-row">
                        <span class="info-label">Curso:</span>
                        <span class="info-value">{{ $pago->curso_nombre }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Código:</span>
                        <span class="info-value">{{ $pago->curso_codigo }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Modalidad:</span>
                        <span class="info-value">{{ $pago->modalidad }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Duración:</span>
                        <span class="info-value">{{ $pago->horas_academicas }} horas</span>
                    </div>
                </div>
            </div>
            
            <!-- Detalle del pago -->
            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Concepto</th>
                        <th style="width: 20%; text-align: center;">Cantidad</th>
                        <th style="width: 15%; text-align: right;">Precio Unit.</th>
                        <th style="width: 15%; text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Inscripción al Curso</strong><br>
                            <small style="color: #64748b;">{{ $pago->curso_nombre }}</small>
                        </td>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: right;">S/ {{ number_format($pago->monto, 2) }}</td>
                        <td style="text-align: right;">S/ {{ number_format($pago->monto, 2) }}</td>
                    </tr>
                    @if($pago->material_adicional ?? false)
                    <tr>
                        <td>
                            <strong>Material Didáctico</strong><br>
                            <small style="color: #64748b;">Material del curso incluido</small>
                        </td>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: right;">S/ {{ number_format($pago->costo_material ?? 0, 2) }}</td>
                        <td style="text-align: right;">S/ {{ number_format($pago->costo_material ?? 0, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <!-- Totales -->
            <div class="totals-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>S/ {{ number_format($pago->monto, 2) }}</span>
                </div>
                @if($pago->descuento ?? 0 > 0)
                <div class="total-row" style="color: #059669;">
                    <span>Descuento ({{ $pago->porcentaje_descuento ?? 0 }}%):</span>
                    <span>- S/ {{ number_format($pago->descuento, 2) }}</span>
                </div>
                @endif
                <div class="total-row grand-total">
                    <span>TOTAL A PAGAR:</span>
                    <span>S/ {{ number_format($pago->monto_final ?? $pago->monto, 2) }}</span>
                </div>
            </div>
            
            <!-- Método de pago -->
            <div class="payment-method">
                <h4>MÉTODO DE PAGO</h4>
                <p><strong>Tipo:</strong> {{ $pago->metodo_pago }}</p>
                @if($pago->numero_operacion)
                <p><strong>Número de Operación:</strong> {{ $pago->numero_operacion }}</p>
                @endif
                @if($pago->banco)
                <p><strong>Banco:</strong> {{ $pago->banco }}</p>
                @endif
                <p><strong>Fecha de Pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i:s') }}</p>
            </div>
            
            <!-- Notas -->
            @if($pago->observaciones)
            <div class="notes-section">
                <h4>OBSERVACIONES</h4>
                <p>{{ $pago->observaciones }}</p>
            </div>
            @endif
            
            <div class="notes-section">
                <h4>INFORMACIÓN IMPORTANTE</h4>
                <p>
                    • Este comprobante es válido como constancia de pago de inscripción.<br>
                    • Conserve este documento para cualquier reclamo o consulta.<br>
                    • La inscripción es personal e intransferible.<br>
                    • No se realizan devoluciones una vez iniciado el curso.<br>
                    • Para consultas, comuníquese con la oficina de la EISC.
                </p>
            </div>
            
            <!-- Código QR -->
            <div class="qr-section">
                <p style="color: #64748b; font-size: 11px; margin-bottom: 10px;">
                    Escanea el código QR para verificar la autenticidad
                </p>
                <div class="qr-code">
                    {!! $pago->qr_code !!}
                </div>
                <p style="color: #94a3b8; font-size: 10px;">
                    Código de Verificación: <strong>{{ $pago->codigo_verificacion }}</strong>
                </p>
            </div>
        </div>
        
        <!-- Pie de página -->
        <div class="footer">
            <p class="footer-text">
                <strong>Sistema SKY - Gestión de Cursos Extracurriculares</strong>
            </p>
            <p class="footer-text">
                Este documento ha sido generado automáticamente y no requiere firma ni sello.
            </p>
            <div class="contact-info">
                <span>📧 eisc@undac.edu.pe</span>
                <span>📞 (063) 123-4567</span>
                <span>🌐 www.undac.edu.pe</span>
            </div>
        </div>
    </div>
</body>
</html>