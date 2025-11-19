<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $certificado->estudiante_nombre }}</title>
    <style>
        @page {
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            position: relative;
            min-height: 100vh;
        }
        
        .certificate-container {
            background: white;
            border: 20px solid #1e40af;
            border-image: linear-gradient(45deg, #667eea, #764ba2, #f093fb, #f5576c) 1;
            padding: 60px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .decorative-corner {
            position: absolute;
            width: 100px;
            height: 100px;
            border: 3px solid #d4af37;
        }
        
        .corner-tl {
            top: 30px;
            left: 30px;
            border-right: none;
            border-bottom: none;
        }
        
        .corner-tr {
            top: 30px;
            right: 30px;
            border-left: none;
            border-bottom: none;
        }
        
        .corner-bl {
            bottom: 30px;
            left: 30px;
            border-right: none;
            border-top: none;
        }
        
        .corner-br {
            bottom: 30px;
            right: 30px;
            border-left: none;
            border-top: none;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
        }
        
        .institution-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .school-name {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .certificate-title {
            font-size: 42px;
            color: #d4af37;
            margin: 30px 0;
            text-transform: uppercase;
            letter-spacing: 4px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .content {
            text-align: center;
            margin: 40px 0;
            line-height: 2;
        }
        
        .certifies-text {
            font-size: 18px;
            color: #475569;
            margin-bottom: 20px;
        }
        
        .student-name {
            font-size: 36px;
            color: #1e40af;
            font-weight: bold;
            margin: 20px 0;
            padding: 15px 30px;
            border-bottom: 3px solid #d4af37;
            display: inline-block;
        }
        
        .course-info {
            font-size: 16px;
            color: #475569;
            margin: 30px 0;
            line-height: 1.8;
        }
        
        .course-name {
            font-size: 24px;
            color: #1e40af;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 40px auto;
            max-width: 600px;
            text-align: left;
        }
        
        .detail-item {
            padding: 10px;
            background: #f8fafc;
            border-left: 4px solid #2563eb;
        }
        
        .detail-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .detail-value {
            font-size: 16px;
            color: #1e40af;
            font-weight: bold;
        }
        
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 80px;
        }
        
        .signature-block {
            text-align: center;
        }
        
        .signature-line {
            border-top: 2px solid #1e40af;
            margin-bottom: 10px;
            padding-top: 5px;
        }
        
        .signature-name {
            font-size: 14px;
            color: #1e40af;
            font-weight: bold;
        }
        
        .signature-title {
            font-size: 12px;
            color: #64748b;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px dashed #cbd5e1;
        }
        
        .qr-code {
            width: 120px;
            height: 120px;
            margin: 0 auto 15px;
        }
        
        .verification-code {
            font-size: 14px;
            color: #64748b;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .verification-text {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 10px;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(30, 64, 175, 0.05);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Esquinas decorativas -->
        <div class="decorative-corner corner-tl"></div>
        <div class="decorative-corner corner-tr"></div>
        <div class="decorative-corner corner-bl"></div>
        <div class="decorative-corner corner-br"></div>
        
        <!-- Marca de agua -->
        <div class="watermark">UNDAC</div>
        
        <!-- Encabezado -->
        <div class="header">
            {{-- <div class="logo">
                <img src="{{ public_path('images/logo-undac.png') }}" alt="Logo UNDAC" style="width: 100%;">
            </div> --}}
            <div class="institution-name">Universidad Nacional Daniel Alcides Carrión</div>
            <div class="school-name">Escuela de Ingeniería de Sistemas y Computación</div>
        </div>
        
        <!-- Título del certificado -->
        <div class="certificate-title">CERTIFICADO</div>
        
        <!-- Contenido -->
        <div class="content">
            <p class="certifies-text">La Escuela de Ingeniería de Sistemas y Computación certifica que:</p>
            
            <div class="student-name">{{ strtoupper($certificado->estudiante_nombre) }}</div>
            
            <p class="course-info">
                Ha completado satisfactoriamente el curso extracurricular:
            </p>
            
            <div class="course-name">{{ $certificado->curso_nombre }}</div>
            
            <!-- Detalles del curso -->
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Duración</div>
                    <div class="detail-value">{{ $certificado->horas_academicas }} horas académicas</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Calificación</div>
                    <div class="detail-value">{{ number_format($certificado->nota_final, 2) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Fecha de Inicio</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($certificado->fecha_inicio)->format('d/m/Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Fecha de Finalización</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($certificado->fecha_fin)->format('d/m/Y') }}</div>
                </div>
            </div>
            
            <p class="course-info">
                En reconocimiento a su dedicación y excelente desempeño académico.
            </p>
        </div>
        
        <!-- Firmas -->
        <div class="signatures">
            <div class="signature-block">
                <div class="signature-line">
                    <div class="signature-name">{{ $certificado->firma_director }}</div>
                </div>
                <div class="signature-title">Director de Escuela</div>
                <div class="signature-title">EISC - UNDAC</div>
            </div>
            <div class="signature-block">
                <div class="signature-line">
                    <div class="signature-name">{{ $certificado->firma_docente }}</div>
                </div>
                <div class="signature-title">Docente del Curso</div>
                <div class="signature-title">{{ $certificado->curso_codigo }}</div>
            </div>
        </div>
        
        <!-- Código QR y verificación -->
        <div class="qr-section">
            <div class="qr-code">
                {!! $certificado->qr_code !!}
            </div>
            <div class="verification-code">
                Código de Verificación: {{ $certificado->codigo_verificacion }}
            </div>
            <p class="verification-text">
                Verifique la autenticidad de este certificado en: {{ config('app.url') }}/verificar-certificado
            </p>
            <p class="verification-text">
                Cerro de Pasco, {{ \Carbon\Carbon::parse($certificado->fecha_emision)->format('d') }} de 
                {{ \Carbon\Carbon::parse($certificado->fecha_emision)->locale('es')->monthName }} de 
                {{ \Carbon\Carbon::parse($certificado->fecha_emision)->format('Y') }}
            </p>
        </div>
    </div>
</body>
</html>