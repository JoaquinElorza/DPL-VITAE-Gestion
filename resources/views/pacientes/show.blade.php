<x-layouts.app :title="'Detalle Paciente'">
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Paciente #{{ $paciente->curp ?? $paciente->id_paciente }}</h5>
                    <div>
                        <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                        <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">CURP</dt>
                        <dd class="col-sm-7">{{ $paciente->curp ?? '—' }}</dd>
                        <dt class="col-sm-5">Nombre</dt>
                        <dd class="col-sm-7">{{ $paciente->nombre }} {{ $paciente->ap_paterno }} {{ $paciente->ap_materno }}</dd>
                        <dt class="col-sm-5">Sexo</dt>
                        <dd class="col-sm-7">{{ $paciente->sexo ?? '—' }}</dd>
                        <dt class="col-sm-5">Fecha Nacimiento</dt>
                        <dd class="col-sm-7">{{ $paciente->fecha_nacimiento ?? '—' }}</dd>
                        <dt class="col-sm-5">Peso</dt>
                        <dd class="col-sm-7">{{ $paciente->peso ? $paciente->peso . ' kg' : '—' }}</dd>
                        <dt class="col-sm-5">Oxígeno</dt>
                        <dd class="col-sm-7">{{ $paciente->oxigeno ? $paciente->oxigeno . '%' : '—' }}</dd>
                        <dt class="col-sm-5">Servicio</dt>
                        <dd class="col-sm-7">{{ $paciente->id_servicio }}</dd>
                        <dt class="col-sm-5">Dirección</dt>
                        <dd class="col-sm-7">
                            @if($paciente->direccion)
                                {{ $paciente->direccion->nombre_calle }} {{ $paciente->direccion->n_exterior }}
                                @if($paciente->direccion->n_interior) Int. {{ $paciente->direccion->n_interior }}@endif
                                — {{ $paciente->direccion->colonia->nombre_colonia ?? '' }}
                            @else
                                —
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Padecimientos</h6></div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($paciente->padecimientos as $padecimiento)
                            <li class="list-group-item">{{ $padecimiento->nombre_padecimiento }} <span class="badge bg-label-warning ms-1">{{ $padecimiento->nivel_riesgo }}</span></li>
                        @empty
                            <li class="list-group-item text-muted">Sin padecimientos</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 mt-4">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="mb-0 text-primary d-flex align-items-center">
                        <i class="bx bx-history me-2" style="font-size: 1.4rem;"></i>Historial de Servicios del Paciente
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" style="border-collapse: collapse; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID de Servicio</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Fecha / Hora</th>
                                    <th>Costo Total</th>
                                    <th class="text-center pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($servicios as $srv)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">#{{ $srv->id_servicio }}</td>
                                        <td>
                                            <span class="badge bg-label-secondary text-capitalize px-3 py-1">
                                                {{ $srv->tipo ?? '—' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = 'bg-label-secondary';
                                                if ($srv->estado === 'Activo' || $srv->estado === 'Realizado') {
                                                    $badgeClass = 'bg-label-success';
                                                } elseif ($srv->estado === 'Pendiente' || $srv->estado === 'En Curso') {
                                                    $badgeClass = 'bg-label-warning';
                                                } elseif ($srv->estado === 'Cancelado') {
                                                    $badgeClass = 'bg-label-danger';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }} px-3 py-1">
                                                {{ $srv->estado }}
                                            </span>
                                        </td>
                                        <td>{{ $srv->fecha_hora ? $srv->fecha_hora->format('d/m/Y H:i') : '—' }}</td>
                                        <td class="fw-bold text-success">${{ number_format($srv->costo_total, 2) }}</td>
                                        <td class="text-center pe-4">
                                            <a href="{{ route('servicios.show', $srv) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                <i class="bx bx-show me-1"></i>Ver Detalles
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bx bx-folder-open fs-2 mb-2 d-block"></i>
                                            Sin servicios registrados para este paciente.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
