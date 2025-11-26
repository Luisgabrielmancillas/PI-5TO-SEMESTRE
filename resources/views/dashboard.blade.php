<x-app-layout title="Resumen | HydroBox">
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

    <div class="flex flex-col w-full min-h-screen overflow-x-hidden overflow-y-auto">
        <div class="w-full px-3 sm:px-6 lg:px-8 py-4">
            <!-- Sección principal -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                <!-- Gauges Izquierda -->
                <div class="hidden md:flex md:col-span-3 lg:col-span-2 flex-col gauges-col">
                    <div class="space-y-8 flex flex-col items-center justify-center h-full">
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeTempAire" class="mb-3 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">Temp. Aire</div>
                            <div id="gaugeTempAireValue" class="text-gray-600 text-xs sm:text-sm">--°C</div>
                        </div>

                        <div class="flex flex-col items-center">
                            <canvas id="gaugeHumedad" class="mb-3 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">Humedad Aire</div>
                            <div id="gaugeHumedadValue" class="text-gray-600 text-xs sm:text-sm">--%</div>
                        </div>

                        <div class="flex flex-col items-center">
                            <canvas id="gaugeTempAgua" class="mb-3 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">Temp. Agua</div>
                            <div id="gaugeTempAguaValue" class="text-gray-600 text-xs sm:text-sm">--°C</div>
                        </div>
                    </div>
                </div>

                <!-- Gráficas Centrales -->
                <div class="md:col-span-6 lg:col-span-8 flex flex-col items-center justify-center space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg w-full p-3">
                        <h6 class="text-center text-gray-700 dark:text-gray-200 font-semibold mb-2 text-sm sm:text-base">Promedio de Mediciones</h6>
                        <div class="relative w-full h-48 sm:h-64 lg:h-72">
                            <canvas id="barChart" class="w-full h-full"></canvas>
                            <div id="barChartEmpty" class="hidden absolute inset-0 flex items-center justify-center text-gray-500 dark:text-gray-400 text-xs sm:text-sm text-center px-4">
                                No se encontraron registros para la hortaliza seleccionada.
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg w-full p-3">
                        <h6 class="text-center text-gray-700 dark:text-gray-200 font-semibold mb-2 text-sm sm:text-base">Mediciones Recientes</h6>
                        <div class="relative w-full h-48 sm:h-64 lg:h-72">
                            <canvas id="lineChart" class="w-full h-full"></canvas>
                            <div id="lineChartEmpty" class="hidden absolute inset-0 flex items-center justify-center text-gray-500 dark:text-gray-400 text-xs sm:text-sm text-center px-4">
                                No se encontraron registros para la hortaliza seleccionada.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gauges Derecha -->
                <div class="hidden md:flex md:col-span-3 lg:col-span-2 flex-col gauges-col">
                    <div class="space-y-8 flex flex-col items-center justify-center h-full">
                        <div class="flex flex-col items-center">
                            <canvas id="gaugePH" class="mb-3 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">pH Agua</div>
                            <div id="gaugePHValue" class="text-gray-600 text-xs sm:text-sm">--</div>
                        </div>
            
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeNivel" class="mb-3 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">Nivel Agua</div>
                            <div id="gaugeNivelValue" class="text-gray-600 text-xs sm:text-sm">-- cm</div>
                        </div>
            
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeORP" class="mb-3 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">ORP</div>
                            <div id="gaugeORPValue" class="text-gray-600 text-xs sm:text-sm">-- mV</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gauges Móviles -->
            <div class="md:hidden mt-6">
                <div class="grid grid-cols-2 gap-6 sm:gap-8">
                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeTempAireMobile" class="mb-3 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">Temp. Aire</div>
                        <div id="gaugeTempAireValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">--°C</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeHumedadMobile" class="mb-3 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">Humedad Aire</div>
                        <div id="gaugeHumedadValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">--%</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeTempAguaMobile" class="mb-3 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">Temp. Agua</div>
                        <div id="gaugeTempAguaValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">--°C</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugePHMobile" class="mb-3 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">pH Agua</div>
                        <div id="gaugePHValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">--</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeNivelMobile" class="mb-3 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">Nivel Agua</div>
                        <div id="gaugeNivelValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">-- cm</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeORPMobile" class="mb-3 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">ORP</div>
                        <div id="gaugeORPValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">-- mV</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
let barChartInstance = null;
let lineChartInstance = null;
let currentRanges = {};
let chartDataCache = null; 

