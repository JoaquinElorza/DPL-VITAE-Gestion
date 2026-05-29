<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud Enviada — {{ $empresa->nombre ?? config('app.name') }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    
    <style>
        :root {
            --navy-blue: #1A2B4C;
            --premium-purple: #6d28d9;
            --purple-light: #f3f0ff;
            --bg-color: #f8fafc;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background: var(--bg-color);
            margin: 0;
            overflow-x: hidden;
        }

        .split-layout {
            min-height: 100vh;
            display: flex;
            flex-wrap: wrap;
        }

        .success-hero {
            background: linear-gradient(135deg, var(--navy-blue) 0%, #2a4374 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }
        .success-hero::after {
            content: '';
            position: absolute;
            top: -50%; right: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
            pointer-events: none;
        }
        .success-icon-wrap {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            border: 2px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(5px);
        }

        .details-section {
            background: var(--bg-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
        }

        .folio-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(26, 43, 76, 0.05);
            margin-bottom: 2rem;
            position: relative;
            border-left: 6px solid var(--premium-purple);
        }
        .folio-label {
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }
        .folio-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--navy-blue);
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }
        
        .btn-premium {
            background: var(--premium-purple);
            color: #ffffff;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 50rem;
            border: none;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(109, 40, 217, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-premium:hover {
            background: #5b21b6;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(109, 40, 217, 0.4);
            color: #fff;
        }
        .btn-outline-premium {
            background: #ffffff;
            color: var(--navy-blue);
            border: 2px solid #e2e8f0;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 50rem;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-outline-premium:hover {
            border-color: var(--navy-blue);
            background: var(--navy-blue);
            color: #ffffff;
            transform: translateY(-2px);
        }

        @media (min-width: 992px) {
            .success-hero { min-height: 100vh; }
            .details-section { min-height: 100vh; }
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 split-layout">
        
        <div class="col-lg-5 success-hero">
            <div class="position-relative" style="z-index: 2;">
                <div class="success-icon-wrap">
                    <i class='bx bx-check-shield' style="font-size: 4rem; color: #38bdf8;"></i>
                </div>
                <h1 class="fw-bold mb-4" style="font-size: 3rem; line-height: 1.2;">¡Solicitud Recibida!</h1>
                <p class="fs-5 mb-0" style="opacity: 0.9; line-height: 1.6;">
                    Nuestro motor logístico ha procesado su requerimiento con éxito. El equipo médico y de coordinación está evaluando los detalles para brindarle el mejor servicio.
                </p>
            </div>
        </div>

        <div class="col-lg-7 details-section">
            <div style="max-width: 600px; width: 100%; margin: 0 auto;">
                
                <div class="d-flex align-items-center mb-5">
                    <i class='bx bx-plus-medical me-2 fs-3' style="color: var(--navy-blue);"></i>
                    <h4 class="mb-0 fw-bold" style="color: var(--navy-blue);">{{ $empresa->nombre ?? 'Vitae Ambulancias' }}</h4>
                </div>

                @if($numeroGuia)
                <div class="folio-card">
                    <span class="folio-label">Folio de Seguimiento Médico</span>
                    <div class="folio-number">{{ $numeroGuia }}</div>
                    <div class="d-flex align-items-start gap-2 text-muted">
                        <i class='bx bx-info-circle mt-1'></i>
                        <p class="small mb-0">Por favor, conserve este identificador único. Le servirá para consultar el estado en tiempo real de su cotización o servicio operativo.</p>
                    </div>
                </div>
                @endif

                @if($empresa && $empresa->telefono)
                <div class="d-flex align-items-center gap-3 mb-5 p-3 rounded-4" style="background: #f1f5f9; border: 1px solid #e2e8f0;">
                    <div class="bg-white p-2 rounded-circle shadow-sm">
                        <i class='bx bx-phone-call fs-4' style="color: var(--premium-purple);"></i>
                    </div>
                    <div>
                        <span class="d-block small text-muted fw-semibold">Asistencia Telefónica</span>
                        <span class="d-block fw-bold" style="color: var(--navy-blue); font-size: 1.1rem;">{{ $empresa->telefono }}</span>
                    </div>
                </div>
                @endif

                <div class="d-flex flex-column gap-3">
                    @auth
                        @php $u = auth()->user(); $u->loadMissing(['operador','paramedico','cliente']); @endphp
                        @if($u->esAdmin())
                            <a href="{{ route('cotizaciones.index') }}" class="btn-premium text-decoration-none">
                                <i class="bx bx-list-ul"></i> Panel de Gestión
                            </a>
                        @else
                            <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="btn-premium text-decoration-none">
                                <i class="bx bx-history"></i> Historial de Solicitudes
                            </a>
                        @endif
                    @else
                        <a href="{{ route('cotizaciones.rastrear') }}" class="btn-premium text-decoration-none">
                            <i class="bx bx-search-alt"></i> Rastrear mi Solicitud
                        </a>
                    @endauth
                    
                    <a href="{{ route('home') }}" class="btn-outline-premium text-decoration-none">
                        <i class="bx bx-home-alt"></i> Volver a la Página Principal
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
