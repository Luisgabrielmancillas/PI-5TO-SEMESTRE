<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

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


    @stack('scripts')
</x-app-layout>
