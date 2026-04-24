<x-layouts.app :title="__('Mi Panel - Paramédico')">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Mi Panel /</span> Vista de Paramédico
    </h4>

    <div class="row g-4">
        @forelse($serviciosAsignados as $servicio)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border-primary">
                    <div class="card-header d-flex align-items-center justify-content-between pb-2">
                        <h5 class="card-title m-0">Servicio #{{ $servicio->id_servicio }}</h5>
                        <span class="badge bg-label-success">Activo</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-calendar text-primary me-2"></i>
                            <span>{{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-ambulance text-danger me-2"></i>
                            <span>Unidad: <strong>{{ $servicio->ambulancia->placa ?? 'Sin asignar' }}</strong></span>
                        </div>
                        
                        <hr>
                        <h6 class="text-muted">Pacientes a bordo:</h6>
                        <ul class="list-unstyled mb-0">
                            @forelse($servicio->pacientes as $paciente)
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bx bx-user-circle mt-1 me-2 text-info"></i>
                                        <div>
                                            <strong>{{ $paciente->nombre }} {{ $paciente->ap_paterno }}</strong><br>
                                            <small class="text-muted">
                                                <i class="bx bx-map"></i> 
                                                {{ $paciente->direccion->nombre_calle ?? 'Dirección no especificada' }} 
                                                #{{ $paciente->direccion->n_exterior ?? '' }}
                                            </small>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li><small class="text-muted">No hay pacientes registrados en este servicio.</small></li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer pt-0">
                        <button class="btn btn-primary w-100">
                            <i class="bx bx-check-circle me-1"></i> Finalizar Servicio
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bx bx-info-circle me-2"></i>
                    Actualmente no tienes ningún servicio activo asignado. ¡Buen turno!
                </div>
            </div>
        @endforelse
    </div>

</x-layouts.app>