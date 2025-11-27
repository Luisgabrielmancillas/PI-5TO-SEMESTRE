@php
    use App\Models\SeleccionHortalizas;
    use App\Models\ConfigSensores;

    // Hortaliza seleccionada (reusamos si ya viene del controlador)
    $scadaSelectedCrop = $selectedCrop
        ?? SeleccionHortalizas::where('seleccion', 1)->orderByDesc('fecha')->first();

    // Mapa id_sensor -> key (igual que en Comparación)
    $sensorMap = [
        2 => 'temp_ambiente',
        4 => 'humedad',
        1 => 'ph',
        3 => 'temp_agua',
        5 => 'orp',
        6 => 'ultrasonico',
    ];

    // Rangos para la hortaliza seleccionada
    $rangesScada = [];
    if ($scadaSelectedCrop) {
        $configs = ConfigSensores::where('id_hortaliza', $scadaSelectedCrop->id_hortaliza)->get();
        foreach ($configs as $cfg) {
            if (isset($sensorMap[$cfg->id_sensor])) {
                $key = $sensorMap[$cfg->id_sensor];
                $rangesScada[$key] = [
                    'min' => (float) $cfg->valor_min_acept,
                    'max' => (float) $cfg->valor_max_acept,
                ];
            }
        }
    }

    // Misma lógica que en Comparación: bad / warn / ok
    $judgeScada = function ($val, $min, $max) {
        if ($val === null) return 'na';
        if ($min === null || $max === null) return 'na';

        if ($val < $min || $val > $max) {
            return 'bad';   // rojo
        }

        $span = max($max - $min, 0.00001);
        $tol  = 0.10 * $span; // 10% del rango

        if ($val <= $min + $tol || $val >= $max - $tol) {
            return 'warn';  // naranja
        }

        return 'ok';        // verde
    };

    // Devuelve el estado de un sensor concreto (temp_ambiente, humedad, ph, etc.)
    $sensorState = function (string $key) use ($latest, $rangesScada, $judgeScada) {
        if (empty($latest)) {
            return 'na';
        }

        $mapValues = [
            'temp_ambiente' => $latest->tam_value ?? null,
            'humedad'       => $latest->hum_value ?? null,
            'ph'            => $latest->ph_value ?? null,
            'temp_agua'     => $latest->tagua_value ?? null,
            'orp'           => $latest->ce_value ?? null,
            'ultrasonico'   => $latest->us_value ?? null,
        ];

        $val   = $mapValues[$key] ?? null;
        $range = $rangesScada[$key] ?? null;

        if (!$range) {
            return 'na';
        }

        return $judgeScada(
            $val !== null ? (float) $val : null,
            $range['min'],
            $range['max'],
        );
    };

    // Clases Tailwind según el estado
    $pillClass = function (string $state) {
        return match ($state) {
            'bad'  => 'bg-rose-100 border-rose-400 text-rose-800',      // rojo
            'warn' => 'bg-amber-100 border-amber-400 text-amber-800',   // naranja
            'ok'   => 'bg-emerald-100 border-emerald-500 text-emerald-900', // verde
            default=> 'bg-gray-50 border-gray-300 text-slate-900',      // neutro
        };
    };
@endphp

{{-- SENSOR TEMP AMBIENTE --}}
<div id="tempSensor" class="absolute top-[78px] left-[47%] z-20 flex items-center">
    <span class="text-sm px-3 py-1 rounded-full border-2 shadow-md whitespace-nowrap
                 {{ $pillClass($sensorState('temp_ambiente')) }}">
        @if(!empty($latest))
            T. Amb: {{ number_format((float)$latest->tam_value, 1, '.', '') }}°C
        @else
            T. Amb: —°C
        @endif
    </span>
    <img src="{{ asset('images/temperatura.png') }}" alt="Sensor de Temperatura" class="ml-2 h-24 w-auto">
</div>

{{-- SENSOR HUMEDAD AMBIENTE --}}
<div id="humSensor" class="absolute top-[78px] left-[70%] z-20 flex items-center">
    <img src="{{ asset('images/humedad.png') }}" alt="Sensor de Humedad" class="h-24 w-auto">
    <span class="text-sm px-3 py-1 rounded-full border-2 shadow-md whitespace-nowrap
                 {{ $pillClass($sensorState('humedad')) }}">
        @if(!empty($latest))
            Humedad: {{ number_format((float)$latest->hum_value, 1, '.', '') }}%
        @else
            Humedad: —%
        @endif
    </span>
</div>

{{-- T. AGUA, ORP, pH --}}
<div class="absolute top-[79%] left-[32%] z-20 flex flex-col space-y-2 items-start">
    <span class="text-sm px-3 py-1 rounded-full border-2 shadow-md whitespace-nowrap
                 {{ $pillClass($sensorState('temp_agua')) }}">
        @if(!empty($latest))
            T. Agua: {{ number_format((float)$latest->tagua_value, 1, '.', '') }}°C
        @else
            T. Agua: —°C
        @endif
    </span>

    <span class="text-sm px-3 py-1 rounded-full border-2 shadow-md whitespace-nowrap
                 {{ $pillClass($sensorState('orp')) }}">
        @if(!empty($latest))
            ORP: {{ number_format((float)$latest->ce_value, 0, '.', '') }} mV
        @else
            ORP: — mV
        @endif
    </span>

    <span class="text-sm px-3 py-1 rounded-full border-2 shadow-md whitespace-nowrap
                 {{ $pillClass($sensorState('ph')) }}">
        @if(!empty($latest))
            pH: {{ number_format((float)$latest->ph_value, 2, '.', '') }}
        @else
            pH: —
        @endif
    </span>
</div>

{{-- NIVEL DE AGUA --}}
<div class="absolute top-[75%] left-[58%] z-20">
    <span class="text-sm px-3 py-1 rounded-full border-2 shadow-md whitespace-nowrap
                 {{ $pillClass($sensorState('ultrasonico')) }}">
        @if(!empty($latest))
            N. Agua: {{ number_format((float)$latest->us_value, 0, '.', '') }}cm
        @else
            N. Agua: —cm
        @endif
    </span>
</div>