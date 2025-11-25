{{-- SENSOR TEMP AMBIENTE --}}
<div id="tempSensor" class="absolute top-[78px] left-[47%] z-20 flex items-center">
    <span class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
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
    <span class="border border-2 text-sm text-black px-3 py-1 rounded-full bg-gray-50 border-gray-300 shadow-md whitespace-nowrap">
        @if(!empty($latest))
            Humedad: {{ number_format((float)$latest->hum_value, 1, '.', '') }}%
        @else
            Humedad: —%
        @endif
    </span>
</div>

{{-- T. AGUA, ORP, pH --}}
<div class="absolute top-[79%] left-[32%] z-20 flex flex-col space-y-2 items-start">
    <span class="text-sm text-black px-3 py-1 rounded-full 
                 bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
        @if(!empty($latest))
            T. Agua: {{ number_format((float)$latest->tagua_value, 1, '.', '') }}°C
        @else
            T. Agua: —°C
        @endif
    </span>

    <span class="text-sm text-black px-3 py-1 rounded-full 
                 bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
        @if(!empty($latest))
            ORP: {{ number_format((float)$latest->ce_value, 0, '.', '') }} mV
        @else
            ORP: — mV
        @endif
    </span>

    <span class="text-sm text-black px-3 py-1 rounded-full 
                 bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
        @if(!empty($latest))
            pH: {{ number_format((float)$latest->ph_value, 2, '.', '') }}
        @else
            pH: —
        @endif
    </span>
</div>

{{-- NIVEL DE AGUA --}}
<div class="absolute top-[75%] left-[58%] z-20">
    <span class="text-sm text-black px-3 py-1 rounded-full 
                 bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
        @if(!empty($latest))
            N. Agua: {{ number_format((float)$latest->us_value, 0, '.', '') }}cm
        @else
            N. Agua: —cm
        @endif
    </span>
</div>