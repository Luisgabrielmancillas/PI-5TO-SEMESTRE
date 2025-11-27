@php
    // Helpers
    $fmt = function($v) {
        $s = number_format((float)$v, 2, '.', '');
        return rtrim(rtrim($s, '0'), '.');
    };

    $judge = function($val, $min, $max) {
        if ($val === null) return ['state' => 'na', 'msg' => 'Sin datos'];
        if ($min === null || $max === null) return ['state' => 'na', 'msg' => 'Rango no definido'];

        // 🔴 Fuera de rango
        if ($val < $min || $val > $max) {
            return ['state' => 'bad', 'msg' => 'Valor fuera del rango aceptado'];
        }

        // 🟠 Cerca del límite (10% del rango)
        $span = max($max - $min, 0.00001);
        $tol  = 0.10 * $span;

        if ($val <= $min + $tol) {
            return ['state' => 'warn', 'msg' => 'Cerca del mínimo aceptado'];
        } elseif ($val >= $max - $tol) {
            return ['state' => 'warn', 'msg' => 'Cerca del máximo aceptado'];
        }

        // 🟢 Dentro de rango cómodo
        return ['state' => 'ok', 'msg' => 'Dentro de rango'];
    };

    // 🔴🟠🟢 Colores de la tarjeta
    $cardClass = function($state) {
        return match ($state) {
            'bad'  => 'ring-1 ring-rose-200 bg-rose-50/70 dark:bg-rose-900/10 dark:ring-rose-800',
            'warn' => 'ring-1 ring-amber-200 bg-amber-50/70 dark:bg-amber-900/10 dark:ring-amber-800',
            'ok'   => 'ring-1 ring-emerald-200 bg-emerald-50/70 dark:bg-emerald-900/10 dark:ring-emerald-800',
            default=> 'ring-1 ring-slate-200 bg-white dark:bg-slate-800/40 dark:ring-white/10',
        };
    };

    // Colores del texto del mensajito de estado
    $badgeClass = function($state) {
        return match ($state) {
            'bad'  => 'text-rose-700 dark:text-rose-300',
            'warn' => 'text-amber-700 dark:text-amber-300',
            'ok'   => 'text-emerald-700 dark:text-emerald-300',
            default=> 'text-slate-600 dark:text-slate-300',
        };
    };
@endphp

@if(empty($ranges))
    <div class="rounded-xl p-4 ring-1 ring-slate-200 dark:ring-white/10 bg-white dark:bg-slate-800/40">
        <p class="text-sm text-slate-600 dark:text-slate-300">
            No hay rangos configurados para la hortaliza seleccionada.
        </p>
    </div>
@else
    {{-- Grid de tarjetas --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($ranges as $key => $r)
            @php
                $val    = $measurements[$key] ?? null;
                $state  = $judge($val, $r['min'] ?? null, $r['max'] ?? null);
                $classes= $cardClass($state['state']);
                $icon   = $r['icon'] ?? 'ri-information-line';
                $unit   = $r['unit'] ?? '';
            @endphp

            <article class="rounded-xl p-4 {{ $classes }}">
                <header class="flex items-center justify-between">
                    <p class="kicker">{{ $r['label'] ?? ucfirst(str_replace('_',' ',$key)) }}</p>
                    <i class="{{ $icon }} text-2xl
                        {{ $state['state']==='bad' ? 'text-rose-600 dark:text-rose-300'
                         : ($state['state']==='warn' ? 'text-amber-600 dark:text-amber-300'
                         : 'text-emerald-500 dark:text-emerald-300') }}"></i>
                </header>

                <div class="mt-3">
                    @if($val !== null)
                        <p class="text-2xl font-extrabold">
                            {{ $fmt($val) }}{{ $unit }}
                        </p>
                    @else
                        <p class="text-2xl font-extrabold">—{{ $unit }}</p>
                    @endif

                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Rango: {{ $fmt($r['min'] ?? 0) }}–{{ $fmt($r['max'] ?? 0) }}{{ $unit }}
                    </p>

                    {{-- Ahora también mostramos el mensaje cuando está OK (verde) --}}
                    @if(in_array($state['state'], ['warn','bad','ok']))
                        <div class="mt-3 text-xs font-medium {{ $badgeClass($state['state']) }}">
                            {{ $state['msg'] }}
                        </div>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-8 text-xs text-slate-500 dark:text-slate-400">
        @if($latest)
            @php
                $when = $latest->fecha ?? $latest->created_at;
            @endphp
            Última lectura: <b>{{ $when }}</b>
        @else
            No hay registros en <code>registro_mediciones</code>.
        @endif
    </div>
@endif