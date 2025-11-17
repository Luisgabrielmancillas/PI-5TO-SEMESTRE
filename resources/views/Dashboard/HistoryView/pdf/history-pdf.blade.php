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
        .header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            border-bottom:1px solid #d0d7e2;
            padding-bottom:8px;
            margin-bottom:12px;
        }
        .brand{
            display:flex;
            align-items:center;
        }
        .brand-logo{
            height:48px;
            margin-right:8px;
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
        .qr img{
            height:64px;
        }
        .pill{
            display:inline-block;
            padding:2px 8px;
            border-radius:9999px;
            background:#e0f2fe;
            color:#0369a1;
            font-size:9px;
            font-weight:600;
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
        .chart-table{
            width:100%;
            border-collapse:collapse;
            margin-top:4px;
            margin-bottom:8px;
        }
        .chart-table th,
        .chart-table td{
            padding:4px 5px;
            border:1px solid #e5e7eb;
            font-size:9px;
        }
        .chart-table th{
            background:#f9fafb;
            font-weight:600;
        }
        .bar-row{
            display:flex;
            align-items:center;
            margin-bottom:4px;
        }
        .bar-label{
            width:110px;
            font-size:9px;
        }
        .bar-track{
            flex:1;
            height:8px;
            background:#e5e7eb;
            border-radius:9999px;
            overflow:hidden;
            margin:0 6px;
        }
        .bar-fill{
            height:8px;
            border-radius:9999px;
            background:#4f46e5;
        }
        .bar-value{
            width:40px;
            font-size:9px;
            text-align:right;
        }

        /* Tabla de mediciones */
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
    <div class="header">
        <div class="brand">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ public_path('images/logo.png') }}" class="brand-logo" alt="Logo">
            @endif
            <div>
                <p class="project-title">{{ $projectName }}</p>
                <p class="project-sub">Reporte histórico de sensores · {{ $generatedAt->format('d/m/Y H:i') }}</p>
                <span class="pill">Documento generado automáticamente</span>
            </div>
        </div>
        <div class="qr">
            @if(file_exists(public_path('images/qr.png')))
                <img src="{{ public_path('images/qr.png') }}" alt="QR HydroBox">
            @endif
        </div>
    </div>

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

    {{-- GRÁFICAS (representación estática) --}}
    <h3 class="section-title">Resumen gráfico</h3>

    @if($chartData['mode'] === 'bar')
        {{-- Barras horizontales con promedios --}}
        @php
            $max = max($chartData['series']) ?: 1;
        @endphp
        @foreach($chartData['labels'] as $i => $label)
            @php
                $value = $chartData['series'][$i];
                $percent = $value <= 0 ? 0 : ($value / $max) * 100;
            @endphp
            <div class="bar-row">
                <div class="bar-label">{{ $label }}</div>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ $percent }}%;"></div>
                </div>
                <div class="bar-value">{{ number_format($value, 2) }}</div>
            </div>
        @endforeach

    @elseif($chartData['mode'] === 'single-line')
        {{-- Tabla simple con la serie temporal --}}
        @php $c = $chartData['chart']; @endphp
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Fecha / Intervalo</th>
                    <th>{{ $c['label'] }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($c['labels'] as $idx => $lbl)
                    <tr>
                        <td>{{ $lbl }}</td>
                        <td>
                            @php $val = $c['series'][$idx]; @endphp
                            {{ $val !== null ? number_format($val,2) : '—' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($chartData['mode'] === 'multi-line')
        {{-- Varias tablas, una por sensor --}}
        @foreach($chartData['charts'] as $c)
            <p style="font-size:10px; font-weight:bold; margin-top:6px; margin-bottom:2px;">
                {{ $c['label'] }}
            </p>
            <table class="chart-table">
                <thead>
                    <tr>
                        <th>Fecha / Intervalo</th>
                        <th>Valor promedio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($c['labels'] as $idx => $lbl)
                        <tr>
                            <td>{{ $lbl }}</td>
                            <td>
                                @php $val = $c['series'][$idx]; @endphp
                                {{ $val !== null ? number_format($val,2) : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
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