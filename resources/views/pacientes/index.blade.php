<x-layouts.app :title="'Pacientes'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Pacientes</h5>
            <div class="d-flex gap-2 align-items-center">
                <form action="{{ route('pacientes.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-outline-secondary ms-1" title="Buscar"><i class="bx bx-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-outline-danger ms-1" title="Limpiar"><i class="bx bx-x"></i></a>
                    @endif
                </form>
                <a href="{{ route('pacientes.create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i> Nuevo
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
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
                        <td>{{ $paciente->id_paciente }}</td>
                        <td>{{ $paciente->nombre }}</td>
                        <td>{{ $paciente->ap_paterno }}</td>
                        <td>{{ $paciente->ap_materno ?? '—' }}</td>
                        <td>{{ $paciente->sexo ?? '—' }}</td>
                        <td>{{ $paciente->fecha_nacimiento ?? '—' }}</td>
                        <td>{{ $paciente->id_servicio }}</td>
                        <td class="text-center">
                            <a href="{{ route('pacientes.show', $paciente) }}"
                               class="btn btn-sm btn-outline-info me-1"
                               title="Ver detalle">
                                <i class="bx bx-show"></i> Ver
                            </a>
                            <a href="{{ route('pacientes.edit', $paciente) }}"
                               class="btn btn-sm btn-outline-warning me-1"
                               title="Editar">
                                <i class="bx bx-edit"></i> Editar
                            </a>
                            <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este paciente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bx bx-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $pacientes->total() }} registros</small>
            {{ $pacientes->links() }}
        </div>
    </div>
</x-layouts.app>
