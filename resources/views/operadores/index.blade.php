<x-layouts.app :title="'Operadores'">

@if(session('success'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<style>
:root{
    --primary:#7E57C2;
    --secondary:#0D0847;
    --danger:#dc2626;
}

/* HEADER */
.page-title{
    font-weight:800;
    color: var(--secondary);
}

/* TABLE */
.operators-table thead{
    background: var(--secondary);
    color:#fff;
}

.operators-table th{
    padding:1rem;
    font-size:.85rem;
    text-transform:uppercase;
    letter-spacing:.08em;
}

.operators-table td{
    padding:1rem;
    border-bottom:1px solid rgba(0,0,0,.06);
}

/* BADGES */
.badge-status{
    padding:.4rem .8rem;
    border-radius:999px;
    font-weight:600;
    font-size:.8rem;
}

.badge-active{
    background: rgba(220,38,38,.12);
    color: var(--danger);
}

.badge-free{
    background: rgba(126,87,194,.12);
    color: var(--primary);
}

/* FOOTER */
.table-footer{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:1rem;
}
</style>

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="page-title mb-0">Operadores</h4>
        <small class="text-muted">Gestión del personal operativo</small>
    </div>

    <a href="{{ route('operadores.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Nuevo operador
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

{{-- TABLE --}}
<div class="card border-0 shadow-sm">

    <div class="table-responsive">

        <table class="operators-table w-100">

            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Pago</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($operadores as $operador)

                <tr>

                    <td class="fw-semibold">
                        {{ $operador->usuario->nombre ?? '—' }}
                        {{ $operador->usuario->ap_paterno ?? '' }}
                        {{ $operador->usuario->ap_materno ?? '' }}
                    </td>

                    <td class="text-muted">
                        {{ $operador->usuario->email ?? '—' }}
                    </td>

                    <td class="fw-semibold">
                        ${{ number_format($operador->salario_hora, 2) }}
                        <small class="text-muted">/hr</small>
                    </td>

                    <td>
                        @if($operador->en_servicio)
                            <span class="badge-status badge-active">En servicio</span>
                        @else
                            <span class="badge-status badge-free">Disponible</span>
                        @endif
                    </td>

                    <td class="text-center">

                        {{-- BOTONES SIN CAMBIO --}}
                        <a href="{{ route('operadores.show', $operador) }}"
                           class="btn btn-sm btn-outline-primary">
                            Ver
                        </a>

                        <a href="{{ route('operadores.edit', $operador) }}"
                           class="btn btn-sm btn-outline-warning">
                            Editar
                        </a>

                        <form action="{{ route('operadores.destroy', $operador) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar operador?')">

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
                    <td colspan="5" class="text-center text-muted py-5">
                        Sin operadores registrados
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="table-footer">

        <small class="text-muted">
            Total: {{ $operadores->total() }} registros
        </small>

        {{ $operadores->links() }}

    </div>

</div>

</x-layouts.app>