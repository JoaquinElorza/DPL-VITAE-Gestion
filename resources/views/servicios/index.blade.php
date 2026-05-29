<x-layouts.app :title="'Historial de Servicios'">



    <style>
        :root {
            --dpl-primary: #7E57C2;
            --dpl-primary-hover: #0D0847;
        }
        
        .titulo-morado {
            color: #ffffff !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 
                -1px -1px 0 #7E57C2,  
                 1px -1px 0 #7E57C2,
                -1px  1px 0 #7E57C2,
                 1px  1px 0 #7E57C2,
                 0px  0px 6px #7E57C2,
                 0px  0px 12px rgba(105, 108, 255, 0.8),
                 2px  2px 4px rgba(0, 0, 0, 0.25);
        }

        .btn-primary {
            background-color: var(--dpl-primary) !important;
            border-color: var(--dpl-primary) !important;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background-color: var(--dpl-primary-hover) !important;
            border-color: var(--dpl-primary-hover) !important;
        }

        .bg-label-primary {
            background-color: rgba(105, 108, 255, 0.16) !important;
            color: var(--dpl-primary) !important;
        }


.dashboard-hero-card {

    background:
        linear-gradient(
            135deg,
            #0f172a,
            #111827
        );

    border-radius: 32px;

    padding: 3rem;

    color: white;

    min-height: 320px;

    position: relative;

    overflow: hidden;
}

.dashboard-hero-card::before {

    content: '';

    position: absolute;

    inset: 0;

    background:
        radial-gradient(
            circle at top right,
            rgba(167,139,250,.25),
            transparent 35%
        );
}

.dashboard-label {

    font-size: .95rem;

    text-transform: uppercase;

    letter-spacing: .12em;

    opacity: .7;
}

.dashboard-number {

    font-size: clamp(4rem, 7vw, 6rem);

    font-weight: 800;

    line-height: 1;

    margin-top: 1rem;
}

.dashboard-subtext {

    opacity: .7;

    margin-top: 1rem;

    font-size: 1rem;
}

.dashboard-icon-lg {

    width: 90px;

    height: 90px;

    border-radius: 24px;

    background:
        rgba(255,255,255,.08);

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 2.5rem;
}

.dashboard-progress {

    width: 100%;

    height: 10px;

    background:
        rgba(255,255,255,.08);

    border-radius: 999px;

    overflow: hidden;
}

.dashboard-progress-bar {

    height: 100%;

    background:
        linear-gradient(
            90deg,
            #dc2626,
            #a78bfa
        );

    border-radius: inherit;
}

.dashboard-mini-card {

    background: white;

    border-radius: 28px;

    padding: 2rem;

    border:
        1px solid rgba(15,23,42,.06);

    min-height: 150px;

    transition: .3s ease;
}

.dashboard-mini-card:hover {

    transform: translateY(-4px);
}

.dashboard-mini-label {

    font-size: .85rem;

    text-transform: uppercase;

    letter-spacing: .08em;

    color: #64748b;
}

.dashboard-mini-number {

    font-size: 2.5rem;

    font-weight: 800;

    color: #0f172a;

    margin-top: .7rem;
}

.dashboard-mini-icon {

    width: 64px;

    height: 64px;

    border-radius: 20px;

    background:
        rgba(167,139,250,.12);

    color: #7e57c2;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 1.8rem;
}

.services-table th {
    color: white;
}
.services-table td,
.services-table th {
    padding: 1.2rem 1rem;
}

.services-table tbody tr:hover {
    background: rgba(167,139,250,.04);
}

.services-panel {

    background: white;

    border-radius: 32px;

    padding: 2rem;

    border:
        1px solid rgba(15,23,42,.06);
}

.services-panel-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 2rem;
}

.panel-label {

    font-size: .8rem;

    text-transform: uppercase;

    letter-spacing: .12em;

    color: #64748b;
}

.panel-title {

    font-size: 2rem;

    font-weight: 800;

    color: #0f172a;

    margin-top: .4rem;
}

.services-table-wrapper {

    overflow: hidden;

    border-radius: 24px;

    border:
        1px solid rgba(15,23,42,.06);
}

.services-table {

    width: 100%;

    border-collapse: collapse;
}

.services-table thead {

    background:
        linear-gradient(
            135deg,
            #0f172a,
            #111827
        );
}

.services-table th {

    color: white;

    padding: 1.2rem 1rem;

    font-size: .85rem;

    text-transform: uppercase;

    letter-spacing: .08em;

    border: none;
}

.services-table td {

    padding: 1.2rem 1rem;

    border-bottom:
        1px solid rgba(15,23,42,.05);

    color: #334155;
}

.services-table tbody tr {

    transition: .2s ease;
}

.services-table tbody tr:hover {

    background:
        rgba(167,139,250,.04);
}

.status-pill {

    padding: .55rem 1rem;

    border-radius: 999px;

    font-size: .8rem;

    font-weight: 600;
}

.status-active {

    background:
        rgba(34,197,94,.12);

    color: #15803d;
}

.status-finished {

    background:
        rgba(100,116,139,.12);

    color: #475569;
}

.status-cancelled {

    background:
        rgba(239,68,68,.12);

    color: #b91c1c;
}

.empty-state {

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    padding: 4rem;

    color: #94a3b8;
}

.empty-state i {

    font-size: 3rem;

    margin-bottom: 1rem;
}

