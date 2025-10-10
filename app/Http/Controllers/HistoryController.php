<?php
namespace App\Http\Controllers;

use App\Models\RegistroMediciones;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HistoryController extends Controller
{
    // Página principal
    public function index(Request $request)
    {
        // Defaults para GRÁFICAS (sensor=all, rango=week)
        $sensor = $request->get('sensor', 'all');  // all | humedad | temp_ambiente | ph | orp | temp_agua | ultrasonico
        $range  = $request->get('range',  'week'); // all | week | month | semester | year

        // Defaults para TABLA (independiente)
        $tableRange = $request->get('tableRange', 'week'); // all | week | month | semester | year

        // Datos tabla según filtro (paginado 15)
        $tableQuery = $this->applyRangeFilter(RegistroMediciones::query(), $tableRange);
        $items = $tableQuery->orderByDesc('fecha')->paginate(15)->withQueryString();

        return view('Dashboard.HistoryView.history', [
            'initialSensor' => $sensor,
            'initialRange'  => $range,
            'tableRange'    => $tableRange,
            'items'         => $items,
        ]);
    }

    // Endpoint JSON para GRÁFICAS
    public function data(Request $request)
    {
        $sensor = $request->get('sensor', 'all');
        $range  = $request->get('range', 'week');

        // Caso 1: sensor=all && range=all => barras con promedios por sensor
        if ($sensor === 'all' && $range === 'all') {
            $avg = RegistroMediciones::avgAllSensors();
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
                $payload[] = $this->seriesForSensorAndRange($key, $range, $label);
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

        return response()->json($this->seriesForSensorAndRange($sensor, $range, $label));
    }

    // Endpoint HTML para TBODY de la tabla (filtro independiente)
    public function table(Request $request)
    {
        $tableRange = $request->get('tableRange', 'week');

        $query = $this->applyRangeFilter(RegistroMediciones::query(), $tableRange)
            ->orderByDesc('fecha');

        $items = $query->paginate(15)->withQueryString();

        return view('Dashboard.HistoryView.partials.tables.tbody', compact('items'))->render();
    }

    // Helpers

    private function seriesForSensorAndRange(string $sensor, string $range, string $niceLabel)
    {
        $col = RegistroMediciones::sensorColumn($sensor);
        abort_if(!$col, 400, 'Sensor inválido');

        switch ($range) {
            case 'week':     // últimos 7 días (promedios por día)
                $rows = RegistroMediciones::avgByDayLast7($col);
                $labels = $rows->pluck('x')->map(fn($d) => Carbon::parse($d)->format('d M'));
                $series = $rows->pluck('y')->map(fn($v) => $v === null ? null : round($v, 2));
                return ['type'=>'line','label'=>$niceLabel,'labels'=>$labels,'series'=>$series];

            case 'month':    // 4 semanas
                $rows = RegistroMediciones::avgByWeeksLast4($col);
                $labels = $rows->map(function($r){
                    $a = Carbon::parse($r->x_start)->format('M d');
                    $b = Carbon::parse($r->x_end)->format('M d');
                    return "$a – $b";
                });
                $series = $rows->pluck('y')->map(fn($v) => $v === null ? null : round($v, 2));
                return ['type'=>'line','label'=>$niceLabel,'labels'=>$labels,'series'=>$series];

            case 'semester': // 6 meses
                $rows = RegistroMediciones::avgByMonthsLast6($col);
                $labels = $rows->pluck('x')->map(fn($m) => Carbon::parse($m.'-01')->isoFormat('MMM YYYY'));
                $series = $rows->pluck('y')->map(fn($v) => $v === null ? null : round($v, 2));
                return ['type'=>'line','label'=>$niceLabel,'labels'=>$labels,'series'=>$series];

            case 'year':     // 6 bimestres
                $rows = RegistroMediciones::avgByBiMonthsLast6($col);
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
}