async function fetchSensorData() {
    try {
        const response = await fetch('{{ route("dashboard.latest") }}');
        if (!response.ok) {
            throw new Error('Error al obtener datos');
        }
        const data = await response.json();
        
        console.log('Datos recibidos:', data);

        if (data.ranges && Object.keys(data.ranges).length > 0) {
            currentRanges = data.ranges;
            console.log('Rangos actualizados:', currentRanges);
        } else {
            console.warn('No se recibieron rangos específicos, usando valores por defecto');
            currentRanges = {
                tempAire: { min: 10, max: 35 },
                humAire: { min: 40, max: 80 },
                tempAgua: { min: 15, max: 28 },
                ph: { min: 5.5, max: 7.0 },
                orp: { min: 200, max: 800 },
                nivelAgua: { min: 10, max: 80 }
            };
        }


        updateGaugeWithRanges('gaugeTempAire', data.tempAire, 'tempAire', '°C');
        updateGaugeWithRanges('gaugeHumedad', data.humAire, 'humAire', '%');
        updateGaugeWithRanges('gaugeTempAgua', data.tempAgua, 'tempAgua', '°C');
        updateGaugeWithRanges('gaugePH', data.ph, 'ph', '');
        updateGaugeWithRanges('gaugeNivel', data.nivelAgua, 'nivelAgua', ' cm');
        updateGaugeWithRanges('gaugeORP', data.orp, 'orp', 'mV');
        
   
        updateGaugeWithRanges('gaugeTempAireMobile', data.tempAire, 'tempAire', '°C');
        updateGaugeWithRanges('gaugeHumedadMobile', data.humAire, 'humAire', '%');
        updateGaugeWithRanges('gaugeTempAguaMobile', data.tempAgua, 'tempAgua', '°C');
        updateGaugeWithRanges('gaugePHMobile', data.ph, 'ph', '');
        updateGaugeWithRanges('gaugeNivelMobile', data.nivelAgua, 'nivelAgua', ' cm');
        updateGaugeWithRanges('gaugeORPMobile', data.orp, 'orp', 'mV');
    } catch (error) {
        console.error('Error fetching sensor data:', error);
    }
}


function updateGaugeWithRanges(gaugeId, value, rangeKey, unit) {
    const range = currentRanges[rangeKey];
    
    if (!range) {
        console.warn(`No se encontró rango para: ${rangeKey}`);
        const defaultRanges = {
            tempAire: { min: 10, max: 35 },
            humAire: { min: 40, max: 80 },
            tempAgua: { min: 15, max: 28 },
            ph: { min: 5.5, max: 7.0 },
            orp: { min: 200, max: 800 },
            nivelAgua: { min: 10, max: 80 }
        };
        
        const defaultRange = defaultRanges[rangeKey] || { min: 0, max: 100 };
        updateGauge(gaugeId, value, defaultRange.max, unit, rangeKey);
        return;
    }


    const visualMax = Math.max(range.max * 1.2, value * 1.1, range.max + 5);
    
    console.log(`Gauge ${gaugeId}: valor=${value}, rango=[${range.min}-${range.max}], visualMax=${visualMax}`);
    

    updateGauge(gaugeId, value, visualMax, unit, rangeKey, range);
    

    const valueElement = document.getElementById(gaugeId + 'Value');
    const mobileValueElement = document.getElementById(gaugeId + 'ValueMobile');
    
    if (valueElement) {
        valueElement.textContent = value.toFixed(1) + (unit ? unit : '');

        if (value < range.min || value > range.max) {
            valueElement.classList.add('text-red-500', 'font-bold');
            valueElement.classList.remove('text-gray-600');
        } else {
            valueElement.classList.remove('text-red-500', 'font-bold');
            valueElement.classList.add('text-gray-600');
        }
    }
    
    if (mobileValueElement) {
        mobileValueElement.textContent = value.toFixed(1) + (unit ? unit : '');
        if (value < range.min || value > range.max) {
            mobileValueElement.classList.add('text-red-500', 'font-bold');
            mobileValueElement.classList.remove('text-gray-600', 'dark:text-gray-400');
        } else {
            mobileValueElement.classList.remove('text-red-500', 'font-bold');
            mobileValueElement.classList.add('text-gray-600', 'dark:text-gray-400');
        }
    }
}


