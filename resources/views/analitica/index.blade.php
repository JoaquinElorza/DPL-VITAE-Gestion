<x-layouts.app title="Dashboard Analítico">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --dpl-primary: #BA55D3;
            --dpl-primary-hover: #5f61e6;
        }

        .titulo-morado {
            color: #ffffff !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 
                -1px -1px 0 #BA55D3,  
                 1px -1px 0 #BA55D3,
                -1px  1px 0 #BA55D3,
                 1px  1px 0 #BA55D3,
                 0px  0px 6px #BA55D3,
                 0px  0px 12px rgba(105, 108, 255, 0.8),
                 2px  2px 4px rgba(0, 0, 0, 0.25);
        }
    </style>

    <div class="container-fluid mt-4">
        <h2 class="mb-4 titulo-morado">Dashboard Analítico - DPL Vitae</h2>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="card text-white h-100 shadow-sm" style="background-color: #BA55D3;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="text-white mb-2 fw-bold text-uppercase">Total Servicios Útiles</h6>
                        <h2 class="text-white mb-0 fw-bold">{{ $totalServicios }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white h-100 shadow-sm" style="background-color: #191970;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="text-white mb-2 fw-bold text-uppercase">Ingresos Totales</h6>
                        <h2 class="text-white mb-0 fw-bold">${{ number_format($ingresosTotales, 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white h-100 shadow-sm" style="background-color: #F08080;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="text-white mb-2 fw-bold text-uppercase">Ticket Promedio</h6>
                        <h2 class="text-white mb-0 fw-bold">${{ number_format($promedioCosto, 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm" style="background-color: #FFFFFF; border: 2px solid #191970;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="mb-2 fw-bold text-uppercase" style="color: #191970;">Punto Caliente</h6>
                        <h2 class="mb-0 fw-bold" style="color: #191970;">ID: {{ $coloniaFrecuente ?? 'N/A' }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado">Distribución por Tipo (Global)</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:320px; width:100%;">
                        <canvas id="graficaServicios"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado">Mapa de Puntos Calientes (Oaxaca)</h5>
                    </div>
                    <div class="card-body p-2">
                        <div id="mapaEmergencias" style="height: 320px; width: 100%; border-radius: 8px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado">Ingresos (Mensual)</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaMeses"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado">Volumen de Servicios (Por Día)</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaDias"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado">Lugares con Más Demanda (Top Colonias)</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaLugares"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado">Estado Actual de los Servicios (Global)</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaEstados"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- NUEVA SECCIÓN DE IA -->
        <hr class="my-5" style="border-color: rgba(186, 85, 211, 0.3);">
        <h3 class="mb-4 titulo-morado"><i class='bx bx-brain bx-tada me-2' style="color: #FF7F50;"></i>Inteligencia de Precios y Cotizaciones (IA)</h3>
        <!-- KPIs IA -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #FF9A76 0%, #FF7F50 100%); border: none;">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class='bx bx-filter-alt fs-3 text-white'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px; color: rgba(255,255,255,0.9);">Outliers Detectados</h6>
                            <h4 class="mb-0 fw-bold text-white">{{ number_format($aiOutliers) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #B96BFF 0%, #8A2BE2 100%); border: none;">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class='bx bx-target-lock fs-3 text-white'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px; color: rgba(255,255,255,0.9);">Precisión (R²)</h6>
                            <h4 class="mb-0 fw-bold text-white">{{ $aiPrecision }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #3A3A98 0%, #191970 100%); border: none;">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class='bx bx-data fs-3 text-white'></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px; color: rgba(255,255,255,0.9);">Traslados Analizados</h6>
                            <h4 class="mb-0 fw-bold text-white">{{ number_format($aiTrasladosAnalizados) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid #FF7F50;">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado fs-6">Evolución Precio Promedio (Por Día)</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaPrecioPromedio"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid #0EA5E9;">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado fs-6">Costo Promedio por Tipo de Ambulancia</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaCostoAmbulancia"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid #10B981;">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado fs-6">Distancia vs Precio Cotizado (IA)</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaDistanciaPrecio"></canvas>
                    </div>
                </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid #8B5CF6;">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado fs-6">Tasa de Conversión (Predicción de Éxito)</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaConversion"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid #EC4899;">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="titulo-morado fs-6">Impacto de Factores en el Precio</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:300px; width:100%;">
                        <canvas id="graficaFactores"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        function inicializarDashboard() {
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            };

            const ctxServicios = document.getElementById('graficaServicios')?.getContext('2d');
            if (ctxServicios) {
                new Chart(ctxServicios, {
                    type: 'doughnut',
                    plugins: [ChartDataLabels],
                    data: {
                        labels: {!! json_encode($labelsGrafica) !!},
                        datasets: [{
                            data: {!! json_encode($valoresGrafica) !!},
                            backgroundColor: ['#696CFF', '#10B981', '#0EA5E9', '#F43F5E'],
                            borderWidth: 2, borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        ...chartOptions,
                        plugins: {
                            ...chartOptions.plugins,
                            datalabels: {
                                color: '#fff', font: { weight: 'bold', size: 12 },
                                formatter: (value, ctx) => {
                                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    return (value * 100 / sum).toFixed(1) + "%";
                                }
                            }
                        }
                    }
                });
            }

            const ctxMeses = document.getElementById('graficaMeses')?.getContext('2d');
            if (ctxMeses) {
                new Chart(ctxMeses, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labelsMeses) !!}.map(m => 'Mes ' + m),
                        datasets: [{
                            label: 'Ingresos Totales ($)',
                            data: {!! json_encode($valoresMeses) !!},
                            borderColor: '#10B981', backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            fill: true, tension: 0.4, pointBackgroundColor: '#10B981'
                        }]
                    },
                    options: { ...chartOptions, scales: { y: { beginAtZero: true } } }
                });
            }

            const ctxDias = document.getElementById('graficaDias')?.getContext('2d');
            if (ctxDias) {
                new Chart(ctxDias, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labelsDias) !!},
                        datasets: [{
                            label: 'Servicios por Día',
                            data: {!! json_encode($valoresDias) !!},
                            borderColor: '#FF9F43', backgroundColor: 'rgba(255, 159, 67, 0.2)',
                            fill: true, tension: 0.3, pointBackgroundColor: '#FF9F43'
                        }]
                    },
                    options: { ...chartOptions, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                });
            }

            const ctxLugares = document.getElementById('graficaLugares')?.getContext('2d');
            if (ctxLugares) {
                new Chart(ctxLugares, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($labelsLugares) !!},
                        datasets: [{
                            label: 'Demanda de Ambulancias',
                            data: {!! json_encode($valoresLugares) !!},
                            backgroundColor: '#F43F5E', borderRadius: 4
                        }]
                    },
                    options: { ...chartOptions, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                });
            }

            const ctxEstados = document.getElementById('graficaEstados')?.getContext('2d');
            if (ctxEstados) {
                new Chart(ctxEstados, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($labelsEstados) !!},
                        datasets: [{
                            label: 'Cantidad',
                            data: {!! json_encode($valoresEstados) !!},
                            backgroundColor: '#0EA5E9', borderRadius: 4
                        }]
                    },
                    options: { ...chartOptions, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                });
            }

            const mapElement = document.getElementById('mapaEmergencias');
            if (mapElement && !mapElement._leaflet_id) {
                const map = L.map('mapaEmergencias').setView([{{ $lat }}, {{ $lng }}], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                L.circle([{{ $lat }}, {{ $lng }}], {
                    color: 'red', fillColor: '#f03', fillOpacity: 0.5, radius: 500
                }).addTo(map).bindPopup('<b>Punto Caliente Principal</b><br>Colonia ID: {{ $coloniaFrecuente }}');
            }

            // --- GRÁFICAS DE INTELIGENCIA DE PRECIOS (IA) ---

            const ctxPrecioProm = document.getElementById('graficaPrecioPromedio')?.getContext('2d');
            if (ctxPrecioProm) {
                new Chart(ctxPrecioProm, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labelsPrecioProm) !!},
                        datasets: [{
                            label: 'Precio Promedio Cotizado ($)',
                            data: {!! json_encode($valoresPrecioProm) !!},
                            borderColor: '#FF7F50', backgroundColor: 'rgba(255, 127, 80, 0.2)',
                            fill: true, tension: 0.4, pointBackgroundColor: '#FF7F50'
                        }]
                    },
                    options: { ...chartOptions, scales: { y: { beginAtZero: true } } }
                });
            }

            const ctxCostoAmb = document.getElementById('graficaCostoAmbulancia')?.getContext('2d');
            if (ctxCostoAmb) {
                new Chart(ctxCostoAmb, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($labelsCostoAmb) !!},
                        datasets: [{
                            label: 'Costo Promedio ($)',
                            data: {!! json_encode($valoresCostoAmb) !!},
                            backgroundColor: ['#0EA5E9', '#3B82F6', '#8B5CF6', '#EC4899'],
                            borderRadius: 4
                        }]
                    },
                    options: { ...chartOptions, scales: { y: { beginAtZero: true } } }
                });
            }

            const ctxDistanciaPrecio = document.getElementById('graficaDistanciaPrecio')?.getContext('2d');
            if (ctxDistanciaPrecio) {
                new Chart(ctxDistanciaPrecio, {
                    type: 'scatter',
                    data: {
                        datasets: [{
                            label: 'Cotizaciones (Distancia, Precio)',
                            data: {!! json_encode($distanciaVsPrecio) !!},
                            backgroundColor: '#10B981',
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: { 
                        ...chartOptions, 
                        scales: { 
                            x: { title: { display: true, text: 'Distancia (km)' }, beginAtZero: true },
                            y: { title: { display: true, text: 'Precio Sugerido ($)' }, beginAtZero: true }
                        } 
                    }
                });
            }

            const ctxConversion = document.getElementById('graficaConversion')?.getContext('2d');
            if (ctxConversion) {
                new Chart(ctxConversion, {
                    type: 'doughnut',
                    plugins: [ChartDataLabels],
                    data: {
                        labels: {!! json_encode($labelsConversion) !!},
                        datasets: [{
                            data: {!! json_encode($valoresConversion) !!},
                            backgroundColor: ['#10B981', '#F43F5E'],
                            borderWidth: 2, borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        ...chartOptions,
                        plugins: {
                            ...chartOptions.plugins,
                            datalabels: {
                                color: '#fff', font: { weight: 'bold', size: 12 },
                                formatter: (value, ctx) => {
                                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    if (sum === 0) return '0%';
                                    return (value * 100 / sum).toFixed(1) + "%";
                                }
                            }
                        }
                    }
                });
            }

            const ctxFactores = document.getElementById('graficaFactores')?.getContext('2d');
            if (ctxFactores) {
                new Chart(ctxFactores, {
                    type: 'radar',
                    data: {
                        labels: {!! json_encode($labelsFactores) !!},
                        datasets: [{
                            label: 'Costo Total Acumulado ($)',
                            data: {!! json_encode($valoresFactores) !!},
                            backgroundColor: 'rgba(236, 72, 153, 0.2)',
                            borderColor: '#EC4899',
                            pointBackgroundColor: '#EC4899',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#EC4899'
                        }]
                    },
                    options: {
                        ...chartOptions,
                        scales: {
                            r: {
                                angleLines: { display: true },
                                suggestedMin: 0
                            }
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', inicializarDashboard);
        document.addEventListener('livewire:navigated', inicializarDashboard);
    </script>
</x-layouts.app>