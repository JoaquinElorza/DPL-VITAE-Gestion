@section('title', 'Dashboard')
<x-layouts.app :title="__('Dashboard')">

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

    @php
        $totalServicios   = \App\Models\Servicio::count();
        $serviciosActivos = \App\Models\Servicio::where('estado', 'Activo')->count();
        $totalAmbulancia  = \App\Models\Ambulancia::count();
        $ambulanciasDisp  = \App\Models\Ambulancia::where('estado', 'Disponible')->count();
        $totalPacientes   = \App\Models\Paciente::count();
        $totalParamedicos = \App\Models\Paramedico::count();
        $totalClientes    = \App\Models\Cliente::count();
        $totalOperadores  = \App\Models\Operador::count();
    @endphp

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Servicios Activos</span>
                            <h3 class="card-title mb-2 text-primary fw-bold">{{ $serviciosActivos }}</h3>
                            <small class="text-muted">de {{ $totalServicios }} en total</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-ambulance bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Ambulancias Disponibles</span>
                            <h3 class="card-title mb-2 text-success fw-bold">{{ $ambulanciasDisp }}</h3>
                            <small class="text-muted">de {{ $totalAmbulancia }} en total</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-car bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Pacientes</span>
                            <h3 class="card-title mb-2 text-warning fw-bold">{{ $totalPacientes }}</h3>
                            <small class="text-muted">registrados</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-user-circle bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Personal</span>
                            <h3 class="card-title mb-2 text-info fw-bold">{{ $totalParamedicos + $totalOperadores }}</h3>
                            <small class="text-muted">{{ $totalParamedicos }} paramédicos</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-group bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(105, 108, 255, 0.05), rgba(59, 130, 246, 0.05));">
        <div class="card-body p-3">
            <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
                
                <div class="col-md-2">
                    <label class="form-label fw-bold" style="color: var(--dpl-primary); font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-search me-1"></i>Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="# ID o Tipo" class="form-control border-0 shadow-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold" style="color: var(--dpl-primary); font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-category me-1"></i>Tipo</label>
                    <select name="tipo" class="form-select border-0 shadow-sm">
                        <option value="">Todos los tipos</option>
                        @foreach ($tipos as $value => $label)
                            <option value="{{ $value }}" {{ request('tipo') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold" style="color: var(--dpl-primary); font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-check-circle me-1"></i>Estado</label>
                    <select name="estado" class="form-select border-0 shadow-sm">
                        <option value="">Todos</option>
                        @foreach ($estados as $value => $label)
                            <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold" style="color: var(--dpl-primary); font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-calendar me-1"></i>Desde</label>
                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control border-0 shadow-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold" style="color: var(--dpl-primary); font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-calendar-event me-1"></i>Hasta</label>
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control border-0 shadow-sm">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm"><i class="bx bx-search-alt me-1"></i> Buscar</button>
                    @if(request()->hasAny(['buscar', 'tipo', 'estado', 'ambulancia', 'fecha_inicio', 'fecha_fin']))
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100 bg-white shadow-sm"><i class="bx bx-x me-1"></i> Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="titulo-morado">Últimos Servicios</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Ambulancia</th>
                                <th>Costo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->id_servicio }}</td>
                                <td>{{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}</td>
                                <td>{{ $servicio->tipo ?? '—' }}</td>
                                <td>
                                    @php
                                        $badge = match($servicio->estado) {
                                            'Activo'     => 'success',
                                            'Finalizado' => 'secondary',
                                            'Cancelado'  => 'danger',
                                            default      => 'warning',
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $badge }}">{{ $servicio->estado }}</span>
                                </td>
                                <td>{{ $servicio->ambulancia->placa ?? '—' }}</td>
                                <td>${{ number_format($servicio->costo_total, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Sin servicios registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $servicios->links() }}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="titulo-morado">Resumen de Personal</h5>
                </div>
                <div class="card-body mt-3">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-primary"><i class="bx bx-plus-medical"></i></span></span>
                                <span class="fw-medium">Paramédicos</span>
                            </div>
                            <span class="badge bg-primary rounded-pill px-3">{{ $totalParamedicos }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-success"><i class="bx bx-id-card"></i></span></span>
                                <span class="fw-medium">Operadores</span>
                            </div>
                            <span class="badge bg-success rounded-pill px-3">{{ $totalOperadores }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-info"><i class="bx bx-user"></i></span></span>
                                <span class="fw-medium">Clientes</span>
                            </div>
                            <span class="badge bg-info rounded-pill px-3">{{ $totalClientes }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="titulo-morado">Estado de Ambulancias</h5>
                </div>
                <div class="card-body mt-3">
                    @php
                        $estadosAmb = \App\Models\Ambulancia::selectRaw('estado, count(*) as total')->groupBy('estado')->get();
                    @endphp
                    @forelse($estadosAmb as $est)
                    @php
                        $color = match($est->estado) {
                            'Disponible' => 'success',
                            'En servicio', 'En Servicio' => 'warning',
                            'Mantenimiento' => 'danger',
                            default => 'secondary',
                        };
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-medium">{{ $est->estado }}</span>
                        <div class="d-flex align-items-center gap-3">
                            <div class="progress" style="width:150px; height:8px;">
                                <div class="progress-bar bg-{{ $color }}" style="width:{{ $totalAmbulancia ? ($est->total / $totalAmbulancia) * 100 : 0 }}%"></div>
                            </div>
                            <span class="badge bg-label-{{ $color }} px-2">{{ $est->total }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">Sin ambulancias registradas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>