function createGauge(id, value, max, unit, rangeKey, range) {
    const canvas = document.getElementById(id);
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = Math.min(centerX, centerY) - 8;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const displayValue = Math.min(value, max * 1.1);
    const normalizedValue = Math.min(value / max, 1.1);

    const isDark = document.documentElement.classList.contains('dark');


    let gaugeColor;
    let alertText = '';
    
    if (range) {

        if (value < range.min) {
            gaugeColor = isDark ? '#f59e0b' : '#d97706'; // Amarillo/naranja para bajo
            alertText = 'BAJO';
        } else if (value > range.max) {
            gaugeColor = isDark ? '#ef4444' : '#dc2626'; // Rojo para alto
            alertText = 'ALTO';
        } else {

            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, isDark ? '#6366f1' : '#22d3ee');
            gradient.addColorStop(1, isDark ? '#22d3ee' : '#22c55e');
            gaugeColor = gradient;
        }
    } else {

        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradient.addColorStop(0, isDark ? '#6366f1' : '#22d3ee');
        gradient.addColorStop(1, isDark ? '#22d3ee' : '#22c55e');
        gaugeColor = gradient;
    }

    const startAngle = 0.75 * Math.PI;
    const endAngle   = 2.25 * Math.PI;
    const angleRange = endAngle - startAngle;


    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, startAngle, endAngle);
    ctx.strokeStyle = isDark ? '#374151' : '#e5e7eb';
    ctx.lineWidth = 10;
    ctx.stroke();


    const fillAngle = startAngle + (angleRange * normalizedValue);
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, startAngle, fillAngle);
    ctx.strokeStyle = gaugeColor;
    ctx.lineWidth = 8;
    ctx.stroke();


    const markerRadius = radius - 4;
    const markerX = centerX + markerRadius * Math.cos(fillAngle);
    const markerY = centerY + markerRadius * Math.sin(fillAngle);

    ctx.beginPath();
    ctx.moveTo(centerX, centerY);
    ctx.lineTo(markerX, markerY);
    ctx.strokeStyle = isDark ? '#facc15' : '#000000';
    ctx.lineWidth = 2;
    ctx.stroke();


    ctx.beginPath();
    ctx.arc(markerX, markerY, 3, 0, 2 * Math.PI);
    ctx.fillStyle = isDark ? '#facc15' : '#000000';
    ctx.fill();


    if (alertText) {
        ctx.fillStyle = gaugeColor; 
        ctx.font = 'bold 10px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('¡' + alertText + '!', centerX, centerY + 25);
    }
}

async function fetchChartData() {
    try {
        const response = await fetch('{{ route("dashboard.chart") }}');
        if (!response.ok) {
            throw new Error('Error al obtener datos de gráficas');
        }
        const data = await response.json();

        console.log('Datos de gráficas:', data);


        if (data.ranges && Object.keys(data.ranges).length > 0) {
            currentRanges = data.ranges;
            console.log('Rangos actualizados desde gráficas:', currentRanges);
        }

        if (data.empty) {
            showChartsEmptyState();
        } else {

            if (!chartDataCache || JSON.stringify(chartDataCache) !== JSON.stringify(data)) {
                chartDataCache = data;
                hideChartsEmptyState();
                updateCharts(data);
            }
        }
        
    } catch (error) {
        console.error('Error fetching chart data:', error);
        showChartsEmptyState();
    }
}

function showChartsEmptyState() {
    const barCanvas   = document.getElementById('barChart');
    const lineCanvas  = document.getElementById('lineChart');
    const barEmpty    = document.getElementById('barChartEmpty');
    const lineEmpty   = document.getElementById('lineChartEmpty');

    if (barChartInstance) {
        barChartInstance.destroy();
        barChartInstance = null;
    }
    if (lineChartInstance) {
        lineChartInstance.destroy();
        lineChartInstance = null;
    }

    if (barCanvas)  barCanvas.classList.add('hidden');
    if (lineCanvas) lineCanvas.classList.add('hidden');
    if (barEmpty)   barEmpty.classList.remove('hidden');
    if (lineEmpty)  lineEmpty.classList.remove('hidden');
}

function hideChartsEmptyState() {
    const barCanvas   = document.getElementById('barChart');
    const lineCanvas  = document.getElementById('lineChart');
    const barEmpty    = document.getElementById('barChartEmpty');
    const lineEmpty   = document.getElementById('lineChartEmpty');

    if (barCanvas)  barCanvas.classList.remove('hidden');
    if (lineCanvas) lineCanvas.classList.remove('hidden');
    if (barEmpty)   barEmpty.classList.add('hidden');
    if (lineEmpty)  lineEmpty.classList.add('hidden');
}

