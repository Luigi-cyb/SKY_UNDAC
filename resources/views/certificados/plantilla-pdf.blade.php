<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado - {{ $certificado->codigo_certificado }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            color: #2c3e50;
        }
        
        /* ========== COMÚN PARA AMBAS PÁGINAS ========== */
        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            overflow: hidden;
        }
        
        .border-frame {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 4px solid #1a472a;
        }
        
        .border-frame::before {
            content: '';
            position: absolute;
            top: 4mm;
            left: 4mm;
            right: 4mm;
            bottom: 4mm;
            border: 1.5px solid #d4af37;
        }
        
        .decoration-top {
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 100mm 100mm 0;
            border-color: transparent #8b1538 transparent transparent;
            opacity: 0.1;
        }
        
        /* ========== PÁGINA 1: CERTIFICADO ========== */
        .page-certificado {
            page-break-after: always;
        }
        
        .cert-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            text-align: center;
            width: 260mm;
        }
        
        .cert-header {
            margin-bottom: 10mm;
            padding-bottom: 5mm;
            border-bottom: 2px solid #1a472a;
        }
        
        .logo-undac {
            width: 50px;
            height: 50px;
            margin: 0 auto 5px;
        }
        
        .logo-undac img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .uni-name {
            font-size: 17px;
            font-weight: bold;
            color: #1a472a;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 4px 0;
        }
        
        .uni-dept {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            margin: 2px 0;
        }
        
        .uni-program {
            font-size: 8px;
            color: #8b1538;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
        }
        
        .cert-body {
            margin: 8mm 0;
        }
        
        .title-certificado {
            font-size: 50px;
            font-weight: bold;
            color: #8b1538;
            font-style: italic;
            margin-bottom: 6mm;
            letter-spacing: 2px;
        }
        
        .otorgado {
            font-size: 11px;
            color: #333;
            margin-bottom: 5mm;
        }
        
        .student-name {
            font-size: 26px;
            font-weight: bold;
            color: #8b1538;
            text-transform: uppercase;
            margin: 5mm auto;
            padding-bottom: 4px;
            border-bottom: 2.5px solid #1a472a;
            max-width: 60%;
            letter-spacing: 1.5px;
        }
        
        .curso-intro {
            font-size: 11px;
            color: #333;
            margin: 5mm 0 3mm 0;
        }
        
        .curso-nombre {
            font-size: 17px;
            font-weight: bold;
            color: #1a472a;
            text-transform: uppercase;
            margin: 3mm 0 5mm 0;
            letter-spacing: 1px;
        }
        
        .detalles-box {
            font-size: 10px;
            color: #444;
            line-height: 1.6;
            margin: 0 auto;
            max-width: 70%;
        }
        
        .cert-footer {
            margin-top: 8mm;
            width: 100%;
        }
        
        .fecha-lugar {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-bottom: 8mm;
        }
        
        .firmas-container {
            display: table;
            width: 100%;
            margin-top: 5mm;
        }
        
        .firmas-row {
            display: table-row;
        }
        
        .firma-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 10mm;
        }
        
        .firma-linea {
            width: 140px;
            height: 1.5px;
            background: #1a472a;
            margin: 0 auto 5px auto;
        }
        
        .firma-nombre {
            font-size: 10px;
            font-weight: bold;
            color: #1a472a;
            margin: 3px 0 1px 0;
        }
        
        .firma-cargo {
            font-size: 8px;
            color: #666;
            font-weight: bold;
        }
        
        .qr-section {
            text-align: center;
            padding: 6px;
            background: white;
            border: 2.5px solid #1a472a;
            border-radius: 5px;
            display: inline-block;
        }
        
        .qr-title {
            font-size: 7px;
            color: #1a472a;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .qr-code-box {
            width: 75px;
            height: 75px;
            margin: 2px auto;
        }
        
        .qr-code-box img {
            width: 100%;
            height: 100%;
        }
        
        .qr-text {
            font-size: 6px;
            color: #666;
            margin-top: 3px;
            font-family: 'Courier New', monospace;
        }
        
        .cert-number {
            position: absolute;
            top: 15mm;
            right: 18mm;
            background: #8b1538;
            color: white;
            padding: 5px 10px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 3px;
            transform: rotate(90deg);
            transform-origin: right top;
            z-index: 100;
        }
        
        /* ========== PÁGINA 2: TEMARIO ========== */
        .decoration-bottom-left {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 100mm 0 0 100mm;
            border-color: transparent transparent transparent #1a472a;
            opacity: 0.08;
        }
        
        .temario-content-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            width: 260mm;
        }
        
        .temario-header {
            text-align: center;
            margin-bottom: 8mm;
            padding-bottom: 4mm;
            border-bottom: 3px solid #1a472a;
            position: relative;
        }
        
        .temario-header::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #d4af37;
        }
        
        .logo-temario {
            width: 40px;
            height: 40px;
            margin: 0 auto 4px;
        }
        
        .logo-temario img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .temario-title {
            font-size: 15px;
            font-weight: bold;
            color: #1a472a;
            text-transform: uppercase;
            margin: 4px 0;
            letter-spacing: 0.7px;
        }
        
        .temario-subtitle {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }
        
        .temario-content {
            display: table;
            width: 100%;
            margin-bottom: 6mm;
        }
        
        .temario-columna {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 5mm;
        }
        
        .columna-titulo {
            font-size: 10px;
            font-weight: bold;
            color: white;
            background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%);
            padding: 4px 8px;
            margin-bottom: 5px;
            border-radius: 3px;
            text-transform: uppercase;
        }
        
        .temario-lista {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .temario-lista li {
            font-size: 8px;
            line-height: 1.4;
            color: #333;
            margin-bottom: 2px;
            padding-left: 12px;
            position: relative;
        }
        
        .temario-lista li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #8b1538;
            font-size: 10px;
            font-weight: bold;
        }
        
        .info-adicional {
            margin-top: 6mm;
            padding: 6px 12px;
            background: rgba(248, 249, 250, 0.95);
            border-left: 4px solid #8b1538;
            border-radius: 3px;
        }
        
        .info-adicional-titulo {
            font-size: 10px;
            font-weight: bold;
            color: #8b1538;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        
        .info-adicional-texto {
            font-size: 8px;
            color: #444;
            line-height: 1.4;
        }
        
        .temario-footer {
            margin-top: 8mm;
            padding-top: 6mm;
            border-top: 3px solid #1a472a;
            text-align: center;
            position: relative;
        }
        
        .temario-footer::before {
            content: '';
            position: absolute;
            top: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #d4af37;
        }
        
        .footer-item {
            display: inline-block;
        }
        
        .footer-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            margin-right: 8px;
            font-weight: bold;
        }
        
        .footer-value {
            font-size: 16px;
            font-weight: bold;
            color: #8b1538;
        }
        
        .sello-digital {
            position: absolute;
            bottom: 15mm;
            right: 23mm;
            font-size: 7px;
            color: #999;
            z-index: 100;
        }
    </style>
