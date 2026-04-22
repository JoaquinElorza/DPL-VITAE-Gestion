<x-layouts.app :title="'Detalle Evento'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Evento #{{ $evento->id_evento }}</h5>
            <div>
                <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('eventos.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">ID Evento</dt>
                <dd class="col-sm-8">{{ $evento->id_evento }}</dd>
                <dt class="col-sm-4">Servicio</dt>
                <dd class="col-sm-8">{{ $evento->id_servicio }}</dd>
                <dt class="col-sm-4">Duración</dt>
                <dd class="col-sm-8">{{ $evento->duracion }}</dd>
                <dt class="col-sm-4">Personas</dt>
                <dd class="col-sm-8">{{ $evento->personas }}</dd>
            </dl>
        </div>
    </div>
</x-layouts.app>
