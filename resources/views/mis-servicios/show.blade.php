<x-layouts.app title="Detalles del Servicio">
    <div class="container mt-4">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('mis-servicios.index') }}" class="btn btn-icon btn-outline-secondary rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="bx bx-arrow-back fs-4"></i>
            </a>
            <h4 class="fw-bold mb-0" style="background: linear-gradient(135deg, #191970, #BA55D3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Detalles del Servicio <span class="badge px-3 py-2 ms-2 align-middle" style="background-color: rgba(138, 43, 226, 0.1); color: #8A2BE2; border-radius: 50rem; font-size: 0.9rem;">#{{ $servicio->id_servicio }}</span>
            </h4>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4" style="transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 12px 25px rgba(186, 85, 211, 0.1)';" onmouseout="this.style.boxShadow='0 4px 15px rgba(186, 85, 211, 0.05)';">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h5 class="card-title fw-bold text-dark mb-0 d-flex align-items-center">
                            <i class="bx bx-info-circle fs-4 me-2" style="color: #BA55D3;"></i>Información General
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-4">
                            <!-- Paciente -->
                            <div class="col-sm-6">
                                <div class="d-flex align-items-start">
                                    <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(186, 85, 211, 0.1);">
                                        <i class="bx bx-user fs-5" style="color: #BA55D3;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Paciente</small>
                                        <span class="fw-semibold text-dark fs-6">{{ $servicio->paciente?->nombre ?? 'N/A' }} {{ $servicio->paciente?->ap_paterno ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Fecha y Hora -->
                            <div class="col-sm-6">
                                <div class="d-flex align-items-start">
                                    <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(57, 51, 149, 0.1);">
                                        <i class="bx bx-calendar fs-5" style="color: #393395;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Fecha y Hora</small>
                                        <span class="fw-semibold text-dark fs-6">{{ $servicio->fecha_hora?->format('d/m/Y h:i A') ?? 'Pendiente' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Destino -->
                            <div class="col-sm-6">
                                <div class="d-flex align-items-start">
                                    <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(240, 128, 128, 0.15);">
                                        <i class="bx bx-map fs-5" style="color: #F08080;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Destino (Colonia)</small>
                                        <span class="fw-semibold text-dark fs-6">{{ $servicio->paciente?->direccion?->colonia?->nombre_colonia ?? 'No especificada' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Estado -->
                            <div class="col-sm-6">
                                <div class="d-flex align-items-start">
                                    <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(138, 43, 226, 0.1);">
                                        <i class="bx bx-loader-circle fs-5" style="color: #8A2BE2;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Estado actual</small>
                                        <span class="badge px-3 py-2" style="background-color: rgba(57, 51, 149, 0.1); color: #393395; border-radius: 50rem;">{{ $servicio->estado }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4" style="border-top: 1px dashed rgba(0,0,0,0.1);">
                            <div class="d-flex align-items-start">
                                <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(186, 85, 211, 0.05);">
                                    <i class="bx bx-message-square-detail fs-5" style="color: #BA55D3;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Observaciones</small>
                                    <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $servicio->observaciones ?? 'Sin observaciones registradas.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 mb-4" style="transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 12px 25px rgba(57, 51, 149, 0.1)';" onmouseout="this.style.boxShadow='0 4px 15px rgba(57, 51, 149, 0.05)';">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(57, 51, 149, 0.1);">
                                <i class="bx bx-bus fs-4" style="color: #393395;"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark fs-5">Unidad Asignada</h6>
                        </div>
                        <div class="ps-5 ms-1">
                            <span class="badge px-3 py-2 fs-6 fw-semibold" style="background-color: rgba(57, 51, 149, 0.05); color: #393395; border-radius: 10px; border: 1px solid rgba(57, 51, 149, 0.2);">
                                {{ $servicio->ambulancia?->numero_economico ?? 'Sin unidad' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4" style="transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 12px 25px rgba(138, 43, 226, 0.1)';" onmouseout="this.style.boxShadow='0 4px 15px rgba(138, 43, 226, 0.05)';">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background-color: rgba(138, 43, 226, 0.1);">
                                <i class="bx bx-group fs-4" style="color: #8A2BE2;"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark fs-5">Personal a Bordo</h6>
                        </div>
                        
                        <div class="ps-5 ms-1">
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Operador</small>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded-circle" style="background: linear-gradient(135deg, #191970, #6A5ACD); color: #fff;">O</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-dark lh-1">{{ $servicio->operador?->usuario?->nombre ?? 'Pendiente' }}</span>
                                        @if($servicio->operador?->usuario?->telefono)
                                            <small class="text-muted mt-1" style="font-size: 0.75rem;"><i class="bx bx-phone me-1"></i>{{ $servicio->operador->usuario->telefono }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <small class="text-muted d-block mb-2" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Paramédico(s)</small>
                                @forelse($servicio->paramedicos as $paramedico)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle" style="background: linear-gradient(135deg, #BA55D3, #F08080); color: #fff;">P</span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark lh-1">{{ $paramedico->usuario?->nombre ?? 'N/A' }}</span>
                                            @if($paramedico->usuario?->telefono)
                                                <small class="text-muted mt-1" style="font-size: 0.75rem;"><i class="bx bx-phone me-1"></i>{{ $paramedico->usuario->telefono }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <span class="text-muted fst-italic">Ninguno asignado</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>