</head>
<body>

@php
    // Generar QR en base64
    $qrUrl = url('/certificado/' . $certificado->codigo_qr);
    $qrCodeData = base64_encode(@file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrUrl)));
    
    // Logo UNDAC en base64
    $logoUrl = 'https://undac.edu.pe/wp-content/uploads/elementor/thumbs/undac-favicon-racv7w0vcrg7f2hyb2mmumtxhmtnyapfvxs4kshf3k.png';
    $logoData = base64_encode(@file_get_contents($logoUrl));
@endphp

<!-- PÁGINA 1 -->
<div class="page page-certificado">
    <div class="decoration-top"></div>
    <div class="border-frame"></div>
    
    <div class="cert-number">
        N° {{ $certificado->codigo_certificado }}
    </div>
    
    <div class="cert-content">
        <div class="cert-header">
            <div class="logo-undac">
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" alt="UNDAC">
                @else
                    <div style="width: 50px; height: 50px; background: #1a472a; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; margin: 0 auto;">UNDAC</div>
                @endif
            </div>
            <div class="uni-name">Universidad Nacional Daniel Alcides Carrión</div>
            <div class="uni-dept">Oficina de Capacitación y Desarrollo Profesional</div>
            <div class="uni-program">Sistema de Certificación Académica - 2025</div>
        </div>

        <div class="cert-body">
            <div class="title-certificado">CERTIFICADO</div>
            <div class="otorgado">Otorgado a</div>
            <div class="student-name">
                {{ strtoupper($estudiante->nombres) }} {{ strtoupper($estudiante->apellidos) }}
            </div>
            <div class="curso-intro">Por haber aprobado el curso de</div>
            <div class="curso-nombre">{{ strtoupper($curso->nombre) }}</div>
            <div class="detalles-box">
                <strong>Realizado del</strong> {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d') }} de {{ \Carbon\Carbon::parse($curso->fecha_inicio)->translatedFormat('F') }} 
                al {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d') }} de {{ \Carbon\Carbon::parse($curso->fecha_fin)->translatedFormat('F') }} del presente, 
                con una duración de <strong>{{ $curso->horas_academicas ?? 24 }} horas.</strong>
            </div>
        </div>

        <div class="cert-footer">
            <div class="fecha-lugar">
                Cerro de Pasco, {{ $certificado->fecha_emision->translatedFormat('F') }} de {{ $certificado->fecha_emision->format('Y') }}
            </div>
            
            <div class="firmas-container">
                <div class="firmas-row">
                    <div class="firma-cell">
                        <div class="firma-linea"></div>
                        <div class="firma-nombre">DR. LUIS ALBERTO PACHECO</div>
                        <div class="firma-cargo">Director de EFP de Ingenieria de Sistemas y Computacion</div>
                    </div>
                    
                    <div class="firma-cell">
                        <div class="qr-section">
                            <div class="qr-title">Código QR</div>
                            <div class="qr-code-box">
                                @if($qrCodeData)
                                    <img src="data:image/png;base64,{{ $qrCodeData }}" alt="QR Code">
                                @endif
                            </div>
                            <div class="qr-text">{{ $certificado->codigo_qr }}</div>
                        </div>
                    </div>
                    
                    <div class="firma-cell">
                        <div class="firma-linea"></div>
                        <div class="firma-nombre">DR. TITO MARCIAL ARIAS ARZAPALO</div>
                        <div class="firma-cargo">Decano de la Facultad de Ingenieria</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PÁGINA 2 -->
<div class="page">
    <div class="decoration-bottom-left"></div>
    <div class="border-frame"></div>
    
    <div class="sello-digital">
        N° Certificado: {{ $certificado->codigo_certificado }}
    </div>
    
    <div class="temario-content-wrapper">
        <div class="temario-header">
            <div class="logo-temario">
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" alt="UNDAC">
                @else
                    <div style="width: 40px; height: 40px; background: #1a472a; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; margin: 0 auto;">UNDAC</div>
                @endif
            </div>
            <div class="temario-title">{{ strtoupper($curso->nombre) }}</div>
            <div class="temario-subtitle">Universidad Nacional Daniel Alcides Carrión - Oficina de Capacitación</div>
        </div>

        <div class="temario-content">
            @if($curso->temario)
                @php
                    $temarioLimpio = str_replace(['•', '?', ' ', '-', '–'], '', $curso->temario);
                    $temas = array_filter(array_map('trim', preg_split('/[\n\r]+/', $temarioLimpio)));
                    $temasFiltrados = array_values(array_filter($temas, function($tema) {
                        return strlen(trim($tema)) > 2;
                    }));
                    $mitad = ceil(count($temasFiltrados) / 2);
                @endphp
                
                <div class="temario-columna">
                    <div class="columna-titulo">Temario del Curso:</div>
                    <ul class="temario-lista">
                        @foreach(array_slice($temasFiltrados, 0, $mitad) as $tema)
                            <li>{{ trim($tema) }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="temario-columna">
                    <div class="columna-titulo">&nbsp;</div>
                    <ul class="temario-lista">
                        @foreach(array_slice($temasFiltrados, $mitad) as $tema)
                            <li>{{ trim($tema) }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="temario-columna">
                    <div class="columna-titulo">Contenido del Curso:</div>
                    <ul class="temario-lista">
                        <li>Introducción y fundamentos teóricos</li>
                        <li>Desarrollo de competencias prácticas</li>
                        <li>Casos de estudio y aplicaciones</li>
                        <li>Evaluación final y certificación</li>
                    </ul>
                </div>
            @endif
        </div>

        @if($curso->objetivos)
        <div class="info-adicional">
            <div class="info-adicional-titulo">Objetivos del Curso</div>
            <div class="info-adicional-texto">{{ $curso->objetivos }}</div>
        </div>
        @endif

        <div class="temario-footer">
            <div class="footer-item">
                <span class="footer-label">Nota Final:</span>
                <span class="footer-value">{{ number_format($inscripcion->nota_final ?? 0, 0) }} ({{ $notaEnLetras($inscripcion->nota_final ?? 0) }})</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>