<x-layouts.app :title="'Pacientes'">
<style>

.events-panel{
    background: #fff;
    border-radius: 32px;
    padding: 2rem;
    border: 1px solid rgba(15,23,42,.06);
    box-shadow: 0 10px 30px rgba(15,23,42,.04);
    overflow: hidden;
}

.events-table-wrapper{
    overflow: hidden;
    border-radius: 24px;
    border: 1px solid rgba(15,23,42,.06);
}

.events-table{
    width: 100%;
    min-width: 1000px;
    border-collapse: separate;
    border-spacing: 0;
}

.events-table thead{
    background: linear-gradient(
        135deg,
        #0f172a,
        #111827
    );
}

.events-table th{
    color: #fff;
    padding: 1.2rem 1rem;
    font-size: .85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .08em;
    border: none;
}

.events-table td{
    padding: 1.2rem 1rem;
    color: #334155;
    vertical-align: middle;
    border-bottom: 1px solid rgba(15,23,42,.06);
    border-right: 1px solid rgba(15,23,42,.06);
}

.events-table th:last-child,
.events-table td:last-child{
    border-right: none;
}

.events-table tbody tr{
    transition: .2s ease;
}

.events-table tbody tr:last-child td{
    border-bottom: none;
}

.events-table tbody tr:hover{
    background: rgba(126,87,194,.04);
}

.badge-event{
    background: rgba(126,87,194,.12);
    color: #7E57C2;
    padding: .45rem .9rem;
    border-radius: 999px;
    font-size: .82rem;
    font-weight: 700;
}

.action-btn{
    padding: .45rem .8rem;
    border-radius: 10px;
    border: 1px solid rgba(15,23,42,.08);
    background: #fff;
    color: #334155;
    font-size: .85rem;
    font-weight: 600;
    text-decoration: none;
    transition: .2s ease;
}

.action-btn:hover{
    transform: translateY(-2px);
    background: rgba(126,87,194,.06);
    color: #7E57C2;
}

.empty-state{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem;
    text-align: center;
    color: #94a3b8;
}

.empty-state i{
    font-size: 3rem;
    margin-bottom: 1rem;
}

</style>

{{-- =========================
    HEADER
========================= --}}
<div class="dashboard-header mb-5">

    <span class="dashboard-label">Registro clínico</span>

    <h1 class="dashboard-title mt-2">
        Pacientes
    </h1>

    <p class="dashboard-description">
        Gestión centralizada de pacientes registrados en el sistema
    </p>

</div>

{{-- =========================
    HEADER ACTIONS
========================= --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

    {{-- SEARCH --}}
    <form action="{{ route('pacientes.index') }}" method="GET" class="d-flex gap-2">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Buscar paciente..."
               class="form-control form-control-sm">

        <button class="btn btn-primary btn-sm">
            <i class="bx bx-search"></i>
        </button>

        @if(request('search'))
            <a href="{{ route('pacientes.index') }}"
               class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-x"></i>
            </a>
        @endif

    </form>

    {{-- CREATE --}}
    <a href="{{ route('pacientes.create') }}" class="btn btn-primary btn-sm">
        <i class="bx bx-plus me-1"></i> Nuevo paciente
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


{{-- =========================
    TABLE PANEL
========================= --}}

<div class="events-panel">

    <div class="table-responsive">

        <table class="events-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ap. Paterno</th>
                    <th>Ap. Materno</th>
                    <th>Sexo</th>
                    <th>Fecha Nac.</th>
                    <th>Servicio</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($pacientes as $paciente)

                <tr>

                    <td>
                        <span class="badge-event">
                            #{{ $paciente->id_paciente }}
                        </span>
                    </td>

                    <td>
                        {{ $paciente->nombre }}
                    </td>

                    <td>
                        {{ $paciente->ap_paterno }}
                    </td>

                    <td>
                        {{ $paciente->ap_materno ?? '—' }}
                    </td>

                    <td>
                        {{ $paciente->sexo ?? '—' }}
                    </td>

                    <td>
                        {{ $paciente->fecha_nacimiento ?? '—' }}
                    </td>

                    <td>
                        Servicio #{{ $paciente->id_servicio }}
                    </td>

                    <td class="text-center">

                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                            <a href="{{ route('pacientes.show', $paciente) }}"
                               class="action-btn">
                                Ver
                            </a>

                            <a href="{{ route('pacientes.edit', $paciente) }}"
                               class="action-btn">
                                Editar
                            </a>

                            <form action="{{ route('pacientes.destroy', $paciente) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar paciente?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="action-btn text-danger border-0">
                                    Eliminar
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="8">

                        <div class="empty-state">
                            <i class="bx bx-user-x fs-1 mb-3"></i>
                            <p>Sin pacientes registrados</p>
                        </div>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <small class="text-muted">
            Total: {{ $pacientes->total() }} registros
        </small>

        {{ $pacientes->links() }}

    </div>

</div>

</x-layouts.app>