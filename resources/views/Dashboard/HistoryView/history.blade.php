<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Historial') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- ======================= SECCIÓN GRÁFICAS ======================= --}}
            <section class="bg-white dark:bg-slate-800 shadow-sm sm:rounded-lg border border-slate-200/60 dark:border-slate-700/60">
                <div class="p-4 sm:p-6">
                    <header class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Gráficas</h3>
                        <div class="flex items-center gap-2 sm:gap-3">
                            {{-- Sensor --}}
                            <select id="sensorSelect" class="rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                                <option value="all" {{ $initialSensor==='all'?'selected':'' }}>Todos los sensores</option>
                                <option value="humedad">Humedad</option>
                                <option value="temp_ambiente">Temperatura ambiente</option>
                                <option value="ph">pH</option>
                                <option value="orp">ORP</option>
                                <option value="temp_agua">Temperatura del agua</option>
                                <option value="ultrasonico">Ultrasónico</option>
                            </select>

                            {{-- Rango (Datos) --}}
                            <select id="rangeSelect" class="rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                                <option value="all">Todos</option>
                                <option value="week" {{ $initialRange==='week'?'selected':'' }}>Última semana</option>
                                <option value="month">Último mes</option>
                                <option value="semester">Último semestre</option>
                                <option value="year">Último año</option>
                            </select>
                        </div>
                    </header>

                    <div id="chartsHost" class="mt-6 space-y-6">
                        @include('Dashboard.HistoryView.partials.graphs.placeholder')
                    </div>
                </div>
            </section>

            {{-- ======================= SECCIÓN TABLA ======================= --}}
            <section class="bg-white dark:bg-slate-800 shadow-sm sm:rounded-lg border border-slate-200/60 dark:border-slate-700/60">
                <div class="p-4 sm:p-6">
                    <header class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Registros</h3>
                        <div>
                            <select id="tableRangeSelect" class="rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                                <option value="week" {{ $tableRange==='week'?'selected':'' }}>Última semana</option>
                                <option value="month" {{ $tableRange==='month'?'selected':'' }}>Último mes</option>
                                <option value="semester" {{ $tableRange==='semester'?'selected':'' }}>Último semestre</option>
                                <option value="year" {{ $tableRange==='year'?'selected':'' }}>Último año</option>
                                <option value="all" {{ $tableRange==='all'?'selected':'' }}>Todos</option>
                            </select>
                        </div>
                    </header>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-900/40">
                                <tr>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">N°</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Humedad</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Temp. amb.</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">pH</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">ORP</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Temp. agua</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Ultrasónico</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Fecha</th>
                                </tr>
                            </thead>

                            <tbody id="tableBody" class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @include('Dashboard.HistoryView.partials.tables.tbody', ['items' => $items])
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación (contenedor fijo, se rellena por AJAX) --}}
                    <div id="tablePagination" class="mt-4">
                        @include('Dashboard.HistoryView.partials.tables.pagination', ['items' => $items])
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>

    {{-- ====== JS TABLA (paginación + filtro por rango) ====== --}}
    <script>
    (() => {
        const tableRangeSelect = document.getElementById('tableRangeSelect');
        const tbody   = document.getElementById('tableBody');
        const pagWrap = document.getElementById('tablePagination');
        const urlTable = "{{ route('history.table') }}";

        async function refreshTable(url = null) {
            const params = new URLSearchParams({ tableRange: tableRangeSelect.value });
            const target = url ? url : `${urlTable}?${params.toString()}`;

            const res = await fetch(target, { headers: {'X-Requested-With':'XMLHttpRequest'} });
            const json = await res.json();

            tbody.innerHTML   = json.tbody;
            pagWrap.innerHTML = json.pagination;
        }

        // Filtro por rango
        tableRangeSelect.addEventListener('change', () => refreshTable());

        // Delegación de eventos para paginación (no necesitamos re-enganchar)
        pagWrap.addEventListener('click', (e) => {
            const a = e.target.closest('a[href]');
            if (!a) return;
            e.preventDefault();
            refreshTable(a.href);
        });
    })();
    </script>

    {{-- ====== JS GRÁFICAS (tu partial existente) ====== --}}
    @include('Dashboard.HistoryView.partials.graphs.scripts', [
        'initialSensor' => $initialSensor,
        'initialRange'  => $initialRange,
    ])
</x-app-layout>
