<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pagos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .statistics {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 2px solid #e5e7eb;
            background-color: #f9fafb;
        }
        .stat-card h4 {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .stat-card .number {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }
        .stat-card.green .number {
            color: #059669;
        }
        .stat-card.yellow .number {
            color: #d97706;
        }
        .stat-card.red .number {
            color: #dc2626;
        }
        .stat-card.blue .number {
            color: #2563eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        th {
            background-color: #1e40af;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-confirmado {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-rechazado {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>REPORTE DE PAGOS</h1>
        <p>Universidad Nacional Daniel Alcides Carrión</p>
        <p>Escuela de Ingeniería de Sistemas y Computación</p>
        <p style="margin-top: 10px;">Generado el {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Estadísticas -->
    <div class="statistics">
        <div class="stat-card blue">
            <h4>INGRESOS CONFIRMADOS</h4>
            <div class="number">S/ {{ number_format($estadisticas['total_ingresos'], 2) }}</div>
        </div>
        <div class="stat-card green">
            <h4>CONFIRMADOS</h4>
            <div class="number">{{ $estadisticas['cantidad_confirmados'] }}</div>
        </div>
        <div class="stat-card yellow">
            <h4>PENDIENTES</h4>
            <div class="number">{{ $estadisticas['cantidad_pendientes'] }}</div>
        </div>
        <div class="stat-card red">
            <h4>RECHAZADOS</h4>
            <div class="number">{{ $estadisticas['cantidad_rechazados'] }}</div>
        </div>
    </div>

    <!-- Tabla de Pagos -->
    <table>
        <thead>
            <tr>
                <th>CÓDIGO</th>
                <th>ESTUDIANTE</th>
                <th>CURSO</th>
                <th>MÉTODO</th>
                <th class="text-center">FECHA</th>
                <th class="text-right">MONTO</th>
                <th class="text-center">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagos as $pago)
            <tr>
                <td class="font-bold">{{ $pago->codigo_transaccion }}</td>
                <td>{{ $pago->inscripcion->estudiante->nombres }} {{ $pago->inscripcion->estudiante->apellidos }}</td>
                <td>{{ $pago->inscripcion->curso->nombre }}</td>
                <td>{{ $pago->metodoPago->nombre ?? 'N/A' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                <td class="text-right font-bold">S/ {{ number_format($pago->monto, 2) }}</td>
                <td class="text-center">
                    @if($pago->estado == 'confirmado')
                        <span class="badge badge-confirmado">Confirmado</span>
                    @elseif($pago->estado == 'pendiente')
                        <span class="badge badge-pendiente">Pendiente</span>
                    @else
                        <span class="badge badge-rechazado">Rechazado</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #6b7280;">
                    No hay pagos registrados con los filtros seleccionados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Resumen Final -->
    <div style="margin-top: 30px; padding: 15px; background-color: #f3f4f6; border-radius: 8px;">
        <table style="width: 100%; margin: 0;">
            <tr>
                <td style="border: none; padding: 5px;"><strong>Total de registros:</strong></td>
                <td style="border: none; padding: 5px; text-align: right;">{{ $pagos->count() }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px;"><strong>Monto total confirmado:</strong></td>
                <td style="border: none; padding: 5px; text-align: right; font-size: 14px; color: #059669;">
                    <strong>S/ {{ number_format($estadisticas['total_ingresos'], 2) }}</strong>
                </td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px;"><strong>Monto pendiente:</strong></td>
                <td style="border: none; padding: 5px; text-align: right; color: #d97706;">
                    S/ {{ number_format($estadisticas['pendientes'], 2) }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} Universidad Nacional Daniel Alcides Carrión - Sistema SKY</p>
        <p>Este documento fue generado automáticamente por el sistema</p>
    </div>
</body>
</html>