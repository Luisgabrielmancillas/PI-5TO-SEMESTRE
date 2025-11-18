<x-app-layout title="Historial | HydroBox">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de usuarios') }}
            </h2>

            {{-- Grupo derecho: botón PDF + pill de hortaliza --}}
            <div class="ml-auto flex items-center gap-3">
                {{-- Botón solo en desktop --}}
                <button
                    type="button"
                    id="hb-open-export-modal"
                    class="hidden lg:inline-flex items-center px-3 py-2 rounded-full text-sm font-medium
                        bg-red-600 text-white hover:bg-red-700 active:bg-red-800
                        shadow-sm shadow-red-500/40 transition"
                >
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M9 12h6m-7 4h8M5 8h14M6 4h12a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z"/>
                    </svg>
                    Exportar PDF
                </button>

                <span class="pill pill-emerald inline-flex items-center">
                    Hortaliza seleccionada:
                    @if(!empty($selectedCrop))
                        <b class="ml-1">{{ $selectedCrop->nombre }}</b>
                    @else
                        <b class="ml-1">No hay</b>
                    @endif
                </span>
            </div>
        </div>
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


    {{-- Modal Exportar PDF --}}
    <div id="hb-export-modal"
        class="fixed inset-0 z-50 hidden">
        {{-- Fondo oscuro --}}
        <div class="absolute inset-0 bg-slate-900/40"></div>

        {{-- Contenido modal --}}
        <div class="relative w-full h-full flex items-center justify-center">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl shadow-slate-900/20
                        w-full max-w-md mx-4 p-5 border border-slate-200 dark:border-slate-700">

                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-1">
                    Exportar historial a PDF
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    Selecciona los filtros que se usarán para generar el documento.
                </p>

                <form id="hb-export-form"
                    method="POST"
                    action="{{ route('history.exportPdf') }}"
                    target="_blank">
                    @csrf

                    {{-- Hortaliza --}}
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Hortaliza
                        </label>
                        <select name="crop" id="hb-export-crop"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600
                                    bg-white dark:bg-slate-900 text-sm
                                    focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">Selecciona una opción</option>
                            <option value="all">Todas</option>
                            @foreach($allCrops as $crop)
                                <option value="{{ $crop->id_hortaliza }}">
                                    {{ $crop->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sensor para gráficas --}}
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Sensor (para gráficas)
                        </label>
                        <select name="sensor" id="hb-export-sensor"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600
                                    bg-white dark:bg-slate-900 text-sm
                                    focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">Selecciona una opción</option>
                            <option value="all">Todos los sensores</option>
                            <option value="humedad">Humedad</option>
                            <option value="temp_agua">Temperatura del agua</option>
                            <option value="temp_ambiente">Temperatura del aire</option>
                            <option value="ph">pH</option>
                            <option value="orp">ORP</option>
                            <option value="ultrasonico">Ultrasónico</option>
                        </select>
                    </div>

                    {{-- Rango de fechas para gráficas --}}
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Rango de fechas (gráficas)
                        </label>
                        <select name="range" id="hb-export-range"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600
                                    bg-white dark:bg-slate-900 text-sm
                                    focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">Selecciona una opción</option>
                            <option value="week">Última semana</option>
                            <option value="month">Último mes</option>
                            <option value="semester">Último semestre</option>
                            <option value="year">Último año</option>
                            <option value="all" data-only-all-sensors="1">Todos</option>
                        </select>
                    </div>

                    {{-- Rango de fechas para tabla --}}
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Rango de fechas (tabla)
                        </label>
                        <select name="tableRange" id="hb-export-table-range"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600
                                    bg-white dark:bg-slate-900 text-sm
                                    focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">Selecciona una opción</option>
                            <option value="week">Última semana</option>
                            <option value="month">Último mes</option>
                            <option value="semester">Último semestre</option>
                            <option value="year">Último año</option>
                            <option value="all">Todos</option>
                        </select>
                    </div>

                    <p id="hb-export-error"
                    class="hidden text-xs text-red-600 mt-1"></p>

                    <div class="mt-5 flex justify-end gap-2">
                        <button type="button"
                                id="hb-export-cancel"
                                class="px-3 py-1.5 rounded-full text-xs font-medium
                                    border border-slate-300 dark:border-slate-600
                                    text-slate-700 dark:text-slate-200
                                    hover:bg-slate-100 dark:hover:bg-slate-800">
                            Cancelar
                        </button>

                        <button type="submit"
                                id="hb-export-submit"
                                class="px-4 py-1.5 rounded-full text-xs font-semibold
                                    bg-red-600 text-white hover:bg-red-700
                                    active:bg-red-800 shadow-sm shadow-red-500/40
                                    disabled:opacity-60 disabled:cursor-not-allowed">
                            Descargar PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>

    @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openBtn      = document.getElementById('hb-open-export-modal');
            const modal        = document.getElementById('hb-export-modal');
            const cancelBtn    = document.getElementById('hb-export-cancel');
            const form         = document.getElementById('hb-export-form');
            const cropSelect   = document.getElementById('hb-export-crop');
            const sensorSelect = document.getElementById('hb-export-sensor');
            const rangeSelect  = document.getElementById('hb-export-range');
            const tableRange   = document.getElementById('hb-export-table-range');
            const errorLabel   = document.getElementById('hb-export-error');

            function resetExportForm() {
                [cropSelect, sensorSelect, rangeSelect, tableRange].forEach(function(sel){
                    if (!sel) return;
                    const placeholder = sel.querySelector('option[value=""]');
                    if (placeholder) {
                        placeholder.disabled = false;
                        placeholder.selected = true;
                    }
                    sel.value = '';
                    sel.classList.remove('border-red-500');
                });

                if (errorLabel) {
                    errorLabel.classList.add('hidden');
                    errorLabel.textContent = '';
                }
            }

            function toggleModal(show) {
                if (!modal) return;
                if (show) {
                    resetExportForm();
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                } else {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }

            if (openBtn) {
                openBtn.addEventListener('click', () => toggleModal(true));
            }
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => toggleModal(false));
            }
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        toggleModal(false);
                    }
                });
            }

            // Regla: "Todos" en rango (gráficas) solo si sensor = all
            function validateRangeRule() {
                const sensor = sensorSelect.value;
                const range  = rangeSelect.value;

                const allOption = rangeSelect.querySelector('option[value="all"]');
                if (allOption) {
                    if (sensor === 'all') {
                        allOption.disabled = false;
                    } else {
                        allOption.disabled = true;
                        if (range === 'all') {
                            rangeSelect.value = '';
                        }
                    }
                }

                if (sensor !== 'all' && range === 'all') {
                    errorLabel.textContent = 'El rango "Todos" solo está permitido cuando el sensor es "Todos los sensores".';
                    errorLabel.classList.remove('hidden');
                    return false;
                }

                errorLabel.classList.add('hidden');
                errorLabel.textContent = '';
                return true;
            }

            if (sensorSelect && rangeSelect) {
                sensorSelect.addEventListener('change', validateRangeRule);
                rangeSelect.addEventListener('change', validateRangeRule);
            }

            // Una vez que se elige una opción distinta de vacío,
            // ya no se puede volver a "Selecciona una opción"
            function lockPlaceholderOnChange(selectEl) {
                if (!selectEl) return;
                selectEl.addEventListener('change', function () {
                    const placeholder = selectEl.querySelector('option[value=""]');
                    if (placeholder && selectEl.value !== '') {
                        placeholder.disabled = true;
                    }
                });
            }

            lockPlaceholderOnChange(cropSelect);
            lockPlaceholderOnChange(sensorSelect);
            lockPlaceholderOnChange(rangeSelect);
            lockPlaceholderOnChange(tableRange);

            if (form) {
                form.addEventListener('submit', function (e) {
                    let ok = true;

                    [cropSelect, sensorSelect, rangeSelect, tableRange].forEach(function (sel) {
                        if (sel && !sel.value) {
                            ok = false;
                            sel.classList.add('border-red-500');
                        } else if (sel) {
                            sel.classList.remove('border-red-500');
                        }
                    });

                    if (!validateRangeRule()) {
                        ok = false;
                    }

                    if (!ok) {
                        e.preventDefault();
                        return;
                    }

                    // Si todo está bien: permitir el submit y cerrar el modal
                    toggleModal(false);
                });
            }
        });
        </script>
    @endpush

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
