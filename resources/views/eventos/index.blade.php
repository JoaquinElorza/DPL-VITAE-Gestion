<x-layouts.app :title="'Eventos'">

<style>
:root {
    --dpl-primary: #7E57C2;
    --dpl-secondary: #0D0847;
    --dpl-danger: #dc2626;
}

/* HEADER */
.dashboard-title {
    font-weight: 800;
    letter-spacing: .5px;
    color: var(--dpl-secondary);
}

.dashboard-description {
    color: #64748b;
    margin-top: .4rem;
}

/* BUTTON */
.btn-primary {
    background: var(--dpl-primary) !important;
    border: none !important;
    border-radius: 12px;
    font-weight: 600;
    padding: .7rem 1rem;
}

.btn-primary:hover {
    background: var(--dpl-secondary) !important;
}

/* PAGINATION SELECT */
.form-select {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.08);
    padding: .5rem 2rem .5rem .8rem;
    font-weight: 500;
}

/* PANEL */
.events-panel {
    background: #fff;
    border-radius: 28px;
    border: 1px solid rgba(15,23,42,.06);
    overflow: hidden;
    box-shadow:
        0 10px 30px rgba(15,23,42,.04);
}

/* TABLE */
.events-table {
    width: 100%;
    border-collapse: collapse;
}

/* THEAD */
.events-table thead {
    background:
        linear-gradient(
            135deg,
            #0f172a,
            #111827
        );
}

.events-table th {
    color: white;
    font-size: .82rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: 1.2rem 1rem;
    border: none;
    white-space: nowrap;
}

/* TD */
.events-table td {
    padding: 1.15rem 1rem;
    border-bottom:
        1px solid rgba(15,23,42,.05);
    color: #334155;
    vertical-align: middle;
}

/* ROW HOVER */
.events-table tbody tr {
    transition: .2s ease;
}

.events-table tbody tr:hover {
    background:
        rgba(167,139,250,.04);
}

/* BADGE */
.badge-event {
    background:
        rgba(126,87,194,.12);

    color:
        var(--dpl-primary);

    padding:
        .45rem .85rem;

    border-radius:
        999px;

    font-weight:
        700;

    font-size:
        .8rem;

    display:
        inline-flex;

    align-items:
        center;
}

/* ACTION BUTTONS */
.action-btn {

    border:
        1px solid rgba(15,23,42,.08);

    background:
        white;

    padding:
        .45rem .8rem;

    border-radius:
        10px;

    font-size:
        .85rem;

    font-weight:
        600;

    color:
        #334155;

    text-decoration:
        none;

    transition:
        .2s ease;
}

.action-btn:hover {

    transform:
        translateY(-2px);

    border-color:
        var(--dpl-primary);

    color:
        var(--dpl-primary);

    box-shadow:
        0 4px 12px rgba(126,87,194,.12);
}

/* EMPTY STATE */
.empty-state {

    display:
        flex;

    flex-direction:
        column;

    align-items:
        center;

    justify-content:
        center;

    padding:
        4rem;

    color:
        #94a3b8;
}

.empty-state i {

    font-size:
        3rem;

    margin-bottom:
        1rem;
}

/* FOOTER */
.events-panel-footer {

    padding:
        1.2rem 1.5rem;

    border-top:
        1px solid rgba(15,23,42,.05);

    background:
        #fff;
}

/* PAGINATION */
.pagination {
    margin-bottom: 0;
}

.page-link {

    border:
        none !important;

    color:
        #475569;

    border-radius:
        10px !important;

    margin:
        0 .15rem;

    font-weight:
        600;
}

.page-item.active .page-link {

    background:
        var(--dpl-primary) !important;

    color:
        white !important;
}

.page-link:hover {

    background:
        rgba(126,87,194,.1);

    color:
        var(--dpl-primary);
}
</style>

{{-- =========================
    HEADER
========================= --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

    <div>
        <h2 class="dashboard-title mb-1">Eventos privados</h2>
        <p class="text-muted mb-0">
            Control operativo de eventos registrados
        </p>
    </div>

    <div class="d-flex gap-3 align-items-center flex-wrap">
        {{-- SEARCH --}}
        <form action="{{ route('eventos.index') }}" method="GET" class="d-flex gap-2">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Buscar evento..."
                   class="form-control form-control-sm"
                   style="max-width: 250px;">
            <button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Buscar evento">
                <i class="bx bx-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('eventos.index') }}"
                   class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Limpiar búsqueda">
                    <i class="bx bx-x"></i>
                </a>
            @endif
        </form>

        <a href="{{ route('eventos.create') }}" class="btn btn-primary">
            + Nuevo
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

{{-- =========================
    TABLE PANEL
========================= --}}
<div class="events-panel">

    <div class="table-responsive">

        <table class="events-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Duración (hrs)</th>
                    <th>Personas</th>
                    <th>Servicio Vinculado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($eventos as $evento)

                <tr>

                    <td>
                        <span class="badge-event">
                            #{{ $evento->id_evento }}
                        </span>
                    </td>

                    <td>
                        {{ $evento->duracion }} hrs
                    </td>

                    <td>
                        {{ $evento->personas }}
                    </td>

                    <td>
                        Servicio #{{ $evento->id_servicio }}
                    </td>

                    <td class="text-center">

                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                            <a href="{{ route('eventos.show', $evento) }}"
                               class="action-btn"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Ver detalles del evento">
                                Ver
                            </a>

                            <a href="{{ route('eventos.edit', $evento) }}"
                               class="action-btn"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Editar evento">
                                Editar
                            </a>

                            <form action="{{ route('eventos.destroy', $evento) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar evento?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="action-btn text-danger border-0"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Eliminar evento">
                                    Eliminar
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5">

                        <div class="empty-state">
                            <i class="bx bx-calendar-x fs-1 mb-3"></i>
                            <p>Sin eventos registrados</p>
                        </div>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <small class="text-muted">
            Total: {{ $eventos->total() }} registros
        </small>

        {{ $eventos->links() }}

    </div>

</div>

</x-layouts.app>