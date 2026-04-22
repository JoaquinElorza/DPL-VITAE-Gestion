<x-layouts.app :title="'Detalle Tipo de Ambulancia'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tipo: {{ $tipoAmbulancia->nombre_tipo }}</h5>
            <div>
                <a href="{{ route('tipos-ambulancia.edit', $tipoAmbulancia) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('tipos-ambulancia.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">ID</dt>
                <dd class="col-sm-8">{{ $tipoAmbulancia->id_tipo_ambulancia }}</dd>
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $tipoAmbulancia->nombre_tipo }}</dd>
                <dt class="col-sm-4">Descripción</dt>
                <dd class="col-sm-8">{{ $tipoAmbulancia->descripcion ?? '—' }}</dd>
            </dl>
        </div>
    </div>
</x-layouts.app>
