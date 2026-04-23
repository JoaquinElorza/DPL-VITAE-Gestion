@section('title', 'Panel de Operador')
<x-layouts.app title="Panel de Operador">

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<style>
    .fc .fc-toolbar-title { font-size:1.1rem; font-weight:700; }
    .fc-event { cursor:pointer; }
</style>
@endsection

@if(session('success'))
<div class="alert alert-success alert-dismissible mb-4">
    <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0"><i class="bx bx-time-five me-2 text-warning"></i>Reservas por Despachar</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Guía</th>
                            <th>Fecha Req.</th>
                            <th>Servicio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $reservasPorDespachar = \App\Models\Cotizacion::where('decision_cliente', 'confirmada')
                                ->where('estado', 'Aceptada')
                                ->get();
                        @endphp
                        @forelse($reservasPorDespachar as $res)
                        <tr>
                            <td><span class="fw-bold">{{ $res->numero_guia }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($res->fecha_requerida)->format('d/m H:i') }}</td>
                            <td>{{ $res->tipo_servicio }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" 
                                        onclick="abrirDespacho({{ $res->id_cotizacion }}, '{{ $res->numero_guia }}')">
                                    Despachar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No hay reservas pendientes</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title m-0"><i class="bx bx-car me-2 text-success"></i>Unidades Disponibles</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($ambulanciasDisponibles as $amb)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <span class="fw-semibold">{{ $amb->placa }}</span><br>
                            <small class="text-muted">{{ $amb->tipo->nombre_tipo }}</small>
                        </div>
                        <span class="badge bg-label-success">Libre</span>
                    </li>
                    @empty
                    <li class="list-group-item px-0 text-muted">Sin unidades disponibles</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="calendar-operador"></div>
    </div>
</div>

<div class="modal fade" id="modalDespacho" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formDespacho" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Despachar Reserva <span id="spanGuia"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Ambulancia</label>
                        <select name="id_ambulancia" class="form-select" required>
                            <option value="">Seleccione una unidad...</option>
                            @foreach($ambulanciasDisponibles as $amb)
                                <option value="{{ $amb->id_ambulancia }}">{{ $amb->placa }} ({{ $amb->tipo->nombre_tipo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asignar Paramédicos</label>
                        <div class="bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                            @foreach($todosParamedicos as $p)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="paramedicos[]" value="{{ $p->id_usuario }}" id="p{{ $p->id_usuario }}">
                                <label class="form-check-label" for="p{{ $p->id_usuario }}">
                                    {{ $p->usuario->nombre }} {{ $p->usuario->ap_paterno }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Iniciar Servicio</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar-operador');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
        events: @json($eventosCalendario)
    });
    calendar.render();

    window.abrirDespacho = function(id, guia) {
        document.getElementById('spanGuia').textContent = guia;
        document.getElementById('formDespacho').action = "/mi-panel/despachar/" + id;
        new bootstrap.Modal(document.getElementById('modalDespacho')).show();
    }
});
</script>
@endsection

</x-layouts.app>