function updateCharts(chartData) {
    const isDark = document.documentElement.classList.contains('dark');
    const axisColor = isDark ? '#d1d5db' : '#a0aec0';
    const gridColor = isDark ? '#374151' : '#2d3748';
    const legendColor = isDark ? '#d1d5db' : '#a0aec0';


    if (!barChartInstance) {
        barChartInstance = new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: ['pH', 'ORP', 'Temp Agua', 'Temp Aire', 'Hum Aire', 'Nivel Agua'],
                datasets: [{
                    label: 'Valor promedio',
                    data: [
                        chartData.averages.ph,
                        chartData.averages.ce,
                        chartData.averages.tempAgua,
                        chartData.averages.tempAire,
                        chartData.averages.humAire,
                        chartData.averages.nivelAgua
                    ],
                    backgroundColor: isDark ? 
                        ['#6366f1', '#d946ef', '#facc15', '#22d3ee', '#10b981', '#f97316'] : 
                        ['#22d3ee', '#d946ef', '#facc15', '#ef4444', '#10b981', '#f97316']
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    y: { 
                        beginAtZero: true,
                        ticks: { color: axisColor, font: { size: 10 } },
                        grid: { color: gridColor }
                    },
                    x: { 
                        ticks: { 
                            color: axisColor, 
                            font: { size: 10 },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: { color: gridColor }
                    }
                },
                plugins: {
                    legend: { 
                        labels: { color: legendColor, font: { size: 11 } }
                    }
                }
            }
        });
    } else {

        barChartInstance.data.datasets[0].data = [
            chartData.averages.ph,
            chartData.averages.ce,
            chartData.averages.tempAgua,
            chartData.averages.tempAire,
            chartData.averages.humAire,
            chartData.averages.nivelAgua
        ];
        barChartInstance.update('none'); 
    }


    if (!lineChartInstance) {
        lineChartInstance = new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    { 
                        label: 'pH', 
                        data: chartData.datasets.ph, 
                        borderColor: '#6366f1', 
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false 
                    },
                    { 
                        label: 'ORP', 
                        data: chartData.datasets.ce, 
                        borderColor: '#d946ef', 
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false 
                    },
                    { 
                        label: 'Temp. Agua', 
                        data: chartData.datasets.tempAgua, 
                        borderColor: '#facc15', 
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false 
                    },
                    { 
                        label: 'Temp. Aire', 
                        data: chartData.datasets.tempAire, 
                        borderColor: '#ef4444', 
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false 
                    },
                    { 
                        label: 'Hum. Aire', 
                        data: chartData.datasets.humAire, 
                        borderColor: '#10b981', 
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false 
                    },
                    { 
                        label: 'Nivel Agua', 
                        data: chartData.datasets.nivelAgua, 
                        borderColor: '#f97316', 
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false 
                    }
                ]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    y: { 
                        beginAtZero: false, 
                        ticks: { color: axisColor, font: { size: 10 } },
                        grid: { color: gridColor }
                    },
                    x: { 
                        ticks: { color: axisColor, font: { size: 10 } },
                        grid: { color: gridColor }
                    }
                },
                plugins: {
                    legend: { 
                        labels: { color: legendColor, font: { size: 11 } }
                    }
                }
            }
        });
    } else {
        // Actualizar datos existentes sin recrear el chart
        lineChartInstance.data.labels = chartData.labels;
        lineChartInstance.data.datasets[0].data = chartData.datasets.ph;
        lineChartInstance.data.datasets[1].data = chartData.datasets.ce;
        lineChartInstance.data.datasets[2].data = chartData.datasets.tempAgua;
        lineChartInstance.data.datasets[3].data = chartData.datasets.tempAire;
        lineChartInstance.data.datasets[4].data = chartData.datasets.humAire;
        lineChartInstance.data.datasets[5].data = chartData.datasets.nivelAgua;
        lineChartInstance.update('none'); // 'none' evita animación que causa parpadeo
    }
}

function updateGauge(id, value, max, unit, rangeKey, range) {
    const canvas = document.getElementById(id);
    if (canvas) {
        createGauge(id, value, max, unit, rangeKey, range);
    }
}

function initializeGauges() {
    const gaugeConfigs = {
        'gaugeTempAire': { value: 0, max: 50, unit: '°C' },
        'gaugeHumedad': { value: 0, max: 100, unit: '%' },
        'gaugeTempAgua': { value: 0, max: 50, unit: '°C' },
        'gaugePH': { value: 0, max: 14, unit: '' },
        'gaugeNivel': { value: 0, max: 100, unit: '' },
        'gaugeORP': { value: 0, max: 100, unit: 'mV' },
        'gaugeTempAireMobile': { value: 0, max: 50, unit: '°C' },
        'gaugeHumedadMobile': { value: 0, max: 100, unit: '%' },
        'gaugeTempAguaMobile': { value: 0, max: 50, unit: '°C' },
        'gaugePHMobile': { value: 0, max: 14, unit: '' },
        'gaugeNivelMobile': { value: 0, max: 100, unit: '' },
        'gaugeORPMobile': { value: 0, max: 100, unit: 'mV' }
    };

    Object.keys(gaugeConfigs).forEach(id => {
        const config = gaugeConfigs[id];
        const canvas = document.getElementById(id);
        if (canvas) {
            canvas.width = 100;
            canvas.height = 100;
            createGauge(id, config.value, config.max, config.unit);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initializeGauges();
    
    fetchSensorData();
    fetchChartData();
    
    setInterval(fetchSensorData, 10000);
    setInterval(fetchChartData, 30000);
    
    const observer = new MutationObserver(() => {
        initializeGauges();
        fetchChartData();
    });
    observer.observe(document.documentElement, { 
        attributes: true, 
        attributeFilter: ['class'] 
    });
});
    </script>
    @endpush
</x-app-layout>