@section('title', 'Cotizaciones')
<x-layouts.app :title="'Cotizaciones'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Solicitudes de Cotización</h5>
            <div class="d-flex gap-2 align-items-center">
                <form action="{{ route('cotizaciones.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-outline-secondary ms-1" title="Buscar"><i class="bx bx-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('cotizaciones.index') }}" class="btn btn-sm btn-outline-danger ms-1" title="Limpiar"><i class="bx bx-x"></i></a>
                    @endif
                </form>
                <span class="badge bg-primary">{{ $cotizaciones->total() }} total</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Fecha requerida</th>
                        <th>Estado</th>
                        <th>Recibida</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cotizaciones as $cotizacion)
                    <tr>
                        <td>{{ $cotizacion->id_cotizacion }}</td>
                        <td>{{ $cotizacion->nombre }}</td>
                        <td>{{ $cotizacion->telefono }}</td>
                        <td>{{ $cotizacion->tipo_servicio }}</td>
                        <td>{{ $cotizacion->fecha_requerida ? \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') : '—' }}</td>
                        <td>
                            @php
                                $color = match($cotizacion->estado) {
                                    'Pendiente'   => 'warning',
                                    'En revisión' => 'info',
                                    'Respondida'  => 'success',
                                    'Cancelada'   => 'danger',
                                    default       => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-label-{{ $color }}">{{ $cotizacion->estado }}</span>
                        </td>
                        <td>{{ $cotizacion->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('cotizaciones.show', $cotizacion) }}"
                               class="btn btn-sm btn-outline-info me-1"
                               title="Ver detalle">
                                <i class="bx bx-show"></i> Ver
                            </a>
                            <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta cotización?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bx bx-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Sin solicitudes aún</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $cotizaciones->total() }} registros</small>
            {{ $cotizaciones->links() }}
        </div>
    </div>
</x-layouts.app>
