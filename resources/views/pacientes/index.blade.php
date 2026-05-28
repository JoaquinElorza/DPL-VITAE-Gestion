<x-layouts.app :title="'Pacientes'">



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

{{-- =========================
    TABLE PANEL
========================= --}}
<div class="services-panel">

    <div class="services-panel-header">

        <div>
            <span class="panel-label">Base clínica</span>
            <h2 class="panel-title">Listado de pacientes</h2>
        </div>

    </div>

    <div class="services-table-wrapper">

        <table class="services-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Ap. Paterno</th>
                    <th>Ap. Materno</th>
                    <th>Sexo</th>
                    <th>Fecha Nac.</th>
                    <th>Servicio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($pacientes as $paciente)

                <tr>

                    <td class="fw-bold">
                        #{{ $paciente->id_paciente }}
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
                        {{ $paciente->id_servicio }}
                    </td>

                    <td>
                        <div class="d-flex gap-2">

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
                                  onsubmit="return confirm('¿Eliminar paciente?')">

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

    <div class="services-pagination d-flex justify-content-between align-items-center mt-3">

        <small class="text-muted">
            Total: {{ $pacientes->total() }} registros
        </small>

        {{ $pacientes->links() }}

    </div>

</div>

</x-layouts.app>