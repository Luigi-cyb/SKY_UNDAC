<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .certificate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px;
            text-align: center;
            color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .certificate-header {
            margin-bottom: 40px;
        }
        .certificate-header h1 {
            font-size: 48px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .certificate-body {
            margin: 40px 0;
            font-size: 16px;
        }
        .student-name {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }
        .course-info {
            margin: 30px 0;
            font-size: 18px;
        }
        .certificate-footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-around;
            font-size: 12px;
        }
        .signature-line {
            width: 150px;
            border-top: 2px solid white;
            margin-top: 40px;
        }
        .code-info {
            margin-top: 30px;
            font-size: 12px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="certificate-header">
            <h1>🎓 CERTIFICADO</h1>
            <p style="margin: 10px 0; font-size: 18px;">DE COMPETENCIA PROFESIONAL</p>
        </div>

        <div class="certificate-body">
            <p style="margin: 0; font-size: 16px;">Se certifica que</p>
            
            <div class="student-name">
                {{ strtoupper($estudiante->nombres) }} {{ strtoupper($estudiante->apellidos) }}
            </div>

            <p style="margin: 20px 0; font-size: 16px;">
                Ha completado satisfactoriamente el curso de:
            </p>

            <div class="course-info">
                <strong style="font-size: 20px;">{{ strtoupper($curso->nombre) }}</strong><br>
                <span style="font-size: 14px;">Código: {{ $curso->codigo }}</span>
            </div>

            <p style="margin: 20px 0; font-size: 14px;">
                Con una calificación de: <strong style="font-size: 18px;">{{ number_format($inscripcion->nota_final ?? 0, 2) }}/20</strong>
            </p>

            <p style="margin: 20px 0; font-size: 14px;">
                Expedido el {{ $certificado->fecha_emision->format('d \\d\\e F \\d\\e Y') }}
            </p>
        </div>

        <div class="certificate-footer">
            <div>
                <div class="signature-line"></div>
                <p style="margin: 5px 0;">Firma Autorizada</p>
            </div>
            <div>
                <div class="signature-line"></div>
                <p style="margin: 5px 0;">Sello Institucional</p>
            </div>
        </div>

        <div class="code-info">
            <p style="margin: 5px 0;"><strong>Código de Certificado:</strong> {{ $certificado->codigo_certificado }}</p>
            <p style="margin: 5px 0;"><strong>Código QR:</strong> {{ $certificado->codigo_qr }}</p>
        </div>
    </div>
</body>
</html>