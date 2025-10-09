@extends('layouts.app')

@section('title', 'Dashboard | Hydrobox')

@section('content')
<div class="d-flex">
    <div class="sidebar">
        <div class="logo mb-4 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Hydrobox Logo">
        </div>
        <h6>Vistas</h6>
        <nav class="nav flex-column">
            <a class="nav-link active" href="#"><i class="bi bi-house-door me-2"></i>Inicio</a>
            <a class="nav-link" href="#"><i class="bi bi-toggle-on me-2"></i>Actuadores</a>
            <a class="nav-link" href="#"><i class="bi bi-graph-up me-2"></i>Sensores</a>
            <a class="nav-link" href="#"><i class="bi bi-clock-history me-2"></i>Historial</a>
            <a class="nav-link" href="#"><i class="bi bi-people me-2"></i>Gestionar usuarios</a>
        </nav>

        <h6 class="mt-4">Configuración</h6>
        <nav class="nav flex-column">
            <a class="nav-link" href="#"><i class="bi bi-flower1 me-2"></i>Hortalizas</a>
            <a class="nav-link" href="#"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a>
        </nav>
    </div>

    <div class="content w-100">
        <div class="row g-2 mb-3">
            <div class="col-md-2">
                <div class="card p-2 text-center">
                    <h5>Temperatura del aire</h5>
                    <h2 id="tempAireValue">21.2°C</h2>
                    <small id="tempAireTime">15/04 14:30:25</small>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card p-2 text-center">
                    <h5>Humedad del aire</h5>
                    <h2 id="humAireValue">46.7%</h2>
                    <small id="humAireTime">15/04 14:30:25</small>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card p-2 text-center">
                    <h5>Temperatura del agua</h5>
                    <h2 id="tempAguaValue">27.8°C</h2>
                    <small id="tempAguaTime">15/04 14:30:25</small>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card p-2 text-center">
                    <h5>Nivel pH del agua</h5>
                    <h2 id="phValue">6.5</h2>
                    <small id="phTime">15/04 14:30:25</small>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card p-2 text-center">
                    <h5>Nivel ORP</h5>
                    <h2 id="orpValue">16.9 mV</h2>
                    <small id="orpTime">15/04 14:30:25</small>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card p-2 text-center">
                    <h5>Nivel del agua</h5>
                    <h2 id="nivelAguaValue">33.8</h2>
                    <small id="nivelAguaTime">15/04 14:30:25</small>
                </div>
            </div>
        </div>

        <div class="row g-3 main-content-row">
            <div class="col-md-2 gauges-column">
                <div class="gauges-section">
                    <div class="gauge-container">
                        <div class="gauge">
                            <canvas id="gaugeTempAire"></canvas>
                            <div class="gauge-name">Temp. Aire</div>
                            <div class="gauge-value" id="gaugeTempAireValue">21.2°C</div>
                        </div>
                    </div>
                    <div class="gauge-container">
                        <div class="gauge">
                            <canvas id="gaugeHumedad"></canvas>
                            <div class="gauge-name">Humedad Aire</div>
                            <div class="gauge-value" id="gaugeHumedadValue">46.7%</div>
                        </div>
                    </div>
                    <div class="gauge-container">
                        <div class="gauge">
                            <canvas id="gaugeTempAgua"></canvas>
                            <div class="gauge-name">Temp. Agua</div>
                            <div class="gauge-value" id="gaugeTempAguaValue">27.8°C</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 charts-column d-flex flex-column align-items-center justify-content-center">
                <div class="card" style="width: 100%;">
                    <h6 class="text-center mb-0">Promedio de Mediciones</h6>
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-2 camera-column">
                <div class="card p-3 text-center mb-0">
                    <h6 class="mb-0">Vista Cámara</h6>
                    <div class="camera-placeholder">
                        Cámara en Línea
                    </div>
                </div>
                <div class="gauges-section">
                    <div class="gauge-container">
                        <div class="gauge">
                            <canvas id="gaugePH"></canvas>
                            <div class="gauge-name">pH Agua</div>
                            <div class="gauge-value" id="gaugePHValue">6.5</div>
                        </div>
                    </div>
                    <div class="gauge-container">
                        <div class="gauge">
                            <canvas id="gaugeNivel"></canvas>
                            <div class="gauge-name">Nivel Agua</div>
                            <div class="gauge-value" id="gaugeNivelValue">33.8</div>
                        </div>
                    </div>
                    <div class="gauge-container">
                        <div class="gauge">
                            <canvas id="gaugeORP"></canvas>
                            <div class="gauge-name">ORP</div>
                            <div class="gauge-value" id="gaugeORPValue">16.9 mV</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <h6 class="text-center mb-0">Mediciones Recientes</h6>
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradient.addColorStop(0, '#22d3ee');
        gradient.addColorStop(1, '#22c55e');
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0.25 * Math.PI, 1.75 * Math.PI);
        ctx.strokeStyle = '#2d2f48';
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
        ctx.strokeStyle = '#ffffff';
        ctx.lineWidth = 2;
        ctx.stroke();
        ctx.beginPath();
        ctx.arc(markerX, markerY, 3, 0, 2 * Math.PI);
        ctx.fillStyle = '#ffffff';
        ctx.fill();
    }

    function initializeCharts() {
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: ['pH', 'CE', 'Temp Agua', 'Nivel Agua'],
                datasets: [{
                    label: 'Valor promedio',
                    data: [6.59, 1.24, 22.72, 50],
                    backgroundColor: ['#22d3ee', '#d946ef', '#facc15', '#22c55e']
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    y: { 
                        beginAtZero: true,
                        ticks: { color: '#a0aec0', font: { size: 10 } },
                        grid: { color: '#2d3748' }
                    },
                    x: { 
                        ticks: { color: '#a0aec0', font: { size: 10 } },
                        grid: { color: '#2d3748' }
                    }
                },
                plugins: {
                    legend: { 
                        labels: { color: '#a0aec0', font: { size: 11 } }
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
                        borderColor: '#22d3ee', 
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
                        ticks: { color: '#a0aec0', font: { size: 10 } },
                        grid: { color: '#2d3748' }
                    },
                    x: { 
                        ticks: { color: '#a0aec0', font: { size: 10 } },
                        grid: { color: '#2d3748' }
                    }
                },
                plugins: {
                    legend: { 
                        labels: { color: '#a0aec0', font: { size: 11 } }
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
    });
</script>
@endpush