<x-layouts.app title="Mis Servicios Asignados">
    <div class="container mt-4">
        <h4 class="fw-bold mb-4" style="background: linear-gradient(135deg, #191970, #BA55D3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            <i class="bx bx-list-check me-2" style="-webkit-text-fill-color: #191970;"></i>Mis Servicios Asignados
        </h4>

        @if($servicios->isEmpty())
            <div class="alert alert-info border-0 shadow-sm rounded-4">
                <i class="bx bx-info-circle me-2"></i>No tienes servicios asignados por el momento.
            </div>
        @else
            <div class="row g-4">
                @foreach($servicios as $servicio)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 border-0 rounded-4" style="transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(186, 85, 211, 0.05);" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 25px rgba(186, 85, 211, 0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 15px rgba(186, 85, 211, 0.05)';">
                            <div class="card-header bg-transparent border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                                <span class="badge px-3 py-2" style="background-color: rgba(138, 43, 226, 0.1); color: #8A2BE2; border-radius: 50rem;">
                                    <i class="bx bx-run me-1"></i>{{ $servicio->tipo }}
                                </span>
                                <span class="text-muted small fw-semibold">
                                    <i class="bx bx-time-five me-1"></i>{{ $servicio->fecha_hora?->format('d/m/Y H:i') ?? 'Sin fecha asignada' }}
                                </span>
                            </div>
                            <div class="card-body pt-3 d-flex flex-column">
                                <h5 class="card-title fw-bold text-dark mb-4 d-flex align-items-center">
                                    <i class="bx bx-user-circle fs-3 me-2" style="color: #BA55D3;"></i>
                                    {{ $servicio->paciente?->nombre ?? 'Paciente no especificado' }}
                                </h5>
                                
                                <div class="d-flex flex-column gap-3 mb-4 flex-grow-1">
                                    <div class="d-flex align-items-start">
                                        <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(240, 128, 128, 0.15);">
                                            <i class="bx bx-map fs-5" style="color: #F08080;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Destino</small>
                                            <span class="fw-medium text-dark">{{ $servicio->paciente?->direccion?->colonia?->nombre_colonia ?? 'Ubicación pendiente' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-start">
                                        <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(138, 43, 226, 0.1);">
                                            <i class="bx bx-bus fs-5" style="color: #8A2BE2;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Unidad Asignada</small>
                                            <span class="fw-medium text-dark">{{ $servicio->ambulancia?->numero_economico ?? 'Sin unidad' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid mt-auto">
                                    <a href="{{ route('mis-servicios.show', $servicio->id_servicio) }}" class="btn btn-warning py-2" style="border-radius: 50rem;">
                                        <i class="bx bx-right-arrow-alt me-1"></i>Ver detalles del traslado
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>