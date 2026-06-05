@section('title', 'Dashboard')
<x-layouts.app :title="__('Dashboard')">

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

.dashboard-label {

    font-size: .95rem;

    text-transform: uppercase;

    letter-spacing: .12em;

    opacity: .7;
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
        1px solid rgba(38, 15, 42, 0.06);
}

.services-table {

    width: 100%;

    border-collapse: collapse;
}

.services-table thead {

    background:
        linear-gradient(
            135deg,
            #393395,
            #8A2BE2
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
        $totalServicios   = \App\Models\Servicio::count();
        $serviciosActivos = \App\Models\Servicio::where('estado', 'Activo')->count();
        $totalAmbulancia  = \App\Models\Ambulancia::count();
        $ambulanciasDisp  = \App\Models\Ambulancia::where('estado', 'Disponible')->count();
        $totalPacientes   = \App\Models\Paciente::count();
        $totalParamedicos = \App\Models\Paramedico::count();
        $totalClientes    = \App\Models\Cliente::count();
        $totalOperadores  = \App\Models\Operador::count();
    @endphp

{{-- =========================
    DASHBOARD HEADER
========================= --}}
@unless(request()->hasAny(['buscar','tipo','estado','fecha_inicio','fecha_fin']))
<div class="dashboard-header mb-5">

    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">

        <div>

            <span class="dashboard-label">
                Panel de control
            </span>

            <h1 class="dashboard-title mt-2">
                Centro Operativo
            </h1>

            <p class="dashboard-description">
                Supervisa servicios, ambulancias y personal en tiempo real.
            </p>

        </div>

    </div>

</div>
@endunless

{{-- =========================
    KPI CARDS
========================= --}}
<div class="row g-4 mb-5">

    {{-- Servicios Activos --}}
    <div class="col-lg-4">

        <div class="dashboard-mini-card">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="dashboard-mini-label">
                        Servicios activos
                    </span>

                    <h3 class="dashboard-mini-number">
                        {{ $serviciosActivos }}
                    </h3>

                    <small class="text-muted">
                        de {{ $totalServicios }} registrados
                    </small>

                </div>

                <div class="dashboard-mini-icon">
                    <i class="bx bx-pulse"></i>
                </div>

            </div>

        </div>

    </div>

    {{-- Ambulancias --}}
    <div class="col-lg-4">

        <div class="dashboard-mini-card">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="dashboard-mini-label">
                        Ambulancias disponibles
                    </span>

                    <h3 class="dashboard-mini-number">
                        {{ $ambulanciasDisp }}
                    </h3>

                    <small class="text-muted">
                        de {{ $totalAmbulancia }}
                    </small>

                </div>

                <div class="dashboard-mini-icon">
                    <i class="bx bxs-ambulance"></i>
                </div>

            </div>

        </div>

    </div>

    {{-- Personal --}}
    <div class="col-lg-4">

        <div class="dashboard-mini-card">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="dashboard-mini-label">
                        Personal operativo
                    </span>

                    <h3 class="dashboard-mini-number">
                        {{ $totalParamedicos + $totalOperadores }}
                    </h3>

                    <small class="text-muted">
                        {{ $totalParamedicos }} paramédicos
                    </small>

                </div>

                <div class="dashboard-mini-icon">
                    <i class="bx bx-group"></i>
                </div>

            </div>

        </div>

    </div>

</div>

{{-- =========================
    FILTROS
========================= --}}
<div class="card-body p-3 mb-5">

    <form method="GET" action="{{ url()->current() }}#services-panel" class="row g-3 align-items-end">

        <div class="col-md-2">
            <label class="form-label fw-bold">
                <i class="bx bx-search me-1"></i>Buscar
            </label>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                placeholder="# ID o Tipo"
                class="form-control border-0 shadow-sm">
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold">
                <i class="bx bx-category me-1"></i>Tipo
            </label>
            <select name="tipo" class="form-select border-0 shadow-sm">
                <option value="">Todos</option>
                @foreach ($tipos as $value => $label)
                    <option value="{{ $value }}" {{ request('tipo') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold">
                <i class="bx bx-check-circle me-1"></i>Estado
            </label>
            <select name="estado" class="form-select border-0 shadow-sm">
                <option value="">Todos</option>
                @foreach ($estados as $value => $label)
                    <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold">
                <i class="bx bx-calendar me-1"></i>Desde
            </label>
            <input type="date" name="fecha_inicio"
                value="{{ request('fecha_inicio') }}"
                class="form-control border-0 shadow-sm">
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold">
                <i class="bx bx-calendar-event me-1"></i>Hasta
            </label>
            <input type="date" name="fecha_fin"
                value="{{ request('fecha_fin') }}"
                class="form-control border-0 shadow-sm">
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bx bx-search-alt me-1"></i>Buscar
            </button>

            @if(request()->hasAny(['buscar','tipo','estado','fecha_inicio','fecha_fin']))
                <a href="{{ url()->current() }}"
                    class="btn btn-outline-secondary w-100">
                    <i class="bx bx-x me-1"></i>Limpiar
                </a>
            @endif
        </div>

    </form>

</div>


{{-- CONTROL PAGINACIÓN --}}
<form method="GET" action="{{ route('dashboard') }}" class="mb-3">

    <label class="text-muted me-2">Mostrar:</label>

    <select name="por_pagina" onchange="this.form.submit()" class="form-select d-inline w-auto">
        <option value="5"  {{ $porPagina == 5 ? 'selected' : '' }}>5</option>
        <option value="10" {{ $porPagina == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ $porPagina == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ $porPagina == 50 ? 'selected' : '' }}>50</option>
    </select>

</form>

{{-- =========================
    SERVICIOS TABLE
========================= --}}
<div id="services-panel" class="services-panel">

    {{-- HEADER --}}
    <div class="services-panel-header">

        <div>

            <span class="panel-label">
                Centro Operativo
            </span>

            <h2 class="panel-title">
                Últimos Servicios
            </h2>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="services-table-wrapper">

        <div class="table-responsive">

            <table class="services-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Unidad</th>
                        <th>Costo</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($servicios as $servicio)

                        @php
                            $statusClass = match($servicio->estado) {
                                'Activo' => 'status-active',
                                'Finalizado' => 'status-finished',
                                'Cancelado' => 'status-cancelled',
                                default => 'status-pending',
                            };
                        @endphp

                        <tr>
                            <td class="fw-bold">#{{ $servicio->id_servicio }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}
                            </td>

                            <td>{{ $servicio->tipo ?? '—' }}</td>

                            <td>
                                <span class="status-pill {{ $statusClass }}">
                                    {{ $servicio->estado }}
                                </span>
                            </td>

                            <td>{{ $servicio->ambulancia->placa ?? '—' }}</td>

                            <td class="fw-semibold">
                                ${{ number_format($servicio->costo_total, 2) }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bx bx-folder-open"></i>
                                    <p>Sin servicios registrados</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="services-pagination">
        {{ $servicios->appends(request()->query())->links() }}
    </div>

</div>
</x-layouts.app>