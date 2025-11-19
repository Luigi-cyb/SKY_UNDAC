<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inscripciones</title>
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
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #1e40af;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header h2 {
            color: #64748b;
            font-size: 16px;
            font-weight: normal;
        }
        
        .info-section {
            background-color: #f8fafc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid #2563eb;
        }
        
        .info-section h3 {
            color: #1e40af;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .info-item {
            margin-bottom: 5px;
        }
        
        .info-item strong {
            color: #475569;
        }
        
        .statistics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .stat-card.yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .stat-card.red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .stat-card h4 {
            font-size: 10px;
            margin-bottom: 8px;
            opacity: 0.9;
        }
        
        .stat-card .number {
            font-size: 24px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background-color: #1e40af;
            color: white;
        }
        
        table th {
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        
        table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        table tbody tr:hover {
            background-color: #e0e7ff;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 2px solid #e2e8f0;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>UNIVERSIDAD NACIONAL DANIEL ALCIDES CARRIÓN</h1>
        <h2>Escuela de Ingeniería de Sistemas y Computación</h2>
        <h2>Reporte de Inscripciones - Cursos Extracurriculares</h2>
    </div>

    <!-- Información del Reporte -->
    <div class="info-section">
        <h3>Información del Reporte</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Fecha de Generación:</strong> {{ now()->format('d/m/Y H:i:s') }}
            </div>
            <div class="info-item">
                <strong>Generado por:</strong> {{ Auth::user()->name }}
            </div>
            @if(isset($filtros['curso']))
            <div class="info-item">
                <strong>Curso:</strong> {{ $filtros['curso'] }}
            </div>
            @endif
            @if(isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin']))
            <div class="info-item">
                <strong>Período:</strong> {{ $filtros['fecha_inicio'] }} - {{ $filtros['fecha_fin'] }}
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="statistics">
        <div class="stat-card">
            <h4>TOTAL INSCRIPCIONES</h4>
            <div class="number">{{ $totalInscripciones }}</div>
        </div>
        <div class="stat-card green">
            <h4>CONFIRMADAS</h4>
            <div class="number">{{ $inscripcionesConfirmadas }}</div>
        </div>
        <div class="stat-card yellow">
            <h4>PENDIENTES</h4>
            <div class="number">{{ $inscripcionesPendientes }}</div>
        </div>
        <div class="stat-card red">
            <h4>CANCELADAS</h4>
            <div class="number">{{ $inscripcionesCanceladas }}</div>
        </div>
    </div>

    <!-- Tabla de Inscripciones -->
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Estudiante</th>
                <th>DNI</th>
                <th>Curso</th>
                <th>Fecha Inscripción</th>
                <th>Estado</th>
                <th>Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscripciones as $inscripcion)
            <tr>
                <td>{{ $inscripcion->codigo_inscripcion }}</td>
                <td>{{ $inscripcion->estudiante->nombres }} {{ $inscripcion->estudiante->apellidos }}</td>
                <td>{{ $inscripcion->estudiante->dni }}</td>
                <td>{{ $inscripcion->curso->nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</td>
                <td>
                    @if($inscripcion->estado === 'confirmada')
                        <span class="badge badge-success">Confirmada</span>
                    @elseif($inscripcion->estado === 'pendiente')
                        <span class="badge badge-warning">Pendiente</span>
                    @elseif($inscripcion->estado === 'cancelada')
                        <span class="badge badge-danger">Cancelada</span>
                    @else
                        <span class="badge badge-info">{{ ucfirst($inscripcion->estado) }}</span>
                    @endif
                </td>
                <td>
                    @if($inscripcion->pago_confirmado)
                        <span class="badge badge-success">Pagado</span>
                    @else
                        <span class="badge badge-warning">Pendiente</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pie de página -->
    <div class="footer">
        <p><strong>Sistema SKY - Gestión de Cursos Extracurriculares</strong></p>
        <p>Este documento ha sido generado automáticamente por el sistema.</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html>