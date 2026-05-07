<x-layouts.app title="Detalles del Servicio">
    <div class="container mt-4">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('mis-servicios.index') }}" class="btn btn-icon btn-outline-secondary">
                <i class="bx bx-arrow-back"></i>
            </a>
            <h4 class="fw-bold mb-0">Detalles del Servicio #{{ $servicio->id_servicio }}</h4>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title text-white mb-0"><i class="bx bx-info-circle me-2"></i>Información General</h5>
                    </div>
                    <div class="card-body mt-3">
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <strong>Paciente:</strong>
                                <p>{{ $servicio->paciente?->nombre ?? 'N/A' }} {{ $servicio->paciente?->ap_paterno ?? '' }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <strong>Fecha y Hora:</strong>
                                <p>{{ $servicio->fecha_hora?->format('d/m/Y h:i A') ?? 'Pendiente' }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <strong>Destino (Colonia):</strong>
                                <p>{{ $servicio->paciente?->direccion?->colonia?->nombre_colonia ?? 'No especificada' }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <strong>Estado actual:</strong>
                                <p><span class="badge bg-label-info">{{ $servicio->estado }}</span></p>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <strong>Observaciones:</strong>
                            <p class="text-muted mt-1">{{ $servicio->observaciones ?? 'Sin observaciones registradas.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bx bx-bus me-2 text-primary"></i>Unidad Asignada</h6>
                        <p class="mb-0">{{ $servicio->ambulancia?->numero_economico ?? 'Sin unidad' }}</p>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bx bx-group me-2 text-success"></i>Personal a Bordo</h6>
                        <ul class="list-unstyled mb-0 mt-3">
                            <li class="mb-2">
                                <strong>Operador:</strong> 
                                {{ $servicio->operador?->usuario?->nombre ?? 'Pendiente' }}
                            </li>
                            <li>
                                <strong>Paramédico(s):</strong><br>
                                @forelse($servicio->paramedicos as $paramedico)
                                    - {{ $paramedico->usuario?->nombre ?? 'N/A' }} <br>
                                @empty
                                    <span class="text-muted">Ninguno asignado</span>
                                @endforelse
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>