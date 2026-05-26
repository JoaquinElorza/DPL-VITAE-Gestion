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

@if(session('error'))
<div class="alert alert-danger alert-dismissible mb-4">
    <i class="bx bx-x-circle me-1"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-warning alert-dismissible mb-4">
    <i class="bx bx-alert me-1"></i>
    <strong>Errores de validación:</strong>
    <ul class="mb-0 mt-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card h-100 border-0 rounded-4" style="transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(186, 85, 211, 0.05);" onmouseover="this.style.boxShadow='0 12px 25px rgba(186, 85, 211, 0.15)';" onmouseout="this.style.boxShadow='0 4px 15px rgba(186, 85, 211, 0.05)';">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="card-title fw-bold m-0" style="background: linear-gradient(135deg, #191970, #BA55D3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <i class="bx bx-time-five me-2" style="-webkit-text-fill-color: #BA55D3;"></i>Reservas por Despachar
                </h5>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive text-nowrap rounded-3">
                    <table class="table table-hover">
                        <thead style="background: rgba(186, 85, 211, 0.05) !important;">
                            <tr>
                                <th class="text-dark fw-semibold" style="background-color: transparent !important; border-bottom: 2px solid rgba(186, 85, 211, 0.2) !important; color: #8A2BE2 !important;">Guía</th>
                                <th class="text-dark fw-semibold" style="background-color: transparent !important; border-bottom: 2px solid rgba(186, 85, 211, 0.2) !important; color: #8A2BE2 !important;">Fecha Req.</th>
                                <th class="text-dark fw-semibold" style="background-color: transparent !important; border-bottom: 2px solid rgba(186, 85, 211, 0.2) !important; color: #8A2BE2 !important;">Servicio</th>
                                <th class="text-dark fw-semibold text-center" style="background-color: transparent !important; border-bottom: 2px solid rgba(186, 85, 211, 0.2) !important; color: #8A2BE2 !important;">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $reservasPorDespachar = \App\Models\Cotizacion::where('decision_cliente', 'confirmada')
                                    ->where('estado', 'Aceptada')
                                    ->get();
                            @endphp
                            @forelse($reservasPorDespachar as $res)
                            <tr style="transition: all 0.2s;">
                                <td>
                                    <span class="badge" style="background-color: rgba(138, 43, 226, 0.1); color: #8A2BE2; border-radius: 8px;">
                                        <i class="bx bx-hash me-1"></i>{{ $res->numero_guia }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="p-1 rounded-circle me-2" style="background-color: rgba(57, 51, 149, 0.1); color: #393395;">
                                            <i class="bx bx-calendar-event fs-6"></i>
                                        </div>
                                        <span class="fw-medium text-dark">{{ \Carbon\Carbon::parse($res->fecha_requerida)->format('d/m H:i') }}</span>
                                    </div>
                                </td>
                                <td><span class="fw-medium">{{ $res->tipo_servicio }}</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info rounded-pill px-3 shadow-sm" style="transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='none';"
                                            onclick="abrirDespacho({{ $res->id_cotizacion }}, '{{ $res->numero_guia }}')">
                                        <i class="bx bx-send me-1"></i>Despachar
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bx bx-check-circle fs-1 text-success mb-2 opacity-50"></i><br>
                                    No hay reservas pendientes por despachar
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 rounded-4" style="transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(57, 51, 149, 0.05);" onmouseover="this.style.boxShadow='0 12px 25px rgba(57, 51, 149, 0.15)';" onmouseout="this.style.boxShadow='0 4px 15px rgba(57, 51, 149, 0.05)';">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="card-title fw-bold m-0" style="color: #393395;">
                    <i class="bx bx-car fs-4 me-2 align-middle" style="color: #8A2BE2;"></i>Unidades Disponibles
                </h5>
            </div>
            <div class="card-body pt-4">
                <ul class="list-group list-group-flush">
                    @forelse($ambulanciasDisponibles as $amb)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3" style="border-bottom: 1px dashed rgba(0,0,0,0.1);">
                        <div class="d-flex align-items-center">
                            <div class="p-2 rounded-3 me-3" style="background-color: rgba(57, 51, 149, 0.1);">
                                <i class="bx bx-bus fs-5" style="color: #393395;"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block mb-1">{{ $amb->placa }}</span>
                                <small class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ $amb->tipo->nombre_tipo }}</small>
                            </div>
                        </div>
                        <span class="badge rounded-pill" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745; padding: 0.5rem 0.8rem;">Libre</span>
                    </li>
                    @empty
                    <li class="list-group-item px-0 py-4 text-center text-muted border-0">
                        <i class="bx bx-block fs-3 mb-2 opacity-50"></i><br>
                        Sin unidades disponibles
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4" style="color: #393395;"><i class="bx bx-calendar fs-4 me-2 align-middle" style="color: #BA55D3;"></i>Calendario de Actividades</h5>
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
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4">Iniciar Servicio</button>
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