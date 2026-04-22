<x-layouts.app :title="'Detalle Padecimiento'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Padecimiento #{{ $padecimiento->id_padecimiento }}</h5>
            <div>
                <a href="{{ route('padecimientos.edit', $padecimiento) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('padecimientos.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $padecimiento->nombre_padecimiento }}</dd>
                <dt class="col-sm-4">Nivel de Riesgo</dt>
                <dd class="col-sm-8">{{ $padecimiento->nivel_riesgo }}</dd>
                <dt class="col-sm-4">Costo Extra</dt>
                <dd class="col-sm-8">${{ number_format($padecimiento->costo_extra, 2) }}</dd>
            </dl>
        </div>
    </div>
</x-layouts.app>
