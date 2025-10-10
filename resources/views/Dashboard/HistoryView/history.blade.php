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
                        {{-- Contenedor donde se inyectan los canvas de las gráficas --}}
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

                            {{-- TBODY dinámico (partial) --}}
                            <tbody id="tableBody" class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @include('Dashboard.HistoryView.partials.tables.tbody', ['items' => $items])
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación inicial (se actualizará con el partial) --}}
                    @if ($items)
                        <div id="tablePagination" class="mt-4">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    {{-- Chart.js desde CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
    <script>
        (() => {
        const tableRangeSelect = document.getElementById('tableRangeSelect');
        const tbody    = document.getElementById('tableBody');
        const pagWrap  = document.getElementById('tablePagination');
        const urlTable = "{{ route('history.table') }}";

        async function loadTable() {
            const params = new URLSearchParams({ tableRange: tableRangeSelect.value });
            const res = await fetch(`${urlTable}?${params.toString()}`, { headers: {'X-Requested-With':'XMLHttpRequest'} });
            const html = await res.text();

            // Insertar nuevo tbody
            tbody.innerHTML = html;

            // Actualizar paginación: buscamos links en la respuesta (si tuvieras un contenedor dedicado en el partial, podríamos aislarlo)
            // Aquí, pedimos otra vez la misma URL pero sin XHR para obtener links? Mejor: interceptar clicks del paginador:
            attachPaginationHandlers();
        }

        function attachPaginationHandlers() {
            // Interceptar paginación si existe
            document.querySelectorAll('#tablePagination a').forEach(a => {
            a.addEventListener('click', async (e) => {
                e.preventDefault();
                const href = a.getAttribute('href');
                if (!href) return;
                const res = await fetch(href, { headers:{'X-Requested-With':'XMLHttpRequest'} });
                const html = await res.text();
                tbody.innerHTML = html;

                // reconstruir paginación del contenedor externo
                const dom = new DOMParser().parseFromString(html, 'text/html');
                const innerLinks = dom.querySelectorAll('nav[role="navigation"]');
                pagWrap.innerHTML = '';
                innerLinks.forEach(n => pagWrap.appendChild(n));
                attachPaginationHandlers();
            });
            });
        }

        tableRangeSelect.addEventListener('change', loadTable);

        // Primer enganche para la paginación inicial
        attachPaginationHandlers();
        })();
    </script>


    @include('Dashboard.HistoryView.partials.graphs.scripts', [
        'initialSensor' => $initialSensor,
        'initialRange'  => $initialRange,
    ])
</x-app-layout>
