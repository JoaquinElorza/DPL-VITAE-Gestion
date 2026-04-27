<x-layouts.app title="Dashboard Analítico">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="container mt-4">
        <h2 class="mb-4">Dashboard Analítico - DPL Vitae</h2>

        <div class="row">
            <div class="col-md-3">
                <div class="card text-white mb-4" style="background-color: #696CFF;">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Servicios Útiles</h5>
                        <h3 class="text-white">{{ $totalServicios }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white mb-4" style="background-color: #10B981;">
                    <div class="card-body">
                        <h5 class="card-title text-white">Ingresos Totales</h5>
                        <h3 class="text-white">${{ number_format($ingresosTotales, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white mb-4" style="background-color: #0EA5E9;">
                    <div class="card-body">
                        <h5 class="card-title text-white">Ticket Promedio</h5>
                        <h3 class="text-white">${{ number_format($promedioCosto, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white mb-4" style="background-color: #F43F5E;">
                    <div class="card-body">
                        <h5 class="card-title text-white">Punto Caliente (Colonia)</h5>
                        <h3 class="text-white">ID: {{ $coloniaFrecuente ?? 'N/A' }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Distribución por Tipo de Servicio</h5>
                    </div>
                    <div class="card-body" style="position: relative; height:350px; width:100%; display: flex; justify-content: center;">
                        <canvas id="graficaServicios"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Mapa de Puntos Calientes (Oaxaca)</h5>
                    </div>
                    <div class="card-body">
                        <div id="mapaEmergencias" style="height: 310px; width: 100%; border-radius: 8px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        function inicializarDashboard() {
            const canvaElement = document.getElementById('graficaServicios');
            if (canvaElement) {
                const ctx = canvaElement.getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($labelsGrafica) !!},
                        datasets: [{
                            data: {!! json_encode($valoresGrafica) !!},
                            backgroundColor: ['#696CFF', '#10B981', '#0EA5E9', '#F43F5E'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }

            const mapElement = document.getElementById('mapaEmergencias');
            if (mapElement && !mapElement._leaflet_id) {
                const map = L.map('mapaEmergencias').setView([{{ $lat }}, {{ $lng }}], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                
                L.circle([{{ $lat }}, {{ $lng }}], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 500
                }).addTo(map).bindPopup('<b>Punto Caliente</b><br>Colonia ID: {{ $coloniaFrecuente }}');
            }
        }

        document.addEventListener('DOMContentLoaded', inicializarDashboard);
        document.addEventListener('livewire:navigated', inicializarDashboard);
    </script>
</x-layouts.app>