<x-layouts.app :title="'Clientes'">
    
    <style>
        :root {
            --dpl-primary: #696CFF;
            --dpl-primary-hover: #5f61e6;
        }

        .titulo-morado {
            color: #ffffff !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 
                -1px -1px 0 #696CFF,  
                 1px -1px 0 #696CFF,
                -1px  1px 0 #696CFF,
                 1px  1px 0 #696CFF,
                 0px  0px 6px #696CFF,
                 0px  0px 12px rgba(105, 108, 255, 0.8),
                 2px  2px 4px rgba(0, 0, 0, 0.25);
        }

        .btn-primary {
            background-color: var(--dpl-primary) !important;
            border-color: var(--dpl-primary) !important;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background-color: var(--dpl-primary-hover) !important;
            border-color: var(--dpl-primary-hover) !important;
        }
        
        .bg-label-primary {
            background-color: rgba(105, 108, 255, 0.16) !important;
            color: var(--dpl-primary) !important;
        }
    </style>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4 shadow-sm" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-4 pb-3">
            <h5 class="mb-0 titulo-morado">Clientes</h5>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary shadow-sm">
                <i class="bx bx-plus me-1"></i> Nuevo Cliente
            </a>
        </div>
        
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="fw-bold" style="color: var(--dpl-primary);">ID</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Nombre completo</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Email</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Teléfono</th>
                        <th class="text-center fw-bold" style="color: var(--dpl-primary);">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($clientes as $cliente)
                    <tr>
                        <td><span class="badge bg-label-primary rounded-pill">#{{ $cliente->id_usuario }}</span></td>
                        <td class="fw-medium">
                            {{ $cliente->usuario->nombre ?? '—' }}
                            {{ $cliente->usuario->ap_paterno ?? '' }}
                            {{ $cliente->usuario->ap_materno ?? '' }}
                        </td>
                        <td><i class="bx bx-envelope text-muted me-1"></i> {{ $cliente->usuario->email ?? '—' }}</td>
                        <td><i class="bx bx-phone text-muted me-1"></i> {{ $cliente->usuario->telefono ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('clientes.show', $cliente) }}"
                               class="btn btn-sm btn-icon btn-outline-info me-1"
                               data-bs-toggle="tooltip" title="Ver detalle">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('clientes.edit', $cliente) }}"
                               class="btn btn-sm btn-icon btn-outline-warning me-1"
                               data-bs-toggle="tooltip" title="Editar">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bx bx-group bx-lg mb-2 opacity-50"></i>
                            <p class="mb-0">Aún no hay clientes registrados en el sistema.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer d-flex justify-content-between align-items-center bg-white border-top-0">
            <small class="text-muted fw-medium">Total: {{ $clientes->total() }} registros</small>
            {{ $clientes->links() }}
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</x-layouts.app>