<x-layouts.app :title="'Ambulancias'">

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

/* TABLE WRAPPER */
.services-table-wrapper{
    overflow:hidden;
    border-radius:18px;
    border:1px solid rgba(13,8,71,.08);
}

/* TABLE */
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
    font-size:.8rem;
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
.badge-state{
    padding:.35rem .7rem;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

.badge-active{
    background:rgba(126,87,194,.12);
    color:var(--dpl-primary);
}

.badge-danger{
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

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

    <div>
        <h2 class="dashboard-title mb-1">Ambulancias</h2>
        <p class="text-muted mb-0">Control de unidades disponibles y en servicio</p>
    </div>

    <div class="d-flex gap-3 align-items-center flex-wrap">
        {{-- SEARCH --}}
        <form action="{{ route('ambulancias.index') }}" method="GET" class="d-flex gap-2">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Buscar ambulancia..."
                   class="form-control form-control-sm"
                   style="max-width: 250px;">
            <button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Buscar ambulancia">
                <i class="bx bx-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('ambulancias.index') }}"
                   class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Limpiar búsqueda">
                    <i class="bx bx-x"></i>
                </a>
            @endif
        </form>

        <a href="{{ route('tipos-ambulancia.index') }}" class="btn btn-primary">
            <i class="bx bx-pen me-1"></i> Tipos
        </a>

        <a href="{{ route('ambulancias.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Nueva ambulancia
        </a>
    </div>

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

{{-- PANEL --}}
<div class="services-panel">

    <div class="services-table-wrapper">

        <table class="services-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Placa</th>
                    <th>Estado</th>
                    <th>Tipo</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($ambulancias as $ambulancia)

                <tr>

                    <td class="fw-bold">
                        #{{ $ambulancia->id_ambulancia }}
                    </td>

                    <td>
                        {{ $ambulancia->placa }}
                    </td>

                    <td>
                        @if($ambulancia->estado === 'Disponible')
                            <span class="badge-state badge-active">
                                {{ $ambulancia->estado }}
                            </span>
                        @else
                            <span class="badge-state badge-danger">
                                {{ $ambulancia->estado }}
                            </span>
                        @endif
                    </td>

                    <td>
                        {{ $ambulancia->tipo->nombre_tipo ?? '—' }}
                    </td>

                    <td class="text-center">

                        <a href="{{ route('ambulancias.show', $ambulancia) }}"
                           class="btn btn-sm btn-outline-info"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           title="Ver detalles de la ambulancia">
                            Ver
                        </a>

                        <a href="{{ route('ambulancias.edit', $ambulancia) }}"
                           class="btn btn-sm btn-outline-warning"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           title="Editar ambulancia">
                            Editar
                        </a>

                        <form action="{{ route('ambulancias.destroy', $ambulancia) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar ambulancia?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Eliminar ambulancia">
                                Eliminar
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bx bx-ambulance fs-1 mb-2"></i>
                            Sin registros
                        </div>
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- PAGINACIÓN (UNA SOLA, CORREGIDO) --}}
<div class="d-flex justify-content-between align-items-center mt-3">

    <small class="text-muted">
        Total: {{ $ambulancias->total() }} registros
    </small>

    {{ $ambulancias->appends(request()->query())->links() }}

</div>

</x-layouts.app>