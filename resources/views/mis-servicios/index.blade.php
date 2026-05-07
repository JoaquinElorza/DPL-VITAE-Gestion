<x-layouts.app title="Mis Servicios Asignados">
    <div class="container mt-4">
        <h4 class="fw-bold mb-4"><i class="bx bx-list-check me-2"></i>Mis Servicios Asignados</h4>

        @if($servicios->isEmpty())
            <div class="alert alert-info border-0 shadow-sm">
                <i class="bx bx-info-circle me-2"></i>No tienes servicios asignados por el momento.
            </div>
        @else
            <div class="row">
                @foreach($servicios as $servicio)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0" style="border-left: 5px solid #696cff;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-label-primary">{{ $servicio->tipo }}</span>
                                    <small class="text-muted">{{ $servicio->fecha_hora?->format('d/m/Y H:i') ?? 'Sin fecha asignada' }}</small>
                                </div>
                                <h5 class="card-title fw-bold text-dark">Paciente: {{ $servicio->paciente?->nombre ?? 'No especificado' }}</h5>
                                <p class="card-text mb-2">
                                    <i class="bx bx-map me-1 text-danger"></i> 
                                    {{-- Aquí está la magia: le agregamos ?->nombre_colonia --}}
                                    <strong>Destino:</strong> {{ $servicio->paciente?->direccion?->colonia?->nombre_colonia ?? 'Ubicación pendiente' }}
                                </p>
                                <p class="card-text mb-3">
                                    <i class="bx bx-bus me-1 text-primary"></i> 
                                    <strong>Unidad:</strong> {{ $servicio->ambulancia?->numero_economico ?? 'Sin unidad' }}
                                </p>
                                <div class="d-grid">
                                    {{-- Aquí cambiamos el '#' por la ruta real --}}
                                    <a href="{{ route('mis-servicios.show', $servicio->id_servicio) }}" class="btn btn-outline-primary btn-sm">Ver detalles del traslado</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>