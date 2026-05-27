<x-layouts.app :title="'Cotizaciones'">
    
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

    @if(isset($insightTitulo))
    <div class="alert border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="background: {{ $insightColor === 'warning' ? 'linear-gradient(135deg, #FFB020 0%, #FF9A76 100%)' : 'linear-gradient(135deg, #38EF7D 0%, #11998E 100%)' }}; color: white; border-radius: 12px;">
        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
            <i class='bx {{ $insightColor === 'warning' ? 'bx-error-circle' : 'bx-check-shield' }} fs-3 text-white'></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1 text-white text-uppercase" style="letter-spacing: 0.5px; font-size: 0.8rem;">{{ $insightTitulo }}</h6>
            <p class="mb-0 text-white fw-medium" style="opacity: 0.95; font-size: 0.95rem;">{{ $insightMensaje }}</p>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom-0 pt-4 pb-3">
            <h5 class="mb-0 titulo-morado">Solicitudes de Cotización</h5>
            <div class="d-flex gap-3 align-items-center">
                <form action="{{ route('cotizaciones.index') }}" method="GET" class="d-flex shadow-sm rounded">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bx bx-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Buscar..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary" title="Buscar">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('cotizaciones.index') }}" class="btn btn-outline-secondary" title="Limpiar"><i class="bx bx-x"></i></a>
                        @endif
                    </div>
                </form>
                <span class="badge bg-label-primary rounded-pill px-3 py-2 fw-bold">{{ $cotizaciones->total() }} total</span>
            </div>
        </div>
        
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="fw-bold" style="color: var(--dpl-primary);">#</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Nombre</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Teléfono</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Tipo</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Fecha requerida</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);"><i class='bx bx-brain me-1'></i>Valoración IA</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Estado</th>
                        <th class="fw-bold" style="color: var(--dpl-primary);">Recibida</th>
                        <th class="text-center fw-bold" style="color: var(--dpl-primary);">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($cotizaciones as $cotizacion)
                    <tr>
                        <td><span class="badge bg-label-primary rounded-pill">#{{ $cotizacion->id_cotizacion }}</span></td>
                        <td class="fw-medium">{{ $cotizacion->nombre }}</td>
                        <td><i class="bx bx-phone text-muted me-1"></i> {{ $cotizacion->telefono }}</td>
                        <td><i class="bx bx-category text-muted me-1"></i> {{ $cotizacion->tipo_servicio }}</td>
                        <td><i class="bx bx-calendar text-muted me-1"></i> {{ $cotizacion->fecha_requerida ? \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') : '—' }}</td>
                        <td>
                            @if(isset($precios_ia[$cotizacion->id_cotizacion]))
                                @php 
                                    $c_cluster = $clusters_ia[$cotizacion->id_cotizacion] ?? 'Medio';
                                    $c_color = match($c_cluster) {
                                        'Bajo' => 'success',
                                        'Medio' => 'warning',
                                        'Alto' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-{{ $c_color }}">${{ number_format($precios_ia[$cotizacion->id_cotizacion], 2) }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;"><span class="badge bg-label-{{ $c_color }} rounded-pill" style="font-size: 0.6rem; padding: 0.2em 0.5em;">{{ $c_cluster }}</span></small>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $color = match($cotizacion->estado) {
                                    'Pendiente'   => 'warning',
                                    'En revisión' => 'info',
                                    'Respondida'  => 'success',
                                    'Cancelada'   => 'danger',
                                    default       => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-label-{{ $color }}">{{ $cotizacion->estado }}</span>
                        </td>
                        <td><i class="bx bx-time-five text-muted me-1"></i> {{ $cotizacion->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('cotizaciones.show', $cotizacion) }}"
                               class="btn btn-sm btn-icon btn-outline-info me-1"
                               data-bs-toggle="tooltip" title="Ver detalle">
                                <i class="bx bx-show"></i>
                            </a>
                            <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta cotización?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bx bx-calculator bx-lg mb-2 opacity-50"></i>
                            <p class="mb-0">Aún no hay solicitudes de cotización en el sistema.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer d-flex justify-content-between align-items-center bg-white border-top-0">
            <small class="text-muted fw-medium">Total: {{ $cotizaciones->total() }} registros</small>
            {{ $cotizaciones->links() }}
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