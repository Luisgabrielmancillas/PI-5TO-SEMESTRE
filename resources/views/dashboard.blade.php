<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resumen') }}
        </h2>
    </x-slot>

    <div class="flex w-full" style="min-height: 100vh; max-height: 100vh; overflow: hidden;">
        <div class="w-full">
            <!-- Tarjetas superiores -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-6 mt-6 mx-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 text-center transition-colors">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold">Temperatura del aire</h5>
                    <h2 id="tempAireValue" class="text-2xl font-bold text-gray-900 dark:text-gray-100">21.2°C</h2>
                    <small id="tempAireTime" class="text-gray-500 dark:text-gray-400 text-xs">15/04 14:30:25</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 text-center transition-colors">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold">Humedad del aire</h5>
                    <h2 id="humAireValue" class="text-2xl font-bold text-gray-900 dark:text-gray-100">46.7%</h2>
                    <small id="humAireTime" class="text-gray-500 dark:text-gray-400 text-xs">15/04 14:30:25</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 text-center transition-colors">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold">Temperatura del agua</h5>
                    <h2 id="tempAguaValue" class="text-2xl font-bold text-gray-900 dark:text-gray-100">27.8°C</h2>
                    <small id="tempAguaTime" class="text-gray-500 dark:text-gray-400 text-xs">15/04 14:30:25</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 text-center transition-colors">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold">Nivel pH del agua</h5>
                    <h2 id="phValue" class="text-2xl font-bold text-gray-900 dark:text-gray-100">6.5</h2>
                    <small id="phTime" class="text-gray-500 dark:text-gray-400 text-xs">15/04 14:30:25</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 text-center transition-colors">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold">Nivel ORP</h5>
                    <h2 id="orpValue" class="text-2xl font-bold text-gray-900 dark:text-gray-100">16.9 mV</h2>
                    <small id="orpTime" class="text-gray-500 dark:text-gray-400 text-xs">15/04 14:30:25</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 text-center transition-colors">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold">Nivel del agua</h5>
                    <h2 id="nivelAguaValue" class="text-2xl font-bold text-gray-900 dark:text-gray-100">33.8</h2>
                    <small id="nivelAguaTime" class="text-gray-500 dark:text-gray-400 text-xs">15/04 14:30:25</small>
                </div>
            </div>

            <!-- Sección principal -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mt-0">
                <!-- Columna de gauges izquierda -->
                <div class="md:col-span-2 flex flex-col space-y-3">
                    <div class="space-y-3">
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeTempAire" class="mb-2"></canvas>
                            <div class="text-sm font-semibold">Temp. Aire</div>
                            <div id="gaugeTempAireValue" class="text-gray-600 text-sm">21.2°C</div>
                        </div>

                        <div class="flex flex-col items-center">
                            <canvas id="gaugeHumedad" class="mb-2"></canvas>
                            <div class="text-sm font-semibold">Humedad Aire</div>
                            <div id="gaugeHumedadValue" class="text-gray-600 text-sm">46.7%</div>
                        </div>

                        <div class="flex flex-col items-center">
                            <canvas id="gaugeTempAgua" class="mb-2"></canvas>
                            <div class="text-sm font-semibold">Temp. Agua</div>
                            <div id="gaugeTempAguaValue" class="text-gray-600 text-sm">27.8°C</div>
                        </div>
                    </div>
                </div>

                <!-- Columna central con gráficas -->
                <div class="md:col-span-8 flex flex-col items-center justify-center space-y-6">
                    <!-- Gráfica superior -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg w-full p-2 transition-colors">
                        <h6 class="text-center text-gray-700 dark:text-gray-200 font-semibold mb-1">Promedio de Mediciones</h6>
                        <div class="relative w-full h-52">
                            <canvas id="barChart" class="w-full h-full" style="max-height: 180px;"></canvas>
                        </div>
                    </div>

                    <!-- Gráfica inferior -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg w-full p-2 transition-colors">
                        <h6 class="text-center text-gray-700 dark:text-gray-200 font-semibold mb-2">Mediciones Recientes</h6>
                        <div class="relative w-full h-52">
                            <canvas id="lineChart" class="w-full h-full" style="max-height: 180px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha con gauges -->
                <div class="md:col-span-2 flex flex-col space-y-3">
                    <div class="space-y-3">
                        <div class="flex flex-col items-center">
                            <canvas id="gaugePH" class="mb-2"></canvas>
                            <div class="text-sm font-semibold">pH Agua</div>
                            <div id="gaugePHValue" class="text-gray-600 text-sm">6.5</div>
                        </div>
            
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeNivel" class="mb-2"></canvas>
                            <div class="text-sm font-semibold">Nivel Agua</div>
                            <div id="gaugeNivelValue" class="text-gray-600 text-sm">33.8</div>
                        </div>
            
                        <div class="flex flex-col items-center">
                            <canvas id="gaugeORP" class="mb-2"></canvas>
                            <div class="text-sm font-semibold">ORP</div>
                            <div id="gaugeORPValue" class="text-gray-600 text-sm">16.9 mV</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
        async function fetchSensorData() {
            try {
                const data = {
                    tempAire: 21.2,
                    humAire: 46.7,
                    tempAgua: 27.8,
                    ph: 6.5,
                    orp: 16.9,
                    nivelAgua: 33.8,
                    timestamp: '15/04 14:30:25'
                };
                document.getElementById('tempAireValue').textContent = data.tempAire.toFixed(1) + '°C';
                document.getElementById('humAireValue').textContent = data.humAire.toFixed(1) + '%';
                document.getElementById('tempAguaValue').textContent = data.tempAgua.toFixed(1) + '°C';
                document.getElementById('phValue').textContent = data.ph.toFixed(1);
                document.getElementById('orpValue').textContent = data.orp.toFixed(1) + ' mV';
                document.getElementById('nivelAguaValue').textContent = data.nivelAgua.toFixed(1);
                const timeElements = document.querySelectorAll('[id$="Time"]');
                timeElements.forEach(element => {
                    element.textContent = data.timestamp;
                });
                updateGauge('gaugeTempAire', data.tempAire, 50, '°C');
                updateGauge('gaugeHumedad', data.humAire, 100, '%');
                updateGauge('gaugeTempAgua', data.tempAgua, 50, '°C');
                updateGauge('gaugePH', data.ph, 14, '');
                updateGauge('gaugeNivel', data.nivelAgua, 100, '');
                updateGauge('gaugeORP', data.orp, 100, 'mV');
            } catch (error) {
                console.error('Error fetching sensor data:', error);
            }
        }

        function createGauge(id, value, max, unit) {
            const canvas = document.getElementById(id);
            const ctx = canvas.getContext('2d');
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = Math.min(centerX, centerY) - 8;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            // Detectar modo oscuro
            const isDark = document.documentElement.classList.contains('dark');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, isDark ? '#6366f1' : '#22d3ee');
            gradient.addColorStop(1, isDark ? '#22d3ee' : '#22c55e');
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0.25 * Math.PI, 1.75 * Math.PI);
            ctx.strokeStyle = isDark ? '#d1d5db' : '#2d2f48';
            ctx.lineWidth = 10;
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0.25 * Math.PI, 1.75 * Math.PI);
            ctx.strokeStyle = gradient;
            ctx.lineWidth = 8;
            ctx.stroke();
            const angle = 0.25 * Math.PI + (1.5 * Math.PI * (value / max));
            const markerRadius = radius - 4;
            const markerX = centerX + markerRadius * Math.cos(angle);
            const markerY = centerY + markerRadius * Math.sin(angle);
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.lineTo(markerX, markerY);
            ctx.strokeStyle = isDark ? '#facc15' : '#000000ff';
            ctx.lineWidth = 2;
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(markerX, markerY, 3, 0, 2 * Math.PI);
            ctx.fillStyle = isDark ? '#facc15' : '#000000ff';
            ctx.fill();
        }

        function initializeCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const axisColor = isDark ? '#d1d5db' : '#a0aec0';
            const gridColor = isDark ? '#374151' : '#2d3748';
            const legendColor = isDark ? '#d1d5db' : '#a0aec0';
            new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels: ['pH', 'CE', 'Temp Agua', 'Nivel Agua'],
                    datasets: [{
                        label: 'Valor promedio',
                        data: [6.59, 1.24, 22.72, 50],
                        backgroundColor: isDark ? ['#6366f1', '#d946ef', '#facc15', '#22d3ee'] : ['#22d3ee', '#d946ef', '#facc15', '#22c55e']
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

            new Chart(document.getElementById('lineChart'), {
                type: 'line',
                data: {
                    labels: ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
                    datasets: [
                        { 
                            label: 'pH', 
                            data: [6.3,6.6,6.5,6.7,6.5,6.4,6.6], 
                            borderColor: isDark ? '#6366f1' : '#22d3ee', 
                            borderWidth: 2,
                            tension: 0.3,
                            fill: false 
                        },
                        { 
                            label: 'CE', 
                            data: [1.2,1.3,1.4,1.1,1.2,1.3,1.2], 
                            borderColor: '#d946ef', 
                            borderWidth: 2,
                            tension: 0.3,
                            fill: false 
                        },
                        { 
                            label: 'Temp. Agua', 
                            data: [22,23,22,23,24,22,23], 
                            borderColor: '#facc15', 
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

        const gaugeConfigs = {
            'gaugeTempAire': { value: 21.2, max: 50, unit: '°C' },
            'gaugeHumedad': { value: 46.7, max: 100, unit: '%' },
            'gaugeTempAgua': { value: 27.8, max: 50, unit: '°C' },
            'gaugePH': { value: 6.5, max: 14, unit: '' },
            'gaugeNivel': { value: 33.8, max: 100, unit: '' },
            'gaugeORP': { value: 16.9, max: 100, unit: 'mV' }
        };

        function initializeGauges() {
            Object.keys(gaugeConfigs).forEach(id => {
                const config = gaugeConfigs[id];
                const canvas = document.getElementById(id);
                canvas.width = 100;
                canvas.height = 100;
                createGauge(id, config.value, config.max, config.unit);
            });
        }

        function updateGauge(id, value, max, unit) {
            document.getElementById(id + 'Value').textContent = 
                value.toFixed(1) + (unit ? unit : '');
            createGauge(id, value, max, unit);
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeGauges();
            initializeCharts();
            fetchSensorData();
            setInterval(fetchSensorData, 5000);
            // Actualizar colores al cambiar modo
            const observer = new MutationObserver(() => {
                initializeGauges();
                initializeCharts();
            });
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        });
    </script>
    @endpush
</x-app-layout>
