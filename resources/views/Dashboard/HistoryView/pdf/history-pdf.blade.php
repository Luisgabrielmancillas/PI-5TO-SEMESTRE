<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial - {{ $projectName }}</title>
    <style>
        *{ box-sizing:border-box; font-family: DejaVu Sans, sans-serif; }
        body{
            font-size: 11px;
            color:#1f2933;
        }

        /* ===== HEADER (logo + nombre + QR) ===== */
        .header-table{
            width:100%;
            border-bottom:1px solid #d0d7e2;
            margin-bottom:12px;
            padding-bottom:8px;
            border-collapse:collapse;
        }
        .header-table td{
            vertical-align:top;
        }
        .header-left{
            width:70%;
        }
        .header-right{
            width:30%;
            text-align:right;
        }
        .brand-table{
            border-collapse:collapse;
        }
        .brand-logo-cell{
            width:55px;
        }
        .brand-logo{
            height:48px;
        }
        .brand-text-cell{
            padding-left:6px;
        }
        .project-title{
            font-size:18px;
            font-weight:bold;
            margin:0;
        }
        .project-sub{
            font-size:10px;
            color:#6b7280;
            margin-top:2px;
        }
        .qr-img{
            height:70px;
        }

        .section-title{
            font-size:13px;
            font-weight:bold;
            margin:12px 0 4px;
            padding-bottom:2px;
            border-bottom:1px solid #e5e7eb;
        }

        .filters-table{
            width:100%;
            border-collapse:collapse;
            margin-bottom:10px;
        }
        .filters-table td{
            padding:3px 6px;
            font-size:10px;
        }
        .filters-label{
            width:20%;
            font-weight:bold;
            color:#4b5563;
        }

        /* ===== GRÁFICA BARRAS VERTICALES ===== */
        .bar-chart{
            width:100%;
            height:170px;
            display:table;
            table-layout:fixed;
            margin:8px 0 12px;
        }
        .bar-column{
            display:table-cell;
            vertical-align:bottom;
            text-align:center;
            padding:0 4px;
        }
        .bar-inner{
            height:120px;
            border:1px solid #e5e7eb;
            background:#f9fafb;
            border-radius:6px;
            padding:4px;              /* pequeño margen interno */
            position:relative;        /* para anclar la barra adentro */
            overflow:hidden;
        }
        .bar-rect{
            width:60%;
            position:absolute;
            left:50%;
            transform:translateX(-50%);
            bottom:4px;               /* la barra se “apoya” abajo */
            border-radius:4px 4px 0 0;
        }

        /* ===== TABLA MEDICIONES ===== */
        .data-table{
            width:100%;
            border-collapse:collapse;
            margin-top:4px;
        }
        .data-table th,
        .data-table td{
            border:1px solid #e5e7eb;
            padding:3px 4px;
            font-size:8.5px;
        }
        .data-table th{
            background:#f3f4f6;
            font-weight:600;
        }

        .footer{
            margin-top:10px;
            font-size:8px;
            color:#9ca3af;
            text-align:right;
        }
    </style>
</head>
<body>
    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            {{-- Columna izquierda: logo + nombre --}}
            <td class="header-left">
                <table class="brand-table">
                    <tr>
                        <td class="brand-logo-cell">
                            @if(file_exists(public_path('images/logo.png')))
                                <img src="{{ public_path('images/logo.png') }}" class="brand-logo" alt="Logo">
                            @endif
                        </td>
                        <td class="brand-text-cell">
                            <p class="project-title">{{ $projectName }}</p>
                            <p class="project-sub">
                                Reporte histórico de sensores · {{ $generatedAt->format('d/m/Y H:i') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>

            {{-- Columna derecha: QR --}}
            <td class="header-right">
                @if(file_exists(public_path('images/qr.png')))
                    <img src="{{ public_path('images/qr.png') }}" class="qr-img" alt="QR HydroBox">
                @endif
            </td>
        </tr>
    </table>

    {{-- FILTROS UTILIZADOS --}}
    <h3 class="section-title">Filtros utilizados</h3>
    <table class="filters-table">
        <tr>
            <td class="filters-label">Hortaliza:</td>
            <td>{{ $cropLabel }}</td>
        </tr>
        <tr>
            <td class="filters-label">Sensor (gráficas):</td>
            <td>{{ $sensorLabel }}</td>
        </tr>
        <tr>
            <td class="filters-label">Rango (gráficas):</td>
            <td>{{ $rangeLabel }}</td>
        </tr>
        <tr>
            <td class="filters-label">Rango (tabla):</td>
            <td>{{ $tableRangeLabel }}</td>
        </tr>
    </table>

    {{-- RESUMEN GRÁFICO --}}
    <h3 class="section-title">Resumen gráfico</h3>

    @php
        $labels  = $chartData['labels']  ?? [];
        $series  = $chartData['series']  ?? [];
        $hasData = $chartData['hasData'] ?? false;
    @endphp

    @if(!$hasData || empty($labels) || empty($series))
        <p style="font-size:10px; color:#6b7280;">
            No se encontraron datos para las gráficas con los filtros seleccionados.
        </p>
    @else
        @php
            $maxVal = max($series);
            if ($maxVal <= 0) {
                $maxVal = 1;
            }

            if (!function_exists('hb_sensor_color')) {
                function hb_sensor_color(string $label): string {
                    $key = mb_strtolower(trim($label), 'UTF-8');

                    switch ($key) {
                        case 'humedad':
                            // mismo color que sensorColor() en modo claro
                            return '#0ea5e9';
                        case 'temp. ambiente':
                        case 'temperatura del aire':
                            return '#f59e0b';
                        case 'ph':
                            return '#6366f1';
                        case 'orp':
                            return '#10b981';
                        case 'temp. agua':
                        case 'temperatura del agua':
                            return '#ec4899';
                        case 'ultrasónico':
                        case 'ultrasonico':
                            return '#ef4444';
                        default:
                            // fallback parecido a tu azul por defecto
                            return '#2563eb';
                    }
                }
            }
        @endphp

        <div class="bar-chart">
            @foreach($labels as $i => $label)
                @php
                    $value  = $series[$i] ?? 0;
                    $height = $value <= 0 ? 0 : ($value / $maxVal) * 100;
                    $height = max(5, $height); // altura mínima
                    $color  = hb_sensor_color($label);
                @endphp
                <div class="bar-column">
                    <div class="bar-inner">
                        <div class="bar-rect"
                             style="height: {{ $height }}%; background: {{ $color }};"></div>
                    </div>
                    <div class="bar-value">{{ number_format($value,2) }}</div>
                    <div class="bar-label">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- TABLA DE MEDICIONES --}}
    <h3 class="section-title">Detalle de mediciones</h3>

    @if($tableItems->isEmpty())
        <p style="font-size:10px; color:#6b7280;">No se encontraron registros con los filtros seleccionados.</p>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Humedad</th>
                    <th>Temp. aire</th>
                    <th>Temp. agua</th>
                    <th>pH</th>
                    <th>ORP</th>
                    <th>Ultrasónico</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tableItems as $idx => $row)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $row->fecha->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($row->hum_value, 2) }}</td>
                        <td>{{ number_format($row->tam_value, 2) }}</td>
                        <td>{{ number_format($row->tagua_value, 2) }}</td>
                        <td>{{ number_format($row->ph_value, 2) }}</td>
                        <td>{{ number_format($row->ce_value, 2) }}</td>
                        <td>{{ number_format($row->us_value, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        HydroBox · Historial de sensores · Generado el {{ $generatedAt->format('d/m/Y H:i') }}
    </div>
</body>
</html>