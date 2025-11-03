<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resumen') }}
        </h2>
    </x-slot>

    <div class="flex flex-col w-full min-h-screen overflow-x-hidden overflow-y-auto">
        <div class="w-full px-3 sm:px-6 lg:px-8 py-4">
            <!-- Tarjetas superiores -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 sm:p-4 text-center">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold text-sm sm:text-base">Temperatura del aire</h5>
                    <h2 id="tempAireValue" class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">--°C</h2>
                    <small id="tempAireTime" class="text-gray-500 dark:text-gray-400 text-xs">--/-- --:--:--</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 sm:p-4 text-center">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold text-sm sm:text-base">Humedad del aire</h5>
                    <h2 id="humAireValue" class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">--%</h2>
                    <small id="humAireTime" class="text-gray-500 dark:text-gray-400 text-xs">--/-- --:--:--</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 sm:p-4 text-center">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold text-sm sm:text-base">Temperatura del agua</h5>
                    <h2 id="tempAguaValue" class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">--°C</h2>
                    <small id="tempAguaTime" class="text-gray-500 dark:text-gray-400 text-xs">--/-- --:--:--</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 sm:p-4 text-center">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold text-sm sm:text-base">Nivel pH del agua</h5>
                    <h2 id="phValue" class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">--</h2>
                    <small id="phTime" class="text-gray-500 dark:text-gray-400 text-xs">--/-- --:--:--</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 sm:p-4 text-center">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold text-sm sm:text-base">Nivel del agua</h5>
                    <h2 id="nivelAguaValue" class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">--</h2>
                    <small id="nivelAguaTime" class="text-gray-500 dark:text-gray-400 text-xs">--/-- --:--:--</small>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 sm:p-4 text-center">
                    <h5 class="text-gray-700 dark:text-gray-200 font-semibold text-sm sm:text-base">Nivel ORP</h5>
                    <h2 id="orpValue" class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">-- mV</h2>
                    <small id="orpTime" class="text-gray-500 dark:text-gray-400 text-xs">--/-- --:--:--</small>
                </div>
            </div>

            <!-- Sección principal -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Columna izquierda -->
                <div class="md:col-span-3 lg:col-span-2 flex flex-col gauges-col space-y-4">
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

                <!-- Columna central -->
                <div class="md:col-span-6 lg:col-span-8 flex flex-col items-center justify-center space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg w-full p-3">
                        <h6 class="text-center text-gray-700 dark:text-gray-200 font-semibold mb-2 text-sm sm:text-base">Promedio de Mediciones</h6>
                        <div class="relative w-full h-48 sm:h-64 lg:h-72">
                            <canvas id="barChart" class="w-full h-full"></canvas>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg w-full p-3">
                        <h6 class="text-center text-gray-700 dark:text-gray-200 font-semibold mb-2 text-sm sm:text-base">Mediciones Recientes</h6>
                        <div class="relative w-full h-48 sm:h-64 lg:h-72">
                            <canvas id="lineChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="md:col-span-3 lg:col-span-2 flex flex-col gauges-col space-y-4">
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
        </div>
    </div>

    {{-- Estilos responsivos adicionales --}}
    <style>
        @media (max-width: 768px) {
            /* En pantallas pequeñas, los gauges se apilan centrados */
            .gauges-col {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }
            .gauges-col > div {
                width: 100%;
                display: flex;
                justify-content: center;
            }
        }
    </style>
    @push('scripts')
    <script>
        
        let barChartInstance = null;
        let lineChartInstance = null;

        async function fetchSensorData() {
            try {
                const response = await fetch('{{ route("dashboard.latest") }}');
                if (!response.ok) {
                    throw new Error('Error al obtener datos');
                }
                const data = await response.json();
                
                
                document.getElementById('tempAireValue').textContent = data.tempAire.toFixed(1) + '°C';
                document.getElementById('humAireValue').textContent = data.humAire.toFixed(1) + '%';
                document.getElementById('tempAguaValue').textContent = data.tempAgua.toFixed(1) + '°C';
                document.getElementById('phValue').textContent = data.ph.toFixed(1);
                document.getElementById('orpValue').textContent = data.orp.toFixed(1) + ' mV';
                document.getElementById('nivelAguaValue').textContent = data.nivelAgua.toFixed(1) + ' cm';
                
                
                const timeElements = document.querySelectorAll('[id$="Time"]');
                timeElements.forEach(element => {
                    element.textContent = data.timestamp;
                });

            
                updateGauge('gaugeTempAire', data.tempAire, 50, '°C');
                updateGauge('gaugeHumedad', data.humAire, 100, '%');
                updateGauge('gaugeTempAgua', data.tempAgua, 50, '°C');
                updateGauge('gaugePH', data.ph, 14, '');
                updateGauge('gaugeNivel', data.nivelAgua, 100, ' cm');
                updateGauge('gaugeORP', data.orp, 100, 'mV');
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
                
                updateCharts(data);
                
            } catch (error) {
                console.error('Error fetching chart data:', error);
            }
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
                    labels: ['pH', 'ORP', 'Temp Agua', 'Nivel Agua'],
                    datasets: [{
                        label: 'Valor promedio',
                        data: [
                            chartData.averages.ph,
                            chartData.averages.ce,
                            chartData.averages.tempAgua,
                            chartData.averages.nivel
                        ],
                        backgroundColor: isDark ? 
                            ['#6366f1', '#d946ef', '#facc15', '#22d3ee'] : 
                            ['#22d3ee', '#d946ef', '#facc15', '#22c55e']
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
                            borderColor: isDark ? '#6366f1' : '#22d3ee', 
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
            const ctx = canvas.getContext('2d');
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = Math.min(centerX, centerY) - 8;
        
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        
            // 🔹 Aseguramos que el valor no sobrepase el máximo
            const safeValue = Math.min(value, max);
        
            const isDark = document.documentElement.classList.contains('dark');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, isDark ? '#6366f1' : '#22d3ee');
            gradient.addColorStop(1, isDark ? '#22d3ee' : '#22c55e');
        
            const startAngle = 0.75 * Math.PI;  // 0.25π + 0.5π
            const endAngle   = 2.25 * Math.PI;  // 1.75π + 0.5π
            const angleRange = endAngle - startAngle;
        
            // Fondo del gauge
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, startAngle, endAngle);
            ctx.strokeStyle = isDark ? '#d1d5db' : '#2d2f48';
            ctx.lineWidth = 10;
            ctx.stroke();
        
            // Relleno proporcional al valor (mantiene tamaño original)
            const fillAngle = startAngle + (angleRange * (safeValue / max));
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, startAngle, fillAngle);
            ctx.strokeStyle = gradient;
            ctx.lineWidth = 8;
            ctx.stroke();
        
            // Marcador
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
        }




        const gaugeConfigs = {
            'gaugeTempAire': { value: 0, max: 50, unit: '°C' },
            'gaugeHumedad': { value: 0, max: 100, unit: '%' },
            'gaugeTempAgua': { value: 0, max: 50, unit: '°C' },
            'gaugePH': { value: 0, max: 14, unit: '' },
            'gaugeNivel': { value: 0, max: 100, unit: '' },
            'gaugeORP': { value: 0, max: 100, unit: 'mV' }
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
