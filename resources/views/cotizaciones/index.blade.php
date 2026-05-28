<x-layouts.app :title="'Cotizaciones'">

<style>
:root{
    --dpl-primary:#7E57C2;
    --dpl-secondary:#0D0847;
    --dpl-danger:#dc2626;
}

/* HEADER */
.dashboard-title{
    font-weight:800;
    color:var(--dpl-secondary);
}

/* BUTTON */
.btn-primary{
    background:var(--dpl-primary) !important;
    border:none !important;
}
.btn-primary:hover{
    background:var(--dpl-secondary) !important;
}

/* PANEL */
.services-panel{
    background:#fff;
    border-radius:28px;
    padding:2rem;
    border:1px solid rgba(13,8,71,.08);
}

/* TABLE */
.services-table-wrapper{
    overflow:hidden;
    border-radius:18px;
    border:1px solid rgba(13,8,71,.08);
}

.services-table{
    width:100%;
    border-collapse:collapse;
}

.services-table thead{
    background:var(--dpl-secondary);
    color:#fff;
}

.services-table th{
    padding:1rem;
    font-size:.75rem;
    text-transform:uppercase;
    letter-spacing:.08em;
}

.services-table td{
    padding:1rem;
    border-bottom:1px solid rgba(13,8,71,.06);
    color:#334155;
}

.services-table tbody tr:hover{
    background:rgba(126,87,194,.05);
}

/* BADGES */
.badge-pill{
    padding:.35rem .7rem;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

.badge-primary-soft{
    background:rgba(126,87,194,.12);
    color:var(--dpl-primary);
}

.badge-success-soft{
    background:rgba(34,197,94,.12);
    color:#15803d;
}

.badge-warning-soft{
    background:rgba(245,158,11,.12);
    color:#b45309;
}

.badge-danger-soft{
    background:rgba(220,38,38,.12);
    color:var(--dpl-danger);
}

/* EMPTY */
.empty-state{
    text-align:center;
    padding:3rem;
    color:#94a3b8;
}
</style>

@if(session('success'))
<div class="alert alert-success mb-4">
    {{ session('success') }}
</div>
@endif

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

    <div>
        <h2 class="dashboard-title mb-1">Cotizaciones</h2>
        <p class="text-muted mb-0">Solicitudes de cotización con análisis inteligente</p>
    </div>

    <div class="d-flex gap-2 align-items-center">

        <form action="{{ route('cotizaciones.index') }}" method="GET" class="d-flex">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Buscar..."
                   class="form-control form-control-sm">

            <button class="btn btn-primary btn-sm ms-2">
                <i class="bx bx-search"></i>
            </button>

            @if(request('search'))
                <a href="{{ route('cotizaciones.index') }}"
                   class="btn btn-outline-secondary btn-sm ms-1">
                    <i class="bx bx-x"></i>
                </a>
            @endif

        </form>

        <span class="badge badge-pill badge-primary-soft">
            {{ $cotizaciones->total() }} total
        </span>

    </div>

</div>

{{-- PANEL --}}
<div class="services-panel">

    <div class="services-table-wrapper">

        <table class="services-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>IA</th>
                    <th>Estado</th>
                    <th>Recibida</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($cotizaciones as $cotizacion)

                <tr>

                    <td>
                        <span class="badge-pill badge-primary-soft">
                            #{{ $cotizacion->id_cotizacion }}
                        </span>
                    </td>

                    <td class="fw-semibold">
                        {{ $cotizacion->nombre }}
                    </td>

                    <td>{{ $cotizacion->telefono }}</td>

                    <td>{{ $cotizacion->tipo_servicio }}</td>

                    <td>
                        {{ $cotizacion->fecha_requerida
                            ? \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y')
                            : '—' }}
                    </td>

                    <td>

                        @if(isset($precios_ia[$cotizacion->id_cotizacion]))

                            @php
                                $cluster = $clusters_ia[$cotizacion->id_cotizacion] ?? 'Medio';

                                $class = match($cluster){
                                    'Bajo' => 'badge-success-soft',
                                    'Medio' => 'badge-warning-soft',
                                    'Alto' => 'badge-danger-soft',
                                    default => 'badge-primary-soft'
                                };
                            @endphp

                            <div>
                                <div class="fw-bold">${{ number_format($precios_ia[$cotizacion->id_cotizacion],2) }}</div>
                                <span class="badge-pill {{ $class }}">
                                    {{ $cluster }}
                                </span>
                            </div>

                        @else
                            <span class="text-muted">—</span>
                        @endif

                    </td>

                    <td>

                        @php
                            $status = match($cotizacion->estado){
                                'Pendiente' => 'badge-warning-soft',
                                'En revisión' => 'badge-primary-soft',
                                'Respondida' => 'badge-success-soft',
                                'Cancelada' => 'badge-danger-soft',
                                default => 'badge-primary-soft'
                            };
                        @endphp

                        <span class="badge-pill {{ $status }}">
                            {{ $cotizacion->estado }}
                        </span>

                    </td>

                    <td>
                        {{ $cotizacion->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td class="text-center">

                        <a href="{{ route('cotizaciones.show', $cotizacion) }}"
                           class="btn btn-sm btn-outline-info">
                            Ver
                        </a>

                        <form action="{{ route('cotizaciones.destroy', $cotizacion) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar cotización?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger">
                                Eliminar
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bx bx-calculator fs-1 mb-2"></i>
                            Sin cotizaciones registradas
                        </div>
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="d-flex justify-content-between align-items-center mt-3">

    <small class="text-muted">
        Total: {{ $cotizaciones->total() }} registros
    </small>

    {{ $cotizaciones->links() }}

</div>

</x-layouts.app>