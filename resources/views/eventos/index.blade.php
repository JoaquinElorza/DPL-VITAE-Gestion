<x-layouts.app :title="'Eventos'">

<style>
:root {
    --dpl-primary: #7E57C2;   /* morado */
    --dpl-secondary: #0D0847; /* azul marino */
    --dpl-danger: #dc2626;    /* rojo */
}

/* HEADER */
.dashboard-title {
    font-weight: 800;
    letter-spacing: .5px;
    color: var(--dpl-secondary);
}

/* BUTTON */
.btn-primary {
    background: var(--dpl-primary) !important;
    border: none !important;
}

.btn-primary:hover {
    background: var(--dpl-secondary) !important;
}

/* PANEL */
.events-panel {
    background: #fff;
    border-radius: 24px;
    border: 1px solid rgba(0,0,0,.06);
    overflow: hidden;
}

/* TABLE */
.events-table {
    width: 100%;
    border-collapse: collapse;
}

.events-table thead {
    background: var(--dpl-secondary);
    color: white;
}

.events-table th {
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: 1rem;
}

.events-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
}

/* BADGE */
.badge-event {
    background: rgba(126,87,194,.12);
    color: var(--dpl-primary);
    padding: .35rem .75rem;
    border-radius: 999px;
    font-weight: 600;
    font-size: .8rem;
}

/* ACTIONS */
.action-btn {
    border: 1px solid rgba(0,0,0,.08);
    background: white;
    padding: .35rem .55rem;
    border-radius: 8px;
    font-size: .85rem;
    transition: .2s;
}

.action-btn:hover {
    transform: translateY(-2px);
    border-color: var(--dpl-primary);
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 4rem;
    color: #94a3b8;
}
</style>

{{-- =========================
    HEADER
========================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="dashboard-title mb-1">Eventos clínicos</h2>
        <p class="text-muted mb-0">
            Control operativo de eventos registrados
        </p>
    </div>

    <a href="{{ route('eventos.create') }}" class="btn btn-primary">
        + Nuevo
    </a>

</div>

{{-- =========================
    TABLE PANEL
========================= --}}
<div class="events-panel">

    <div class="table-responsive">

        <table class="events-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servicio</th>
                    <th>Duración</th>
                    <th>Personas</th>
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
                        Servicio #{{ $evento->id_servicio }}
                    </td>

                    <td>
                        {{ $evento->duracion }} hrs
                    </td>

                    <td>
                        {{ $evento->personas }}
                    </td>

                    <td class="text-center">

                        <div class="d-flex justify-content-center gap-2">

                            <a href="{{ route('eventos.show', $evento) }}"
                               class="action-btn">
                                Ver
                            </a>

                            <a href="{{ route('eventos.edit', $evento) }}"
                               class="action-btn">
                                Editar
                            </a>

                            <form action="{{ route('eventos.destroy', $evento) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar evento?')">

                                @csrf
                                @method('DELETE')

                                <button class="action-btn text-danger">
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

    <div class="p-3 d-flex justify-content-between align-items-center">

        <small class="text-muted">
            Total: {{ $eventos->total() }} registros
        </small>

        {{ $eventos->links() }}

    </div>

</div>

</x-layouts.app>