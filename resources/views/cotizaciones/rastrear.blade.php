<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rastrear Cotización — {{ $empresa->nombre ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f5f5f9; }
        .navbar-brand img { height: 45px; object-fit: contain; }
        .guia-input { font-size: 1.1rem; letter-spacing: 2px; text-transform: uppercase; }
        .timeline { border-left: 3px solid #e0e0e0; padding-left: 1.5rem; }
        .timeline-step { position: relative; padding-bottom: 1.5rem; }
        .timeline-step::before {
            content: ''; position: absolute; left: -1.9rem; top: 4px;
            width: 14px; height: 14px; border-radius: 50%;
            background: #e0e0e0; border: 2px solid #fff;
        }
        .timeline-step.done::before   { background: #71dd37; }
        .timeline-step.active::before { background: #696cff; }
        .costo-badge { font-size: 2rem; font-weight: 700; color: #71dd37; }
        footer { background: #1a1a2e; color: #b0b0c0; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container">
        @if($empresa && $empresa->logo_nombre)
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('storage/' . $empresa->logo_nombre) }}" alt="{{ $empresa->nombre }}">
            </a>
        @else
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
                {{ $empresa->nombre ?? config('app.name') }}
            </a>
        @endif
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Volver
        </a>
    </div>
</nav>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="text-center mb-4">
                    <i class="bx bx-search-alt text-primary" style="font-size:3rem;"></i>
                    <h2 class="fw-bold mt-2">Rastrear Cotización</h2>
                    <p class="text-muted">Ingresa tu número de guía para consultar el estado de tu solicitud.</p>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <form action="{{ route('cotizaciones.rastrear') }}" method="GET">
                        <label class="form-label fw-semibold">Número de guía</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-barcode"></i></span>
                            <input type="text" name="guia" class="form-control guia-input"
                                placeholder="COT-2026-XXXXXX" value="{{ request('guia') }}" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-search"></i> Buscar
                            </button>
                        </div>
                        <small class="text-muted mt-1 d-block">El número de guía fue enviado al momento de realizar tu solicitud.</small>
                    </form>
                </div>

                @if($buscado)
                    @if($cotizacion)

                    @php
                        $color = match($cotizacion->estado) {
                            'Pendiente'   => 'warning',
                            'En revisión' => 'info',
                            'Aceptada'    => 'success',
                            'Cancelada'   => 'danger',
                            default       => 'secondary',
                        };
                        $pasos = ['Pendiente', 'En revisión', 'Aceptada'];
                        $indexActual = array_search($cotizacion->estado, $pasos);
                    @endphp

                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h5 class="fw-bold mb-0">{{ $cotizacion->numero_guia }}</h5>
                                <small class="text-muted">Enviada el {{ $cotizacion->created_at->format('d/m/Y \a \l\a\s H:i') }}</small>
                            </div>
                            <span class="badge bg-{{ $color }} fs-6">{{ $cotizacion->estado }}</span>
                        </div>

                        {{-- datos básicos --}}
                        <div class="mb-4">
                            <p class="mb-1"><strong>Nombre:</strong> {{ $cotizacion->nombre }}</p>
                            <p class="mb-1"><strong>Tipo de servicio:</strong> {{ $cotizacion->tipo_servicio }}</p>
                            @if($cotizacion->fecha_requerida)
                                <p class="mb-1"><strong>Fecha requerida:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') }}</p>
                            @endif
                            @if($cotizacion->origen)
                                <p class="mb-1"><strong>Origen:</strong> {{ $cotizacion->origen }}
                                    @if($cotizacion->lat_origen && $cotizacion->lng_origen)
                                        — <a href="https://www.google.com/maps?q={{ $cotizacion->lat_origen }},{{ $cotizacion->lng_origen }}" target="_blank" class="small text-primary"><i class="bx bx-map-alt me-1"></i>Ver mapa</a>
                                    @endif
                                </p>
                            @endif
                            @if($cotizacion->destino)
                                <p class="mb-1"><strong>Destino:</strong> {{ $cotizacion->destino }}
                                    @if($cotizacion->lat_destino && $cotizacion->lng_destino)
                                        — <a href="https://www.google.com/maps?q={{ $cotizacion->lat_destino }},{{ $cotizacion->lng_destino }}" target="_blank" class="small text-primary"><i class="bx bx-map-alt me-1"></i>Ver mapa</a>
                                    @endif
                                </p>
                            @endif
                        </div>

                        {{-- timeline --}}
                        <div class="timeline mb-4">
                            @foreach($pasos as $i => $paso)
                            <div class="timeline-step {{ $indexActual !== false && $i < $indexActual ? 'done' : ($i === $indexActual ? 'active' : '') }}">
                                <p class="fw-semibold mb-0">{{ $paso }}</p>
                                @if($indexActual !== false && $i < $indexActual)
                                    <small class="text-success">Completado</small>
                                @elseif($i === $indexActual)
                                    <small class="text-primary">Estado actual</small>
                                @else
                                    <small class="text-muted">Pendiente</small>
                                @endif
                            </div>
                            @endforeach
                            @if($cotizacion->estado === 'Cancelada')
                            <div class="timeline-step">
                                <p class="fw-semibold mb-0 text-danger">Cancelada</p>
                                <small class="text-danger">Esta solicitud fue rechazada</small>
                            </div>
                            @endif
                        </div>

                        {{-- estado: aceptada --}}
                        @if($cotizacion->estado === 'Aceptada')
                        <div class="alert alert-success rounded-4 mb-3">
                            <h6 class="fw-bold mb-2"><i class="bx bx-check-circle me-1"></i>¡Tu cotización fue aceptada!</h6>

                            @if($cotizacion->costo)
                            <div class="text-center my-3">
                                <div class="text-muted small">Costo total del servicio</div>
                                <div class="costo-badge">${{ number_format($cotizacion->costo, 2) }} <small class="fs-6">MXN</small></div>
                                @if($cotizacion->km_distancia)
                                    <small class="text-muted">Distancia: {{ $cotizacion->km_distancia }} km</small>
                                @endif
                            </div>
                            @endif

                            @if($cotizacion->incluye)
                            <div class="mb-2">
                                <strong>El servicio incluye:</strong>
                                <div class="mt-1" style="white-space:pre-line; font-size:.95rem">{{ $cotizacion->incluye }}</div>
                            </div>
                            @endif

                            @if($cotizacion->respuesta)
                            <hr class="my-2">
                            <p class="mb-0"><strong>Mensaje de {{ $empresa->nombre ?? 'la empresa' }}:</strong><br>{{ $cotizacion->respuesta }}</p>
                            @endif
                        </div>

                        {{-- aviso de registro --}}
                        <div class="card border-primary rounded-4 p-4 text-center">
                            <i class="bx bx-lock-alt text-primary" style="font-size:2.5rem;"></i>
                            <h5 class="fw-bold mt-2 mb-1">Para continuar debes registrarte</h5>
                            <p class="text-muted small mb-3">
                                Crea una cuenta para confirmar el servicio, registrar los datos del paciente
                                y procesar tu anticipo de forma segura.
                            </p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('register') }}?guia={{ $cotizacion->numero_guia }}"
                                   class="btn btn-primary btn-lg">
                                    <i class="bx bx-user-plus me-1"></i>Crear cuenta y confirmar servicio
                                </a>
                                <a href="{{ route('login') }}?guia={{ $cotizacion->numero_guia }}"
                                   class="btn btn-outline-primary">
                                    <i class="bx bx-log-in me-1"></i>Ya tengo cuenta — Iniciar sesión
                                </a>
                            </div>
                        </div>

                        {{-- estado: en revisión o pendiente --}}
                        @elseif($cotizacion->estado === 'En revisión')
                        <div class="alert alert-info mb-0">
                            <i class="bx bx-search me-1"></i>
                            Tu solicitud está siendo revisada. Te contactaremos pronto con una propuesta.
                        </div>

                        @elseif($cotizacion->estado === 'Pendiente')
                        <div class="alert alert-warning mb-0">
                            <i class="bx bx-time me-1"></i>
                            Tu solicitud está en espera de revisión. Pronto comenzaremos a atenderla.
                        </div>

                        {{-- estado: cancelada --}}
                        @elseif($cotizacion->estado === 'Cancelada')
                        <div class="alert alert-danger mb-0">
                            <strong><i class="bx bx-x-circle me-1"></i>Solicitud rechazada</strong>
                            @if($cotizacion->respuesta)
                                <p class="mt-1 mb-0">{{ $cotizacion->respuesta }}</p>
                            @endif
                        </div>

                        @else
                        <div class="alert alert-light border mb-0 text-muted">
                            <i class="bx bx-time me-1"></i> Aún no hay respuesta. Te contactaremos pronto.
                        </div>
                        @endif

                    </div>

                    @else
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                        <i class="bx bx-error-circle text-warning" style="font-size:3rem;"></i>
                        <h5 class="mt-3 mb-1">No encontrada</h5>
                        <p class="text-muted mb-0">No existe una cotización con el número <strong>{{ request('guia') }}</strong>. Verifica que lo hayas ingresado correctamente.</p>
                    </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</section>

<footer class="py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} <strong class="text-white">{{ $empresa->nombre ?? config('app.name') }}</strong> — Todos los derechos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
