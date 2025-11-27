<x-app-layout title="Hortalizas | HydroBox">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de usuarios') }}
            </h2>

            <span class="pill pill-emerald ml-auto inline-flex items-center">
                Hortaliza seleccionada:
                @if(!empty($selectedCrop))
                    <b class="ml-1">{{ $selectedCrop->nombre }}</b>
                @else
                    <b class="ml-1">No hay</b>
                @endif
            </span>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200 transition-colors duration-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-2xl font-semibold text-center sm:text-left">Gestión de Hortalizas</h3>

            {{-- SOLO ADMIN VE EL BOTÓN --}}
            @if(auth()->user()->role === 'admin')
                <button id="openModal"
                    class="w-full sm:w-auto bg-teal-500 hover:bg-teal-400 text-white px-4 py-2 rounded-lg shadow-md transition text-center">
                    Cambiar de cultivo
                </button>
            @endif
        </div>

        <div class="space-y-3">
            @foreach($hortalizas as $hortaliza)
                @php
                    $nombre = strtolower($hortaliza->nombre);
                    $iconPath = asset('images/' . ($icons[$nombre] ?? 'lechuga.png'));

                    $unidades = [
                        'temperatura' => '°C',
                        'ph' => '',
                        'orp' => 'mV',
                        'conductividad' => 'µS/cm',
                        'nivel de agua' => '%',
                        'humedad' => '%',
                        'luminosidad' => 'lux',
                    ];
                @endphp

                <div class="rounded-xl px-4 sm:px-5 py-4 border transition-colors duration-200
                    bg-white/70 dark:bg-gray-800
                    {{ $hortaliza->seleccion ? 'border-teal-400' : 'border-gray-300 dark:border-gray-700 hover:border-gray-500 dark:hover:border-gray-400' }}">

                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $iconPath }}" alt="{{ $hortaliza->nombre }}" class="w-8 h-8 object-contain">
                            <span class="text-lg font-semibold">{{ ucfirst($hortaliza->nombre) }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($hortaliza->seleccion)
                                <span class="text-teal-500 font-semibold text-sm">Seleccionada</span>
                            @endif

                            <button 
                                onclick="toggleRangos({{ $hortaliza->id_hortaliza }})"
                                id="icono-{{ $hortaliza->id_hortaliza }}"
                                class="transition-transform duration-300 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="rangos-{{ $hortaliza->id_hortaliza }}" class="hidden mt-4 bg-gray-100 dark:bg-gray-700 rounded-lg p-3 text-sm">
                        <h5 class="font-semibold mb-2 text-center text-gray-800 dark:text-gray-100">🌿 Rangos óptimos</h5>
                                    
                        @if($hortaliza->config_sensores->isEmpty())
                            <p class="text-gray-500">No hay configuraciones registradas para esta hortaliza.</p>
                        @else
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-300 dark:border-gray-600">
                                        <th class="py-1">Sensor</th>
                                        <th class="py-1 text-center">Valor Mínimo</th>
                                        <th class="py-1 text-center">Valor Máximo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hortaliza->config_sensores as $config)
                                        @php
                                            $nombreSensor = strtolower(trim($config->sensore->nombre));
                                        
                                            $unidad = '';
                                            if (str_contains($nombreSensor, 'temperatura')) $unidad = '°C';
                                            elseif (str_contains($nombreSensor, 'ph')) $unidad = 'pH';
                                            elseif (str_contains($nombreSensor, 'orp')) $unidad = 'mV';
                                            elseif (str_contains($nombreSensor, 'conductividad')) $unidad = 'µS/cm';
                                            elseif (str_contains($nombreSensor, 'nivel de agua') 
                                                    || str_contains($nombreSensor, 'ultrasónico') 
                                                    || str_contains($nombreSensor, 'ultrasonico')) $unidad = 'cm';
                                            elseif (str_contains($nombreSensor, 'humedad')) $unidad = '%';
                                            elseif (str_contains($nombreSensor, 'luminosidad')) $unidad = 'lux';
                                        @endphp
                    
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-1">{{ $config->sensore->nombre }} {{ $unidad }}</td>
                                            <td class="py-1 text-center">{{ $config->valor_min_acept }} {{ $unidad }}</td>
                                            <td class="py-1 text-center">{{ $config->valor_max_acept }} {{ $unidad }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    @if(auth()->user()->role === 'admin')
        <div id="modal"
            class="hidden fixed inset-0 bg-black/60 flex justify-center items-center z-50 p-4 transition-opacity duration-200">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl w-full max-w-sm shadow-xl transform transition-all scale-95 overflow-y-auto max-h-[90vh]">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Selecciona tu cultivo</h4>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
                </div>

                <form method="POST" action="{{ route('hortalizas.cambiar') }}">
                    @csrf
                    <div class="space-y-3">
                        @foreach($hortalizas as $hortaliza)
                            @php
                                $nombre = strtolower($hortaliza->nombre);
                                $iconPath = asset('images/' . ($icons[$nombre] ?? 'lechuga.png'));
                            @endphp

                            <button name="id_hortaliza" value="{{ $hortaliza->id_hortaliza }}"
                                class="w-full flex items-center space-x-3 px-4 py-2 rounded-lg transition text-left
                                {{ $hortaliza->seleccion 
                                    ? 'bg-teal-500 text-white' 
                                    : 'bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200' }}">
                                <img src="{{ $iconPath }}" alt="{{ $hortaliza->nombre }}" class="w-6 h-6 object-contain">
                                <span>{{ ucfirst($hortaliza->nombre) }}</span>
                            </button>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        <script>
            const modal = document.getElementById('modal');
            const openModal = document.getElementById('openModal');
            const closeModal = document.getElementById('closeModal');

            openModal?.addEventListener('click', () => modal.classList.remove('hidden'));
            closeModal?.addEventListener('click', () => modal.classList.add('hidden'));
            window.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });
        </script>
    @endif

    <script>
        function toggleRangos(id) {
            const div = document.getElementById(`rangos-${id}`);
            const icono = document.getElementById(`icono-${id}`);

            div.classList.toggle('hidden');
            icono.classList.toggle('rotate-180');
        }
    </script>

    <script>
        let currentSelected = {{ $selectedCrop->id_hortaliza ?? 0 }};
        
        setInterval(async () => {
            try {
                const response = await fetch("/hortalizas/seleccion-actual");
                const data = await response.json();
            
                const newSelected = data?.id_hortaliza ?? 0;
            
                if (newSelected !== currentSelected) {
                    location.reload();
                }
            
            } catch (error) {
                console.error("Error consultando selección:", error);
            }
        
        }, 2000);
    </script>
</x-app-layout>