.services-pagination {

    margin-top: 2rem;
}

    </style>

@php
    $totalRegistros = \App\Models\Servicio::count();
    $activos        = \App\Models\Servicio::where('estado', 'Activo')->count();
    $finalizados    = \App\Models\Servicio::where('estado', 'Finalizado')->count();
    $cancelados     = \App\Models\Servicio::where('estado', 'Cancelado')->count();
@endphp




{{-- =========================
    HEADER
========================= --}}
<div class="dashboard-header mb-5">

    <span class="dashboard-label">Historial clínico</span>

    <h1 class="dashboard-title mt-2">
        Registro de Servicios
    </h1>

    <p class="dashboard-description">
        Control centralizado de todos los servicios operativos.
    </p>

</div>

{{-- =========================
    KPI ROW
========================= --}}
<div class="row g-4 mb-5">

    <div class="col-sm-6 col-xl-3">
        <div class="dashboard-mini-card">
            <span class="dashboard-mini-label">Total</span>
            <h2 class="dashboard-mini-number">{{ $totalRegistros }}</h2>
            <div class="dashboard-mini-icon">
                <i class="bx bx-clipboard"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="dashboard-mini-card">
            <span class="dashboard-mini-label">Activos</span>
            <h2 class="dashboard-mini-number">{{ $activos }}</h2>
            <div class="dashboard-mini-icon">
                <i class="bx bx-pulse"></i>
            </div>
        </div>
    </div>


    <div class="col-sm-6 col-xl-3">
        <div class="dashboard-mini-card">
            <span class="dashboard-mini-label">Cancelados</span>
            <h2 class="dashboard-mini-number">{{ $cancelados }}</h2>
            <div class="dashboard-mini-icon">
                <i class="bx bx-x-circle"></i>
            </div>
        </div>
    </div>

</div>

{{-- =========================
    SEARCH BAR
========================= --}}
<div class="services-panel mb-5">

    <div class="services-panel-header">
        <div>
            <span class="panel-label">Filtros</span>
            <h2 class="panel-title">Buscar servicios</h2>
        </div>
    </div>

    <form method="GET" action="{{ route('servicios.index') }}"
          class="row g-3 align-items-end">

        <div class="col-md-10">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Buscar por estado, tipo o cliente..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                <i class="bx bx-search me-1"></i> Buscar
            </button>
        </div>

    </form>

</div>

{{-- CONTROL PAGINACIÓN --}}
<form method="GET" action="{{ url()->current() }}" class="mb-3">

    <label class="text-muted me-2">Mostrar:</label>

    <select name="por_pagina" onchange="this.form.submit()" class="form-select d-inline w-auto">
        <option value="5"  {{ $porPagina == 5 ? 'selected' : '' }}>5</option>
        <option value="10" {{ $porPagina == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ $porPagina == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ $porPagina == 50 ? 'selected' : '' }}>50</option>
    </select>

</form>

{{-- =========================
    TABLE
========================= --}}
<div class="services-panel">

    <div class="services-panel-header d-flex justify-content-between align-items-center">

        <div>
            <span class="panel-label">Base de datos</span>
            <h2 class="panel-title">Servicios registrados</h2>
        </div>

        <a href="{{ route('servicios.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Nuevo
        </a>

    </div>

    <div class="services-table-wrapper">

        <table class="services-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Ambulancia</th>
                    <th>Operador</th>
                    <th>Cliente</th>
                    <th>Costo</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

                @forelse($servicios as $servicio)

                    @php
                        $status = match($servicio->estado) {
                            'Activo'     => 'status-active',
                            'Finalizado' => 'status-finished',
                            'Cancelado'  => 'status-cancelled',
                            default      => 'status-pending',
                        };
                    @endphp

                    <tr>

                        <td class="fw-bold">#{{ $servicio->id_servicio }}</td>

                        <td>{{ $servicio->tipo ?? '—' }}</td>

                        <td>
                            <span class="status-pill {{ $status }}">
                                {{ $servicio->estado }}
                            </span>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}
                        </td>

                        <td>{{ $servicio->ambulancia->placa ?? '—' }}</td>

                        <td>{{ $servicio->operador->usuario->nombre ?? '—' }}</td>

                        <td>{{ $servicio->cliente->usuario->nombre ?? '—' }}</td>

                        <td class="fw-semibold">
                            ${{ number_format($servicio->costo_total, 2) }}
                        </td>

                        <td>
                            <div class="d-flex gap-1">

                                <a href="{{ route('servicios.show', $servicio) }}"
                                   class="btn btn-sm btn-icon btn-outline-info"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="Ver detalles del servicio">
                                    <i class="bx bx-show"></i>
                                </a>

                                <a href="{{ route('servicios.edit', $servicio) }}"
                                   class="btn btn-sm btn-icon btn-outline-warning"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="Editar servicio">
                                    <i class="bx bx-edit"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('servicios.destroy', $servicio) }}"
                                      onsubmit="return confirm('¿Eliminar servicio?')">
                                    @csrf @method('DELETE')

                                    <button class="btn btn-sm btn-icon btn-outline-danger"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Eliminar servicio">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="bx bx-folder-open"></i>
                                <p>Sin registros</p>
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="services-pagination mt-3">
        {{ $servicios->links() }}
    </div>

</div>

</x-layouts.app>