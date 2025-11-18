<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RegistroMediciones extends Model
{
    protected $table = 'registro_mediciones';
    protected $primaryKey = 'id_regis_med';
    public $timestamps = false;

    protected $casts = [
        'ph_value'    => 'float',   // pH
        'ce_value'    => 'float',   // ORP
        'tagua_value' => 'float',   // Temp agua
        'us_value'    => 'float',   // Ultrasónico
        'tam_value'   => 'float',   // Temp ambiente
        'hum_value'   => 'float',   // Humedad
        'fecha'       => 'datetime'
    ];

    // Mapeo "sensor -> columna"
    public static function sensorColumn(string $sensor): ?string
    {
        return [
            'humedad'         => 'hum_value',
            'temp_ambiente'   => 'tam_value',
            'ph'              => 'ph_value',
            'orp'             => 'ce_value',
            'temp_agua'       => 'tagua_value',
            'ultrasonico'     => 'us_value',
        ][$sensor] ?? null;
    }

    public function hortaliza()
    {
        return $this->belongsTo(SeleccionHortalizas::class, 'id_hortaliza', 'id_hortaliza');
    }

    public function scopeBetween($q, Carbon $from, Carbon $to)
    {
        return $q->whereBetween('fecha', [$from, $to]);
    }

    // --- Promedios por agrupación temporal --- //

    // Última semana (7 días): promedio por día (eje X = días)
    public static function avgByDayLast7(string $column, ?int $idHortaliza = null)
    {
        $to   = now();
        $from = $to->copy()->subDays(6)->startOfDay();

        $query = static::query()
            ->between($from, $to);

        if (!is_null($idHortaliza)) {
            $query->where('id_hortaliza', $idHortaliza);
        }

        return $query
            ->selectRaw("DATE(fecha) as x, AVG($column) as y")
            ->groupBy('x')
            ->orderBy('x')
            ->get();
    }

    // Último mes: 4 semanas (agrupación por semana calendario relativa al rango)
    public static function avgByWeeksLast4(string $column, ?int $idHortaliza = null)
    {
        $to   = now()->endOfDay();
        $from = now()->copy()->subWeeks(3)->startOfDay();

        $query = static::query()
            ->between($from, $to);

        if (!is_null($idHortaliza)) {
            $query->where('id_hortaliza', $idHortaliza);
        }

        // agrupamos por año+semana ISO para orden estable
        return $query
            ->selectRaw("YEARWEEK(fecha, 3) as grp, MIN(DATE(fecha)) as x_start, MAX(DATE(fecha)) as x_end, AVG($column) as y")
            ->groupBy('grp')
            ->orderBy('x_start')
            ->get();
    }

    // Último semestre: 6 meses
    public static function avgByMonthsLast6(string $column, ?int $idHortaliza = null)
    {
        $to   = now()->endOfMonth();
        $from = now()->copy()->subMonths(5)->startOfMonth();

        $query = static::query()
            ->between($from, $to);

        if (!is_null($idHortaliza)) {
            $query->where('id_hortaliza', $idHortaliza);
        }

        return $query
            ->selectRaw("DATE_FORMAT(fecha, '%Y-%m') as x, AVG($column) as y")
            ->groupBy('x')
            ->orderBy('x')
            ->get();
    }

    // Último año: 6 bimestres (promedio cada 2 meses)
    public static function avgByBiMonthsLast6(string $column, ?int $idHortaliza = null)
    {
        $to   = now()->endOfMonth();
        $from = now()->copy()->subMonths(11)->startOfMonth();

        $query = static::query()
            ->between($from, $to);

        if (!is_null($idHortaliza)) {
            $query->where('id_hortaliza', $idHortaliza);
        }

        // Agrupar por año y paridad de mes (bimestres)
        return $query
            ->selectRaw("
                CONCAT(YEAR(fecha), '-', LPAD( (FLOOR((MONTH(fecha)-1)/2)*2)+1 ,2,'0')) as x_start_key,
                CONCAT(YEAR(fecha), '-', LPAD( (FLOOR((MONTH(fecha)-1)/2)*2)+2 ,2,'0')) as x_end_key,
                AVG($column) as y,
                MIN(DATE_FORMAT(fecha, '%Y-%m')) as minm,
                MAX(DATE_FORMAT(fecha, '%Y-%m')) as maxm
            ")
            ->groupBy('x_start_key','x_end_key')
            ->orderBy('minm')
            ->get();
    }

    // “Todos” (solo válido cuando sensor = todos): promedio de cada sensor
    public static function avgAllSensors(?int $idHortaliza = null)
    {
        $query = static::query();

        if (!is_null($idHortaliza)) {
            $query->where('id_hortaliza', $idHortaliza);
        }

        return $query
            ->selectRaw("
                AVG(hum_value)   as humedad,
                AVG(tam_value)   as temp_ambiente,
                AVG(ph_value)    as ph,
                AVG(ce_value)    as orp,
                AVG(tagua_value) as temp_agua,
                AVG(us_value)    as ultrasonico
            ")
            ->first();
    }
}