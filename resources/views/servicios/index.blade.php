<x-layouts.app :title="'Historial de Servicios'">

    @php
        $totalRegistros = \App\Models\Servicio::count();
        $activos   = \App\Models\Servicio::where('estado', 'Activo')->count();
        $finalizados = \App\Models\Servicio::where('estado', 'Finalizado')->count();
        $cancelados  = \App\Models\Servicio::where('estado', 'Cancelado')->count();
    @endphp

    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="avatar avatar-lg">
            <span class="avatar-initial rounded bg-label-primary">
                <i class="bx bx-history bx-md"></i>
            </span>
        </div>
        <div>
            <h4 class="mb-0">Historial de Servicios</h4>
            <small class="text-muted">Registro completo de todos los servicios</small>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Total Servicios</span>
                            <h3 class="card-title mb-2">{{ $totalRegistros }}</h3>
                            <small class="text-muted">registros</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-ambulance bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Activos</span>
                            <h3 class="card-title mb-2">{{ $activos }}</h3>
                            <small class="text-muted">en curso</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-play-circle bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Finalizados</span>
                            <h3 class="card-title mb-2">{{ $finalizados }}</h3>
                            <small class="text-muted">completados</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="bx bx-check-double bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Cancelados</span>
                            <h3 class="card-title mb-2">{{ $cancelados }}</h3>
                            <small class="text-muted">del total</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-x-circle bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.04), rgba(59, 130, 246, 0.04));">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('servicios.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-search me-1"></i>Buscar</label>
                    <input type="text" name="search" class="form-control border-0 shadow-sm" placeholder="Estado, tipo o cliente..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary" title="Buscar"><i class="bx bx-search me-1"></i> Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('servicios.index') }}" class="btn btn-outline-secondary" title="Limpiar"><i class="bx bx-x"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0">Listado de Servicios</h5>
            <a href="{{ route('servicios.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i>Nuevo Servicio
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Fecha / Hora</th>
                        <th>Ambulancia</th>
                        <th>Operador</th>
                        <th>Cliente</th>
                        <th class="text-end">Costo Total</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                    <tr>
                        <td class="fw-semibold">{{ $servicio->id_servicio }}</td>
                        <td>
                            <span class="badge bg-label-info">{{ $servicio->tipo ?? '—' }}</span>
                        </td>
                        <td>
                            @php
                                $badgeLabel = match($servicio->estado) {
                                    'Activo'     => 'success',
                                    'Finalizado' => 'secondary',
                                    'Cancelado'  => 'danger',
                                    default      => 'warning',
                                };
                            @endphp
                            <span class="badge bg-label-{{ $badgeLabel }}">{{ $servicio->estado }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-calendar text-muted"></i>
                                <span>{{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-ambulance text-muted"></i>
                                <span>{{ $servicio->ambulancia->placa ?? '—' }}</span>
                            </div>
                        </td>
                        <td>{{ $servicio->operador->usuario->nombre ?? '—' }}</td>
                        <td>{{ $servicio->cliente->usuario->nombre ?? '—' }}</td>
                        <td class="text-end fw-semibold">${{ number_format($servicio->costo_total, 2) }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('servicios.show', $servicio) }}"
                                   class="btn btn-sm btn-icon btn-outline-info"
                                   title="Ver detalle">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('servicios.edit', $servicio) }}"
                                   class="btn btn-sm btn-icon btn-outline-warning"
                                   title="Editar">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('servicios.destroy', $servicio) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-icon btn-outline-danger" title="Eliminar">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bx bx-folder-open fs-2 d-block mb-2"></i>
                            No hay servicios registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Mostrando {{ $servicios->firstItem() ?? 0 }} - {{ $servicios->lastItem() ?? 0 }} de {{ $servicios->total() }} registros</small>
            {{ $servicios->links() }}
        </div>
    </div>

</x-layouts.app>