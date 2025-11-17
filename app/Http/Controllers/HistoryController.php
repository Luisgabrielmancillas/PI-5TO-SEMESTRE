<?php

namespace App\Http\Controllers;

use App\Models\RegistroMediciones;
use App\Models\SeleccionHortalizas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class HistoryController extends Controller
{
    // Página principal
    public function index(Request $request)
    {
        // Hortaliza seleccionada (seleccion = 1)
        $selectedCrop = $this->getSelectedCrop();

        // Defaults para GRÁFICAS (sensor=all, rango=week)
        $sensor = $request->get('sensor', 'all');
        $range  = $request->get('range',  'week');

        // Defaults para TABLA
        $tableRange = $request->get('tableRange', 'week');

        // NUEVO: todas las hortalizas para el modal
        $allCrops = SeleccionHortalizas::orderBy('nombre')->get();

        // Base query: solo registros de la hortaliza seleccionada
        $baseQuery = RegistroMediciones::query();
        if ($selectedCrop) {
            $baseQuery->where('id_hortaliza', $selectedCrop->id_hortaliza);
        } else {
            $baseQuery->whereRaw('1 = 0');
        }

        $tableQuery = $this->applyRangeFilter($baseQuery, $tableRange);
        $items = $tableQuery->orderByDesc('fecha')->paginate(15)->withQueryString();

        return view('Dashboard.HistoryView.history', [
            'initialSensor' => $sensor,
            'initialRange'  => $range,
            'tableRange'    => $tableRange,
            'items'         => $items,
            'selectedCrop'  => $selectedCrop,
            'allCrops'      => $allCrops,   // <--- importante
        ]);
    }

    public function exportPdf(Request $request)
    {
        // Validación de filtros
        $request->validate([
            'crop'       => 'required', // validaremos a mano abajo
            'sensor'     => 'required|in:all,humedad,temp_ambiente,temp_agua,ph,orp,ultrasonico',
            'range'      => 'required|in:all,week,month,semester,year',
            'tableRange' => 'required|in:all,week,month,semester,year',
        ]);

        // Regla: si range = all, el sensor DEBE ser all
        if ($request->sensor !== 'all' && $request->range === 'all') {
            return back()->withErrors([
                'range' => 'El rango "Todos" solo se puede usar cuando el sensor es "Todos los sensores".'
            ])->withInput();
        }

        // --- Filtro hortaliza ---
        $cropValue = $request->crop; // 'all' o id_hortaliza
        $idHortaliza = $cropValue === 'all' ? null : (int) $cropValue;

        // Para mostrar nombre bonito en el PDF
        $cropLabel = $this->cropLabelFromValue($cropValue);

        // --- Filtros gráficos y tabla ---
        $sensor     = $request->sensor;
        $range      = $request->range;
        $tableRange = $request->tableRange;

        $sensorLabel     = $this->sensorLabel($sensor);
        $rangeLabel      = $this->rangeLabel($range);
        $tableRangeLabel = $this->rangeLabel($tableRange);

        // --- Datos para GRÁFICAS (reusamos tu lógica) ---
        $chartData = $this->buildChartDataForPdf($sensor, $range, $idHortaliza);

        // --- Datos para TABLA (sin paginación, todo) ---
        $tableQuery = RegistroMediciones::query();
        if (!is_null($idHortaliza)) {
            $tableQuery->where('id_hortaliza', $idHortaliza);
        }
        $tableItems = $this->applyRangeFilter($tableQuery, $tableRange)
            ->orderByDesc('fecha')
            ->get();

        // --- Armar PDF ---
        $pdf = Pdf::loadView('Dashboard.HistoryView.pdf.history-pdf', [
            'projectName'       => 'HydroBox',
            'cropLabel'         => $cropLabel,
            'sensorLabel'       => $sensorLabel,
            'rangeLabel'        => $rangeLabel,
            'tableRangeLabel'   => $tableRangeLabel,
            'chartData'         => $chartData,
            'tableItems'        => $tableItems,
            'generatedAt'       => now(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'HydroBox_Historial_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }

    // ===== Helpers para etiquetas legibles =====

    private function cropLabelFromValue(string $value): string
    {
        if ($value === 'all') {
            return 'Todas las hortalizas';
        }

        $crop = SeleccionHortalizas::where('id_hortaliza', (int) $value)->first();
        return $crop ? $crop->nombre : 'Desconocida';
    }

    private function sensorLabel(string $sensor): string
    {
        return [
            'all'           => 'Todos los sensores',
            'humedad'       => 'Humedad',
            'temp_ambiente' => 'Temperatura del aire',
            'temp_agua'     => 'Temperatura del agua',
            'ph'            => 'pH',
            'orp'           => 'ORP',
            'ultrasonico'   => 'Ultrasónico',
        ][$sensor] ?? 'Sensor';
    }

    private function rangeLabel(string $range): string
    {
        return [
            'all'      => 'Todos',
            'week'     => 'Última semana',
            'month'    => 'Último mes',
            'semester' => 'Último semestre',
            'year'     => 'Último año',
        ][$range] ?? 'Todos';
    }

    private function buildChartDataForPdf(string $sensor, string $range, ?int $idHortaliza = null): array
    {
        // Caso 1: sensor=all y range=all => barras con promedios
        if ($sensor === 'all' && $range === 'all') {
            $avg = RegistroMediciones::avgAllSensors($idHortaliza);

            if (!$avg) {
                return [
                    'mode'   => 'bar',
                    'labels' => ['Humedad','Temp. ambiente','pH','ORP','Temp. agua','Ultrasónico'],
                    'series' => [0,0,0,0,0,0],
                ];
            }

            return [
                'mode'   => 'bar',
                'labels' => ['Humedad','Temp. ambiente','pH','ORP','Temp. agua','Ultrasónico'],
                'series' => [
                    (float) $avg->humedad,
                    (float) $avg->temp_ambiente,
                    (float) $avg->ph,
                    (float) $avg->orp,
                    (float) $avg->temp_agua,
                    (float) $avg->ultrasonico,
                ],
            ];
        }

        // Caso 2: sensor=all y range≠all => varias series (una por sensor)
        if ($sensor === 'all' && $range !== 'all') {
            $sensors = [
                'humedad'       => 'Humedad',
                'temp_ambiente' => 'Temp. ambiente',
                'ph'            => 'pH',
                'orp'           => 'ORP',
                'temp_agua'     => 'Temp. agua',
                'ultrasonico'   => 'Ultrasónico',
            ];

            $charts = [];

            foreach ($sensors as $key => $label) {
                $seriesInfo = $this->seriesForSensorAndRange($key, $range, $label, $idHortaliza);

                // === NORMALIZAR labels / series A ARRAY ===
                $labels = $seriesInfo['labels'];
                $series = $seriesInfo['series'];

                if ($labels instanceof \Illuminate\Support\Collection) {
                    $labels = $labels->values()->all();
                } elseif (is_array($labels)) {
                    $labels = array_values($labels);
                } else {
                    $labels = [];
                }

                if ($series instanceof \Illuminate\Support\Collection) {
                    $series = $series->values()->all();
                } elseif (is_array($series)) {
                    $series = array_values($series);
                } else {
                    $series = [];
                }

                $seriesInfo['labels'] = $labels;
                $seriesInfo['series'] = $series;
                // ==========================================

                $charts[] = $seriesInfo;
            }

            return [
                'mode'   => 'multi-line',
                'charts' => $charts,
            ];
        }

        // Caso 3: un solo sensor
        $labelMap = [
            'humedad'       => 'Humedad',
            'temp_ambiente' => 'Temp. ambiente',
            'ph'            => 'pH',
            'orp'           => 'ORP',
            'temp_agua'     => 'Temp. agua',
            'ultrasonico'   => 'Ultrasónico',
        ];
        $niceLabel = $labelMap[$sensor] ?? 'Sensor';

        $seriesInfo = $this->seriesForSensorAndRange($sensor, $range, $niceLabel, $idHortaliza);

        // === NORMALIZAR labels / series A ARRAY ===
        $labels = $seriesInfo['labels'];
        $series = $seriesInfo['series'];

        if ($labels instanceof \Illuminate\Support\Collection) {
            $labels = $labels->values()->all();
        } elseif (is_array($labels)) {
            $labels = array_values($labels);
        } else {
            $labels = [];
        }

        if ($series instanceof \Illuminate\Support\Collection) {
            $series = $series->values()->all();
        } elseif (is_array($series)) {
            $series = array_values($series);
        } else {
            $series = [];
        }

        $seriesInfo['labels'] = $labels;
        $seriesInfo['series'] = $series;
        // ==========================================

        return [
            'mode'  => 'single-line',
            'chart' => $seriesInfo,
        ];
    }


    // Endpoint JSON para GRÁFICAS
    public function data(Request $request)
    {
        $sensor = $request->get('sensor', 'all');
        $range  = $request->get('range', 'week');

        // Hortaliza seleccionada
        $selectedCrop = $this->getSelectedCrop();
        $idHortaliza  = $selectedCrop?->id_hortaliza;

        // Caso 1: sensor=all && range=all => barras con promedios por sensor
        if ($sensor === 'all' && $range === 'all') {
            $avg = RegistroMediciones::avgAllSensors($idHortaliza);

            if (!$avg) {
                // Sin datos para esa hortaliza: devolvemos barras vacías (0)
                return response()->json([
                    'type'   => 'bar',
                    'labels' => ['Humedad','Temp. ambiente','pH','ORP','Temp. agua','Ultrasónico'],
                    'series' => [0, 0, 0, 0, 0, 0],
                ]);
            }

            return response()->json([
                'type'   => 'bar',
                'labels' => ['Humedad','Temp. ambiente','pH','ORP','Temp. agua','Ultrasónico'],
                'series' => [
                    (float)$avg->humedad,
                    (float)$avg->temp_ambiente,
                    (float)$avg->ph,
                    (float)$avg->orp,
                    (float)$avg->temp_agua,
                    (float)$avg->ultrasonico,
                ],
            ]);
        }

        // Caso 2: sensor=all && range≠all => 6 gráficas de líneas (una por sensor)
        if ($sensor === 'all' && $range !== 'all') {
            $sensors = [
                'humedad'       => 'Humedad',
                'temp_ambiente' => 'Temp. ambiente',
                'ph'            => 'pH',
                'orp'           => 'ORP',
                'temp_agua'     => 'Temp. agua',
                'ultrasonico'   => 'Ultrasónico',
            ];

            $payload = [];
            foreach ($sensors as $key => $label) {
                $payload[] = $this->seriesForSensorAndRange($key, $range, $label, $idHortaliza);
            }
            return response()->json([
                'type'    => 'multi-line', // cliente renderea 6 charts verticales
                'payload' => $payload
            ]);
        }

        // Caso 3: sensor≠all => una sola gráfica de líneas
        $label = [
            'humedad'       => 'Humedad',
            'temp_ambiente' => 'Temp. ambiente',
            'ph'            => 'pH',
            'orp'           => 'ORP',
            'temp_agua'     => 'Temp. agua',
            'ultrasonico'   => 'Ultrasónico',
        ][$sensor] ?? 'Sensor';

        return response()->json(
            $this->seriesForSensorAndRange($sensor, $range, $label, $idHortaliza)
        );
    }

    public function table(Request $request)
    {
        $tableRange = $request->get('tableRange', 'week');

        // Hortaliza seleccionada
        $selectedCrop = $this->getSelectedCrop();

        $baseQuery = RegistroMediciones::query();
        if ($selectedCrop) {
            $baseQuery->where('id_hortaliza', $selectedCrop->id_hortaliza);
        } else {
            $baseQuery->whereRaw('1 = 0');
        }

        $query = $this->applyRangeFilter($baseQuery, $tableRange)
            ->orderByDesc('fecha');

        $items = $query->paginate(15)->withQueryString();

        return response()->json([
            'tbody'      => view('Dashboard.HistoryView.partials.tables.tbody', compact('items'))->render(),
            'pagination' => view('Dashboard.HistoryView.partials.tables.pagination', compact('items'))->render(),
        ]);
    }


    // Helpers

    /**
     * Series para un sensor y rango concretos, filtradas por hortaliza.
     */
    private function seriesForSensorAndRange(string $sensor, string $range, string $niceLabel, ?int $idHortaliza = null)
    {
        $col = RegistroMediciones::sensorColumn($sensor);
        abort_if(!$col, 400, 'Sensor inválido');

        switch ($range) {
            case 'week':     // últimos 7 días (promedios por día)
                $rows = RegistroMediciones::avgByDayLast7($col, $idHortaliza);
                $labels = $rows->pluck('x')->map(fn($d) => Carbon::parse($d)->format('d M'));
                $series = $rows->pluck('y')->map(fn($v) => $v === null ? null : round($v, 2));
                return ['type'=>'line','label'=>$niceLabel,'labels'=>$labels,'series'=>$series];

            case 'month':    // 4 semanas
                $rows = RegistroMediciones::avgByWeeksLast4($col, $idHortaliza);
                $labels = $rows->map(function($r){
                    $a = Carbon::parse($r->x_start)->format('M d');
                    $b = Carbon::parse($r->x_end)->format('M d');
                    return "$a – $b";
                });
                $series = $rows->pluck('y')->map(fn($v) => $v === null ? null : round($v, 2));
                return ['type'=>'line','label'=>$niceLabel,'labels'=>$labels,'series'=>$series];

            case 'semester': // 6 meses
                $rows = RegistroMediciones::avgByMonthsLast6($col, $idHortaliza);
                $labels = $rows->pluck('x')->map(fn($m) => Carbon::parse($m.'-01')->isoFormat('MMM YYYY'));
                $series = $rows->pluck('y')->map(fn($v) => $v === null ? null : round($v, 2));
                return ['type'=>'line','label'=>$niceLabel,'labels'=>$labels,'series'=>$series];

            case 'year':     // 6 bimestres
                $rows = RegistroMediciones::avgByBiMonthsLast6($col, $idHortaliza);
                $labels = $rows->map(function($r){
                    $a = Carbon::parse($r->x_start_key.'-01')->isoFormat('MMM');
                    $b = Carbon::parse($r->x_end_key.'-01')->isoFormat('MMM');
                    return "$a – $b";
                });
                $series = $rows->pluck('y')->map(fn($v) => $v === null ? null : round($v, 2));
                return ['type'=>'line','label'=>$niceLabel,'labels'=>$labels,'series'=>$series];

            case 'all':
                // si sensor != all, “all” no debe usarse; devolvemos vacío por seguridad
                return ['type'=>'line','label'=>$niceLabel,'labels'=>[],'series'=>[]];

            default:
                abort(400,'Rango inválido');
        }
    }

    private function applyRangeFilter($query, string $range)
    {
        switch ($range) {
            case 'week':
                return $query->between(now()->copy()->subDays(6)->startOfDay(), now());
            case 'month':
                return $query->between(now()->copy()->subWeeks(3)->startOfDay(), now());
            case 'semester':
                return $query->between(now()->copy()->subMonths(5)->startOfMonth(), now());
            case 'year':
                return $query->between(now()->copy()->subMonths(11)->startOfMonth(), now());
            case 'all':
            default:
                return $query;
        }
    }

    /**
     * Devuelve la hortaliza que está marcada como seleccionada.
     */
    private function getSelectedCrop(): ?SeleccionHortalizas
    {
        return SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();
    }
}