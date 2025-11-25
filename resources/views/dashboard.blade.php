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
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                <div class="hidden md:flex md:col-span-3 lg:col-span-2 flex-col gauges-col space-y-4">
                    <div class="space-y-4">
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeTempAire" class="mb-2 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">Temp. Aire</div>
                            <div id="gaugeTempAireValue" class="text-gray-600 text-xs sm:text-sm">--°C</div>
                        </div>

                        <div class="flex flex-col items-center">
                            <canvas id="gaugeHumedad" class="mb-2 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">Humedad Aire</div>
                            <div id="gaugeHumedadValue" class="text-gray-600 text-xs sm:text-sm">--%</div>
                        </div>

                        <div class="flex flex-col items-center">
                            <canvas id="gaugeTempAgua" class="mb-2 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">Temp. Agua</div>
                            <div id="gaugeTempAguaValue" class="text-gray-600 text-xs sm:text-sm">--°C</div>
                        </div>
                    </div>
                </div>


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


                <div class="hidden md:flex md:col-span-3 lg:col-span-2 flex-col gauges-col space-y-4">
                    <div class="space-y-4">
                        <div class="flex flex-col items-center">
                            <canvas id="gaugePH" class="mb-2 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">pH Agua</div>
                            <div id="gaugePHValue" class="text-gray-600 text-xs sm:text-sm">--</div>
                        </div>
            
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeNivel" class="mb-2 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">Nivel Agua</div>
                            <div id="gaugeNivelValue" class="text-gray-600 text-xs sm:text-sm">-- cm</div>
                        </div>
            
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeORP" class="mb-2 w-24 sm:w-28"></canvas>
                            <div class="text-xs sm:text-sm font-semibold">ORP</div>
                            <div id="gaugeORPValue" class="text-gray-600 text-xs sm:text-sm">-- mV</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="md:hidden mt-6">
                <div class="grid grid-cols-2 gap-4 sm:gap-6">
                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeTempAireMobile" class="mb-2 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">Temp. Aire</div>
                        <div id="gaugeTempAireValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">--°C</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeHumedadMobile" class="mb-2 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">Humedad Aire</div>
                        <div id="gaugeHumedadValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">--%</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeTempAguaMobile" class="mb-2 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">Temp. Agua</div>
                        <div id="gaugeTempAguaValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">--°C</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugePHMobile" class="mb-2 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">pH Agua</div>
                        <div id="gaugePHValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">--</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeNivelMobile" class="mb-2 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">Nivel Agua</div>
                        <div id="gaugeNivelValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">-- cm</div>
                    </div>

                    <div class="flex flex-col items-center bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <canvas id="gaugeORPMobile" class="mb-2 w-20 sm:w-24"></canvas>
                        <div class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-200">ORP</div>
                        <div id="gaugeORPValueMobile" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">-- mV</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    @media (min-width: 768px) {
        html, body {
            height: 100%;
            overflow-y: auto; 
        }
    
        .flex.flex-col.w-full.min-h-screen {
            box-sizing: border-box;
            min-height: 100vh; 
            max-height: none;  
            overflow-y: visible; 
            padding-bottom: 2.5rem; 
        }
    
        .md\:hidden {
            display: none !important;
        }
    
        .bg-white.shadow.rounded-lg.w-full.p-3 {
            margin-bottom: 1rem;
        }
    }
    </style>

    @push('scripts')
    <script>
       let barChartInstance = null;
let lineChartInstance = null;
let currentRanges = {}; 

async function fetchSensorData() {
    try {
        const response = await fetch('{{ route("dashboard.latest") }}');
        if (!response.ok) {
            throw new Error('Error al obtener datos');
        }
        const data = await response.json();
        

        if (data.ranges) {
            currentRanges = data.ranges;
        }

        // Actualizar gauges con rangos dinámicos
        updateGauge('gaugeTempAire', data.tempAire, currentRanges.tempAire?.max || 50, '°C');
        updateGauge('gaugeHumedad', data.humAire, currentRanges.humAire?.max || 100, '%');
        updateGauge('gaugeTempAgua', data.tempAgua, currentRanges.tempAgua?.max || 50, '°C');
        updateGauge('gaugePH', data.ph, currentRanges.ph?.max || 14, '');
        updateGauge('gaugeNivel', data.nivelAgua, currentRanges.nivelAgua?.max || 100, ' cm');
        updateGauge('gaugeORP', data.orp, currentRanges.orp?.max || 100, 'mV');
        
        // Gauges móviles
        updateGauge('gaugeTempAireMobile', data.tempAire, currentRanges.tempAire?.max || 50, '°C');
        updateGauge('gaugeHumedadMobile', data.humAire, currentRanges.humAire?.max || 100, '%');
        updateGauge('gaugeTempAguaMobile', data.tempAgua, currentRanges.tempAgua?.max || 50, '°C');
        updateGauge('gaugePHMobile', data.ph, currentRanges.ph?.max || 14, '');
        updateGauge('gaugeNivelMobile', data.nivelAgua, currentRanges.nivelAgua?.max || 100, ' cm');
        updateGauge('gaugeORPMobile', data.orp, currentRanges.orp?.max || 100, 'mV');
    } catch (error) {
        console.error('Error fetching sensor data:', error);
    }
}

async function fetchChartData() {
    try {
        const response = await fetch('{{ route("dashboard.chart") }}');
        if (!response.ok) {
            throw new Error('Error al obtener datos de gráficas');
        }
        const data = await response.json();

        // Actualizar rangos si vienen en la respuesta de gráficas
        if (data.ranges) {
            currentRanges = data.ranges;
        }

        if (data.empty) {
            showChartsEmptyState();
        } else {
            hideChartsEmptyState();
            updateCharts(data);
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


    if (barChartInstance) {
        barChartInstance.destroy();
    }


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


    if (lineChartInstance) {
        lineChartInstance.destroy();
    }


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
}

function createGauge(id, value, max, unit) {
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
    if (value > max) {
        gaugeColor = isDark ? '#ef4444' : '#dc2626'; 
    } else if (value < (max * 0.2)) {
        gaugeColor = isDark ? '#f59e0b' : '#d97706'; 
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


    if (value > max) {
        ctx.fillStyle = isDark ? '#ef4444' : '#dc2626';
        ctx.font = 'bold 10px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('¡ALTO!', centerX, centerY + 25);
    } else if (value < (max * 0.2)) {
        ctx.fillStyle = isDark ? '#f59e0b' : '#d97706';
        ctx.font = 'bold 10px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('BAJO', centerX, centerY + 25);
    }
}

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

function initializeGauges() {
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

function updateGauge(id, value, max, unit) {
    const valueElement = document.getElementById(id + 'Value');
    if (valueElement) {
        valueElement.textContent = value.toFixed(1) + (unit ? unit : '');
    }
    

    const mobileValueElement = document.getElementById(id + 'ValueMobile');
    if (mobileValueElement) {
        mobileValueElement.textContent = value.toFixed(1) + (unit ? unit : '');
    }
    
    const canvas = document.getElementById(id);
    if (canvas) {
        createGauge(id, value, max, unit);
    }
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


document.addEventListener('DOMContentLoaded', () => {
    initializeGauges();
    fetchSensorData();
    fetchChartData();

    setInterval(() => {
        fetchSensorData();
        fetchChartData();
    }, 2000);
});

    </script>
    @endpush
</x-app-layout>