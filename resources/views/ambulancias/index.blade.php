<x-layouts.app :title="'Ambulancias'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ambulancias</h5>
            <a href="{{ route('ambulancias.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nueva
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
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
                        <td>{{ $ambulancia->id_ambulancia }}</td>
                        <td>{{ $ambulancia->placa }}</td>
                        <td>{{ $ambulancia->estado }}</td>
                        <td>{{ $ambulancia->tipo->nombre_tipo ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('ambulancias.show', $ambulancia) }}"
                               class="btn btn-sm btn-outline-info me-1"
                               title="Ver detalle">
                                <i class="bx bx-show"></i> Ver
                            </a>
                            <a href="{{ route('ambulancias.edit', $ambulancia) }}"
                               class="btn btn-sm btn-outline-warning me-1"
                               title="Editar">
                                <i class="bx bx-edit"></i> Editar
                            </a>
                            <form action="{{ route('ambulancias.destroy', $ambulancia) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta ambulancia?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bx bx-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $ambulancias->total() }} registros</small>
            {{ $ambulancias->links() }}
        </div>
    </div>
</x-layouts.app>
