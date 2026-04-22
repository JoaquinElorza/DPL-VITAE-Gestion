@section('title', 'Mi Panel')
<x-layouts.app title="Mi Panel">

@php
    $nombreCompleto = trim($user->nombre . ' ' . $user->ap_paterno . ' ' . $user->ap_materno);
    $hoy            = \Carbon\Carbon::now();
    $serviciosHoy   = $servicios->filter(fn($s) => \Carbon\Carbon::parse($s->fecha_hora)->isToday());
    $totalMes       = $esteMes->count();
@endphp

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<style>
    .estado-Activo     { background:#e8e8ff; color:#696cff; }
    .estado-Finalizado { background:#f0f0f0; color:#8592a3; }
    .estado-Cancelado  { background:#ffe0dc; color:#ff3e1d; }
    .estado-default    { background:#fff4de; color:#ffab00; }

    /* Calendario */
    .fc .fc-toolbar-title { font-size:1.05rem; font-weight:700; }
    .fc .fc-button-primary { background:#696cff; border-color:#696cff; }
    .fc .fc-button-primary:hover,
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active { background:#5f63f2; border-color:#5f63f2; }
    .fc-event { cursor:pointer; border-radius:6px !important; font-size:.78rem; }
    .fc-daygrid-event { padding:2px 5px !important; }

    /* Tarjeta de perfil compacta */
    .perfil-card-compact {
        cursor: pointer;
        transition: box-shadow .2s, transform .15s;
    }
    .perfil-card-compact:hover {
        box-shadow: 0 6px 24px rgba(105,108,255,.18) !important;
        transform: translateY(-2px);
    }
    /* Próximas rutas */
    .ruta-item { display:flex; align-items:center; gap:.75rem; padding:.55rem .75rem; border-radius:10px; transition:background .15s; }
    .ruta-item:hover { background:#f5f5ff; }
    .ruta-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
    /* toggle pass */
    .toggle-pass { cursor:pointer; }
</style>
@endsection

{{-- alertas --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible mb-4">
    <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- estadísticas --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div>
                    <span class="fw-medium d-block mb-1 small">Hoy</span>
                    <h3 class="card-title mb-0">{{ $serviciosHoy->count() }}</h3>
                    <small class="text-muted">{{ $serviciosHoy->count() === 1 ? 'ruta' : 'rutas' }}</small>
                </div>
                <span class="avatar-initial rounded bg-label-primary" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-calendar-check"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div>
                    <span class="fw-medium d-block mb-1 small">Este mes</span>
                    <h3 class="card-title mb-0">{{ $totalMes }}</h3>
                    <small class="text-muted">servicios</small>
                </div>
                <span class="avatar-initial rounded bg-label-info" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-calendar"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div>
                    <span class="fw-medium d-block mb-1 small">Próximas</span>
                    <h3 class="card-title mb-0">{{ $proximos->count() }}</h3>
                    <small class="text-muted">pendientes</small>
                </div>
                <span class="avatar-initial rounded bg-label-warning" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-time-five"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div>
                    <span class="fw-medium d-block mb-1 small">Completadas</span>
                    <h3 class="card-title mb-0">{{ $completados }}</h3>
                    <small class="text-muted">finalizadas</small>
                </div>
                <span class="avatar-initial rounded bg-label-success" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-check-circle"></i>
                </span>
            </div>
        </div>
    </div>
</div>

{{-- rutas de hoy --}}
@if($serviciosHoy->isNotEmpty())
<div class="card border-start border-4 border-primary mb-4">
    <div class="card-body py-2">
        <p class="fw-semibold small mb-2 text-primary">
            <i class="bx bx-map-pin me-1"></i>RUTAS DE HOY — {{ $hoy->translatedFormat('l d \d\e F') }}
        </p>
        <div class="d-flex flex-wrap gap-2">
            @foreach($serviciosHoy as $s)
            @php $badgeHoy = match($s->estado){ 'Activo'=>'primary','Finalizado'=>'secondary','Cancelado'=>'danger',default=>'warning' }; @endphp
            <div class="d-flex align-items-center gap-2 bg-light rounded px-3 py-2">
                <i class="bx bx-{{ $s->evento ? 'calendar-event' : 'ambulance' }} text-{{ $badgeHoy }}"></i>
                <div>
                    <div class="fw-semibold small">{{ $s->tipo ?? 'Servicio' }}{{ $s->evento ? ' · Evento' : '' }}</div>
                    <div class="text-muted" style="font-size:.72rem">
                        {{ \Carbon\Carbon::parse($s->fecha_hora)->format('H:i') }}
                        @if($s->hora_salida) – {{ \Carbon\Carbon::parse($s->hora_salida)->format('H:i') }}@endif
                        · {{ $s->ambulancia?->placa ?? '—' }}
                    </div>
                </div>
                <span class="badge bg-label-{{ $badgeHoy }} ms-1">{{ $s->estado }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- fila principal --}}
<div class="row g-4">

    {{-- columna izquierda --}}
    <div class="col-12 col-xl-8 d-flex flex-column gap-4">

        {{-- tarjeta de perfil --}}
        <div class="card perfil-card-compact mb-0"
             data-bs-toggle="modal" data-bs-target="#modal-perfil"
             title="Editar mi perfil">
            <div class="card-body py-3 d-flex align-items-center gap-3">
                <div class="perfil-initials lg flex-shrink-0">
                    {{ mb_strtoupper(mb_substr($user->nombre,0,1) . mb_substr($user->ap_paterno??'',0,1)) }}
                </div>
                <div class="flex-grow-1 min-width-0">
                    <div class="fw-bold text-truncate">{{ $nombreCompleto }}</div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                        @if($rol === 'operador')
                            <span class="role-chip bg-label-primary"><i class="bx bx-id-card"></i>Operador</span>
                            @foreach($ambulancias as $amb)
                                <span class="badge bg-label-secondary" style="font-size:.7rem;">
                                    <i class="bx bx-ambulance me-1"></i>{{ $amb->placa }}
                                </span>
                            @endforeach
                        @else
                            <span class="role-chip bg-label-success"><i class="bx bx-plus-medical"></i>Paramédico</span>
                        @endif
                        @if($user->telefono)
                            <span class="text-muted small"><i class="bx bx-phone me-1"></i>{{ $user->telefono }}</span>
                        @endif
                        <span class="text-muted small"><i class="bx bx-envelope me-1"></i>{{ $user->email }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0 text-muted">
                    <i class="bx bx-edit-alt fs-5"></i>
                </div>
            </div>
        </div>

        {{-- calendario --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title m-0">
                    <i class="bx bx-calendar me-1 text-primary"></i>Mi calendario
                </h5>
                <div class="d-flex gap-3 flex-wrap align-items-center">
                    <span class="d-flex align-items-center gap-1 small"><span style="width:10px;height:10px;border-radius:50%;background:#696cff;display:inline-block;"></span>Activo</span>
                    <span class="d-flex align-items-center gap-1 small"><span style="width:10px;height:10px;border-radius:50%;background:#ffab00;display:inline-block;"></span>Programado</span>
                    <span class="d-flex align-items-center gap-1 small"><span style="width:10px;height:10px;border-radius:50%;background:#8592a3;display:inline-block;"></span>Finalizado</span>
                    <span class="d-flex align-items-center gap-1 small"><span style="width:10px;height:10px;border-radius:50%;background:#ff3e1d;display:inline-block;"></span>Cancelado</span>
                </div>
            </div>
            <div class="card-body">
                <div id="mi-calendario"></div>
            </div>
        </div>

    </div>

    {{-- columna derecha --}}
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header py-3">
                <h6 class="card-title m-0">
                    <i class="bx bx-list-ul me-1 text-warning"></i>Próximas rutas
                </h6>
            </div>
            <div class="card-body p-2">
                @if($proximos->isEmpty())
                    <div class="text-center py-5">
                        <i class="bx bx-calendar-x text-muted" style="font-size:3rem;"></i>
                        <p class="text-muted small mt-2 mb-0">Sin rutas próximas programadas</p>
                    </div>
                @else
                    @foreach($proximos as $s)
                    @php
                        $fechaS   = \Carbon\Carbon::parse($s->fecha_hora);
                        $dotColor = match($s->estado){ 'Activo'=>'#696cff','Finalizado'=>'#8592a3','Cancelado'=>'#ff3e1d',default=>'#ffab00' };
                        $label    = $fechaS->isToday() ? 'Hoy' : ($fechaS->isTomorrow() ? 'Mañana' : $fechaS->format('d/m'));
                    @endphp
                    <div class="ruta-item">
                        <span class="ruta-dot" style="background:{{ $dotColor }}"></span>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-semibold small text-truncate">
                                {{ $s->tipo ?? 'Servicio' }}
                                @if($s->evento)<span class="badge bg-label-info ms-1" style="font-size:.62rem">Evento</span>@endif
                            </div>
                            <div class="d-flex gap-2" style="font-size:.7rem;color:#8592a3;">
                                <span><i class="bx bx-time-five"></i> {{ $fechaS->format('H:i') }}</span>
                                <span><i class="bx bx-ambulance"></i> {{ $s->ambulancia?->placa ?? '—' }}</span>
                            </div>
                        </div>
                        <span class="small fw-semibold {{ $fechaS->isToday() ? 'text-primary' : 'text-muted' }}">{{ $label }}</span>
                    </div>
                    @if(!$loop->last)<hr class="my-0 mx-3">@endif
                    @endforeach
                @endif
            </div>
            @if($servicios->isNotEmpty())
            <div class="card-footer bg-transparent border-top text-center">
                <small class="text-muted">Total asignados: <strong>{{ $servicios->count() }}</strong></small>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- modal editar perfil --}}
<div class="modal fade" id="modal-perfil" tabindex="-1" aria-labelledby="modal-perfil-label">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="perfil-initials">
                        {{ mb_strtoupper(mb_substr($user->nombre,0,1) . mb_substr($user->ap_paterno??'',0,1)) }}
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-perfil-label">Mi perfil</h5>
                        <small class="text-muted">Actualiza tus datos de contacto y acceso</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('empleado.perfil.update') }}" method="POST">
                @csrf @method('PUT')

                <div class="modal-body">

                    {{-- datos personales --}}
                    <p class="fw-semibold small text-uppercase text-muted mb-2">Datos personales</p>
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label small">Nombre(s) <span class="text-danger">*</span></label>
                            <input type="text" name="nombre"
                                class="form-control form-control-sm @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre', $user->nombre) }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Apellido paterno <span class="text-danger">*</span></label>
                            <input type="text" name="ap_paterno"
                                class="form-control form-control-sm @error('ap_paterno') is-invalid @enderror"
                                value="{{ old('ap_paterno', $user->ap_paterno) }}" required>
                            @error('ap_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Apellido materno</label>
                            <input type="text" name="ap_materno"
                                class="form-control form-control-sm @error('ap_materno') is-invalid @enderror"
                                value="{{ old('ap_materno', $user->ap_materno) }}">
                            @error('ap_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- contacto --}}
                    <p class="fw-semibold small text-uppercase text-muted mb-2">Contacto</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small">Teléfono</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $user->telefono) }}"
                                    placeholder="951 123 4567">
                                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- contraseña --}}
                    <p class="fw-semibold small text-uppercase text-muted mb-2">
                        Cambiar contraseña <span class="fw-normal text-lowercase">(dejar vacío para no cambiar)</span>
                    </p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Nueva contraseña</label>
                            <div class="input-group input-group-sm input-group-merge">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Mínimo 8 caracteres">
                                <span class="input-group-text toggle-pass" onclick="togglePass(this)">
                                    <i class="bx bx-hide"></i>
                                </span>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Confirmar contraseña</label>
                            <div class="input-group input-group-sm input-group-merge">
                                <input type="password" name="password_confirmation"
                                    class="form-control" placeholder="Repite la contraseña">
                                <span class="input-group-text toggle-pass" onclick="togglePass(this)">
                                    <i class="bx bx-hide"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bx bx-save me-1"></i> Guardar cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- modal detalle evento --}}
<div class="modal fade" id="modal-evento" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="modal-titulo">—</h5>
                    <span id="modal-estado-badge" class="small fw-semibold px-2 py-1 rounded mt-1 d-inline-block">—</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- datos del servicio --}}
                <dl class="row small mb-0" id="detalle-servicio">
                    <dt class="col-5 text-muted">Fecha inicio</dt><dd class="col-7" id="md-inicio">—</dd>
                    <dt class="col-5 text-muted">Fecha fin</dt><dd class="col-7" id="md-fin">—</dd>
                    <dt class="col-5 text-muted">Tipo</dt><dd class="col-7" id="md-tipo">—</dd>
                    <dt class="col-5 text-muted">Ambulancia</dt><dd class="col-7" id="md-amb">—</dd>
                    <dt class="col-5 text-muted">Tipo ambulancia</dt><dd class="col-7" id="md-tipo-amb">—</dd>
                    <dt id="lbl-dur" class="col-5 text-muted d-none">Duración</dt><dd id="md-dur" class="col-7 d-none">—</dd>
                    <dt id="lbl-per" class="col-5 text-muted d-none">Personas</dt><dd id="md-per" class="col-7 d-none">—</dd>
                    <dt class="col-5 text-muted">Observaciones</dt><dd class="col-7" id="md-obs">—</dd>
                </dl>
                {{-- datos de la reserva --}}
                <dl class="row small mb-0 d-none" id="detalle-reserva">
                    <dt class="col-5 text-muted">N° Guía</dt><dd class="col-7" id="md-guia">—</dd>
                    <dt class="col-5 text-muted">Fecha</dt><dd class="col-7" id="md-res-fecha">—</dd>
                    <dt class="col-5 text-muted">Tipo servicio</dt><dd class="col-7" id="md-res-tipo">—</dd>
                    <dt class="col-5 text-muted">Horas estimadas</dt><dd class="col-7" id="md-res-horas">—</dd>
                    <dt class="col-5 text-muted">Cliente</dt><dd class="col-7" id="md-res-cliente">—</dd>
                    <dt class="col-5 text-muted">Teléfono</dt><dd class="col-7" id="md-res-tel">—</dd>
                    <dt class="col-5 text-muted">Origen</dt><dd class="col-7" id="md-res-origen">—</dd>
                    <dt class="col-5 text-muted">Destino</dt><dd class="col-7" id="md-res-destino">—</dd>
                    <dt id="lbl-res-pac" class="col-5 text-muted d-none">Paciente</dt>
                    <dd id="md-res-pac" class="col-7 d-none">—</dd>
                    <dt class="col-5 text-muted">Costo total</dt><dd class="col-7 fw-bold text-success" id="md-res-costo">—</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Calendario ──
    var cal = new FullCalendar.Calendar(document.getElementById('mi-calendario'), {
        locale: 'es',
        initialView: 'dayGridMonth',
        height: 'auto',
        firstDay: 1,
        nowIndicator: true,
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: { today:'Hoy', month:'Mes', week:'Semana', list:'Lista' },
        events: @json($eventosCalendario),
        noEventsContent: 'No tienes rutas registradas',
        eventClick: function (info) {
            var e = info.event, p = e.extendedProps;
            var fmt = function (iso) {
                if (!iso) return '—';
                return new Date(iso).toLocaleString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
            };

            document.getElementById('modal-titulo').textContent = e.title;
            var badge = document.getElementById('modal-estado-badge');

            var detalleServ = document.getElementById('detalle-servicio');
            var detalleRes  = document.getElementById('detalle-reserva');

            if (p.tipo_evento === 'reserva') {
                detalleServ.classList.add('d-none');
                detalleRes.classList.remove('d-none');

                badge.textContent = 'Reserva confirmada';
                badge.className   = 'small fw-semibold px-2 py-1 rounded mt-1 d-inline-block';
                badge.style.cssText = 'background:#fff3e0;color:#e65100;';

                document.getElementById('md-guia').textContent       = p.guia;
                document.getElementById('md-res-fecha').textContent  = fmt(e.startStr);
                document.getElementById('md-res-tipo').textContent   = p.tipo_servicio;
                document.getElementById('md-res-horas').textContent  = p.horas + ' hrs';
                document.getElementById('md-res-cliente').textContent= p.cliente;
                document.getElementById('md-res-tel').textContent    = p.telefono;
                document.getElementById('md-res-origen').textContent = p.origen;
                document.getElementById('md-res-destino').textContent= p.destino;
                document.getElementById('md-res-costo').textContent  = p.costo;

                var lblPac = document.getElementById('lbl-res-pac');
                var mdPac  = document.getElementById('md-res-pac');
                if (p.paciente) {
                    lblPac.classList.remove('d-none');
                    mdPac.classList.remove('d-none');
                    mdPac.textContent = p.paciente;
                } else {
                    lblPac.classList.add('d-none');
                    mdPac.classList.add('d-none');
                }
            } else {
                detalleServ.classList.remove('d-none');
                detalleRes.classList.add('d-none');

                badge.textContent = p.estado;
                badge.style.cssText = '';
                badge.className   = 'small fw-semibold px-2 py-1 rounded mt-1 d-inline-block estado-' + (p.estado || 'default');

                document.getElementById('md-inicio').textContent   = fmt(e.startStr);
                document.getElementById('md-fin').textContent      = fmt(e.endStr) || '—';
                document.getElementById('md-tipo').textContent     = p.tipo;
                document.getElementById('md-amb').textContent      = p.ambulancia;
                document.getElementById('md-tipo-amb').textContent = p.tipo_amb;
                document.getElementById('md-obs').textContent      = p.observaciones;

                ['lbl-dur','md-dur','lbl-per','md-per'].forEach(function(id){
                    document.getElementById(id).classList.toggle('d-none', !p.es_evento);
                });
                if (p.es_evento) {
                    document.getElementById('md-dur').textContent = p.duracion;
                    document.getElementById('md-per').textContent = p.personas;
                }
            }

            new bootstrap.Modal(document.getElementById('modal-evento')).show();
        },
    });
    cal.render();

    // ── Toggle contraseña ──
    window.togglePass = function(btn) {
        var input = btn.closest('.input-group').querySelector('input');
        var icon  = btn.querySelector('i');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bx-hide', input.type === 'password');
        icon.classList.toggle('bx-show', input.type === 'text');
    };

    // ── Abrir modal de perfil si hay errores de validación ──
    @if($errors->any())
        new bootstrap.Modal(document.getElementById('modal-perfil')).show();
    @endif
});
</script>
@endsection

</x-layouts.app>
