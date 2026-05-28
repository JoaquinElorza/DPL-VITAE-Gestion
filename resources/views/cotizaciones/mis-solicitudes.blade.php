<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Solicitudes — {{ $empresa->nombre ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f5f5f9; }
        .navbar-brand img { height: 45px; object-fit: contain; }
        .status-badge { font-size: .78rem; }

        /* Overrides de colores unificados (Admin Estética) */
        .text-primary { color: #8A2BE2 !important; }
        .text-info { color: #393395 !important; }
        
        .btn { border-radius: 50rem !important; }
        
        .btn-primary { 
            background: linear-gradient(135deg, #BA55D3, #6A5ACD) !important; 
            border: none !important; 
            box-shadow: 0 4px 14px 0 rgba(186, 85, 211, 0.39) !important; 
            transition: all 0.3s ease;
            color: #fff !important;
        }
        .btn-primary:hover { 
            background: linear-gradient(135deg, #6A5ACD, #483D8B) !important; 
            box-shadow: 0 6px 20px rgba(186, 85, 211, 0.4) !important; 
            transform: translateY(-2px); 
        }
        
        .btn-outline-primary { 
            color: #BA55D3 !important; 
            border: 1px solid #BA55D3 !important; 
            background: transparent !important;
            transition: all 0.3s ease;
        }
        .btn-outline-primary:hover { 
            background: #BA55D3 !important;
            color: #fff !important; 
            transform: translateY(-2px);
        }
        
        .btn-outline-info {
            color: #393395 !important;
            border: 1px solid #393395 !important;
            background: transparent !important;
            transition: all 0.3s ease;
        }
        .btn-outline-info:hover {
            background: #393395 !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(57, 51, 149, 0.2) !important;
        }
        
        .btn-outline-secondary {
            color: #191970 !important;
            border: 1px solid rgba(25, 25, 112, 0.3) !important;
            background: #ffffff !important;
            transition: all 0.3s ease;
        }
        .btn-outline-secondary:hover {
            background: linear-gradient(135deg, rgba(25, 25, 112, 0.08), rgba(25, 25, 112, 0.1)) !important;
            color: #191970 !important;
            border-color: rgba(25, 25, 112, 0.6) !important;
            transform: translateY(-2px);
        }

        /* Estilo premium para la tabla */
        .table thead {
            background: linear-gradient(135deg, #BA55D3, #6A5ACD) !important;
        }
        .table thead th {
            background-color: transparent !important;
            color: #ffffff !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: none !important;
            border-top: none !important;
            padding: 1rem;
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(138, 43, 226, 0.04) !important;
            transform: scale(1.002);
            box-shadow: 0 2px 10px rgba(138, 43, 226, 0.05);
            z-index: 10;
            position: relative;
        }
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
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small">{{ auth()->user()->nombre }} {{ auth()->user()->ap_paterno }}</span>
            <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nueva solicitud
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-log-out me-1"></i> Salir
                </button>
            </form>
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container" style="max-width:860px">

        <h2 class="fw-bold mb-1" style="background: linear-gradient(135deg, #393395, #8A2BE2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Mis solicitudes de cotización</h2>
        <p class="text-muted mb-4">Aquí puedes ver el estado de todas tus solicitudes y responder a las propuestas.</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($cotizaciones->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <i class="bx bx-file-blank text-muted" style="font-size:4rem;"></i>
                <h5 class="mt-3 fw-semibold">No tienes solicitudes aún</h5>
                <p class="text-muted">Envía tu primera solicitud y te haremos llegar una propuesta.</p>
                <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary mx-auto" style="max-width:220px">
                    <i class="bx bx-plus me-1"></i> Solicitar cotización
                </a>
            </div>
        @else
        <div class="row g-4">
            @foreach($cotizaciones as $cot)
            @php
                $colorEstado = match($cot->estado) {
                    'Pendiente'   => 'warning',
                    'En revisión' => 'info',
                    'Aceptada'    => 'success',
                    'Cancelada'   => 'danger',
                    default       => 'secondary',
                };

                $bgIcon = match($colorEstado) {
                    'warning' => 'rgba(255, 171, 0, 0.1)',
                    'info'    => 'rgba(57, 51, 149, 0.1)',
                    'success' => 'rgba(113, 221, 55, 0.1)',
                    'danger'  => 'rgba(240, 128, 128, 0.1)',
                    default   => 'rgba(133, 146, 163, 0.1)',
                };
                
                $colIcon = match($colorEstado) {
                    'warning' => '#ffab00',
                    'info'    => '#393395',
                    'success' => '#71dd37',
                    'danger'  => '#F08080',
                    default   => '#8592a3',
                };

                $colorDecision = match($cot->decision_cliente) {
                    'confirmada' => 'success',
                    'declinada'  => 'danger',
                    default      => 'secondary',
                };
            @endphp
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 rounded-4" style="transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(25, 25, 112, 0.04);" onmouseover="this.style.boxShadow='0 12px 25px rgba(57, 51, 149, 0.12); this.style.transform=\'translateY(-4px)\'';" onmouseout="this.style.boxShadow='0 4px 15px rgba(25, 25, 112, 0.04)'; this.style.transform='none';">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: {{ $bgIcon }}; color: {{ $colIcon }};">
                                    <i class="bx {{ $cot->tipo_servicio === 'Traslado' ? 'bx-ambulance' : ($cot->tipo_servicio === 'Evento' ? 'bx-calendar-event' : 'bx-file') }} fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-info" style="letter-spacing: 0.5px;">{{ $cot->numero_guia }}</h5>
                                    <span class="text-muted small fw-medium">{{ $cot->tipo_servicio }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-auto">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-calendar text-muted fs-5 me-2"></i>
                                <span class="text-muted small">
                                    {{ $cot->fecha_requerida ? \Carbon\Carbon::parse($cot->fecha_requerida)->format('d/m/Y') : 'Fecha no especificada' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bx bx-info-circle text-muted fs-5 me-2"></i>
                                <span class="badge bg-{{ $colorEstado }} rounded-pill px-2 py-1 fw-medium status-badge">{{ $cot->estado }}</span>
                            </div>

                            <div class="p-3 rounded-3 mb-4" style="background-color: #f8f9fa;">
                                <div class="text-muted small mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Decisión</div>
                                @if($cot->decision_cliente)
                                    <span class="text-{{ $colorDecision }} fw-bold d-flex align-items-center gap-1">
                                        <i class="bx {{ $cot->decision_cliente === 'confirmada' ? 'bx-check-circle' : 'bx-x-circle' }}"></i>
                                        {{ $cot->decision_cliente === 'confirmada' ? 'Confirmada' : 'Declinada' }}
                                    </span>
                                @elseif($cot->estado === 'Aceptada')
                                    <span class="text-warning fw-bold d-flex align-items-center gap-1" style="color: #ffab00 !important;">
                                        <i class="bx bx-time-five"></i> Pendiente tu respuesta
                                    </span>
                                @else
                                    <span class="text-muted fw-medium">—</span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('cotizaciones.mi-estado', $cot) }}" class="btn btn-outline-info w-100">
                            <i class="bx bx-show me-1"></i> Ver detalles
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($cotizaciones->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $cotizaciones->links() }}
        </div>
        @endif
        @endif

    </div>
</section>

<footer class="py-4 mt-5" style="background: linear-gradient(135deg, #BA55D3, #6A5ACD); color: #ffffff;">
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} <strong class="text-white">{{ $empresa->nombre ?? config('app.name') }}</strong> — Todos los derechos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
