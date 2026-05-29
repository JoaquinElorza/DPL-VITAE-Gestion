<x-layouts.app :title="'Paramédicos'">

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
.medics-table thead{
    background: var(--secondary);
    color:#fff;
}

.medics-table th{
    padding:1rem;
    font-size:.85rem;
    text-transform:uppercase;
    letter-spacing:.08em;
}

.medics-table td{
    padding:1rem;
    border-bottom:1px solid rgba(0,0,0,.06);
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
        <h4 class="page-title mb-0">Paramédicos</h4>
        <small class="text-muted">Gestión del personal clínico</small>
    </div>

    <a href="{{ route('paramedicos.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Nuevo
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

        <table class="medics-table w-100">

            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>Email</th>
                    <th>Salario/Hora</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($paramedicos as $paramedico)

                <tr>

                    <td class="fw-semibold">
                        {{ $paramedico->usuario->nombre ?? '—' }}
                        {{ $paramedico->usuario->ap_paterno ?? '' }}
                        {{ $paramedico->usuario->ap_materno ?? '' }}
                    </td>

                    <td class="text-muted">
                        {{ $paramedico->usuario->email ?? '—' }}
                    </td>

                    <td class="fw-semibold">
                        ${{ number_format($paramedico->salario_hora, 2) }}
                    </td>

                    <td class="text-center">

                        {{-- BOTONES SIN CAMBIO --}}
                        <a href="{{ route('paramedicos.show', $paramedico) }}"
                           class="btn btn-sm btn-outline-info me-1">
                            <i class="bx bx-show"></i> Ver
                        </a>

                        <a href="{{ route('paramedicos.edit', $paramedico) }}"
                           class="btn btn-sm btn-outline-warning me-1">
                            <i class="bx bx-edit"></i> Editar
                        </a>

                        <form action="{{ route('paramedicos.destroy', $paramedico) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este paramédico?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-trash"></i> Eliminar
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        Sin registros
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="table-footer">

        <small class="text-muted">
            Total: {{ $paramedicos->total() }} registros
        </small>

        {{ $paramedicos->links() }}

    </div>

</div>

</x-layouts.app>