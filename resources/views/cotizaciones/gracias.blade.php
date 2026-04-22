<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud enviada — {{ $empresa->nombre ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f5f5f9; }
        .check-circle {
            width: 90px; height: 90px; border-radius: 50%;
            background: #e8f5e9; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .guia-box {
            background: #f0f0ff; border: 2px dashed #696cff;
            border-radius: 12px; padding: 1.25rem;
        }
        .guia-number { font-size: 1.6rem; font-weight: 700; color: #696cff; letter-spacing: 2px; }
    </style>
</head>
<body>
<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5 px-3">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 text-center" style="max-width:520px; width:100%;">
        <div class="check-circle">
            <i class="bx bx-check text-success" style="font-size:3.5rem;"></i>
        </div>
        <h2 class="fw-bold mb-2">¡Solicitud enviada!</h2>
        <p class="text-muted mb-4">
            Hemos recibido tu solicitud. Nuestro equipo la revisará y se pondrá en contacto contigo.
        </p>

        @if($numeroGuia)
        <div class="guia-box mb-4">
            <p class="text-muted small mb-1">Tu número de guía es:</p>
            <div class="guia-number">{{ $numeroGuia }}</div>
            <p class="text-muted small mt-2 mb-0">
                Guarda este número para rastrear el estado de tu cotización.
            </p>
        </div>
        @endif

        @if($empresa && $empresa->telefono)
            <p class="text-muted small mb-4">
                También puedes llamarnos al <strong>{{ $empresa->telefono }}</strong>
            </p>
        @endif

        <div class="d-flex flex-column gap-2">
            @auth
                @php $u = auth()->user(); $u->loadMissing(['operador','paramedico','cliente']); @endphp
                @if($u->esAdmin())
                    <a href="{{ route('cotizaciones.index') }}" class="btn btn-primary">
                        <i class="bx bx-arrow-back me-1"></i> Ver todas las cotizaciones
                    </a>
                @else
                    <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="btn btn-primary">
                        <i class="bx bx-arrow-back me-1"></i> Ver mis solicitudes
                    </a>
                @endif
            @else
                <a href="{{ route('cotizaciones.rastrear') }}" class="btn btn-primary">
                    <i class="bx bx-search me-1"></i> Rastrear mi cotización
                </a>
            @endauth
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bx bx-home me-1"></i> Volver al inicio
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
