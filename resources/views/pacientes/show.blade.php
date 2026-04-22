<x-layouts.app :title="'Detalle Paciente'">
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Paciente #{{ $paciente->id_paciente }}</h5>
                    <div>
                        <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                        <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
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
    </div>
</x-layouts.app>
