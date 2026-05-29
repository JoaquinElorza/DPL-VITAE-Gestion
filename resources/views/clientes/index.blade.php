<x-layouts.app :title="'Clientes'">

<style>
:root{
    --dpl-primary:#7E57C2;      /* morado */
    --dpl-secondary:#0D0847;    /* azul marino */
    --dpl-danger:#dc2626;       /* rojo */
}

/* HEADER */
.dashboard-title{
    font-weight:800;
    color: var(--dpl-secondary);
}

/* BOTÓN PRINCIPAL */
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

/* ROW HOVER */
.services-table tbody tr:hover{
    background:rgba(126,87,194,.05);
}

/* BADGE ID */
.badge-id{
    background:rgba(126,87,194,.12);
    color:var(--dpl-primary);
    padding:.35rem .7rem;
    border-radius:999px;
    font-weight:600;
    font-size:.75rem;
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
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="dashboard-title mb-1">Clientes</h2>
        <p class="text-muted mb-0">Gestión de clientes registrados</p>
    </div>

    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Nuevo cliente
    </a>

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
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($clientes as $cliente)

                <tr>

                    <td>
                        <span class="badge-id">
                            #{{ $cliente->id_usuario }}
                        </span>
                    </td>

                    <td class="fw-semibold">
                        {{ $cliente->usuario->nombre ?? '—' }}
                        {{ $cliente->usuario->ap_paterno ?? '' }}
                        {{ $cliente->usuario->ap_materno ?? '' }}
                    </td>

                    <td>{{ $cliente->usuario->email ?? '—' }}</td>

                    <td>{{ $cliente->usuario->telefono ?? '—' }}</td>

                    <td class="text-center">

                        <a href="{{ route('clientes.show', $cliente) }}"
                           class="btn btn-sm btn-outline-info">
                            Ver
                        </a>

                        <a href="{{ route('clientes.edit', $cliente) }}"
                           class="btn btn-sm btn-outline-warning">
                            Editar
                        </a>

                        <form action="{{ route('clientes.destroy', $cliente) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar cliente?')">

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
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bx bx-group fs-1 mb-2"></i>
                            Sin clientes registrados
                        </div>
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">

        <small class="text-muted">
            Total: {{ $clientes->total() }} registros
        </small>

        {{ $clientes->links() }}

    </div>

</div>

</x-layouts.app>