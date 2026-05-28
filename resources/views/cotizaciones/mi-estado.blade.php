<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud {{ $cotizacion->numero_guia }} — {{ $empresa->nombre ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f5f5f9; }
        .navbar-brand img { height: 45px; object-fit: contain; }
        .timeline-step {
            display: flex; align-items: flex-start; gap: 1rem; padding-bottom: 1.5rem;
            position: relative;
        }
        .timeline-step:not(:last-child)::before {
            content: ''; position: absolute; left: 15px; top: 32px;
            width: 2px; height: calc(100% - 32px); background: #dee2e6;
        }
        .timeline-icon {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: .9rem;
        }
        .timeline-icon.done  { background: #198754; color: #fff; }
        .timeline-icon.active { background: #8A2BE2; color: #fff; }
        .timeline-icon.wait  { background: #dee2e6; color: #6c757d; }
        
        /* Overrides de colores unificados (Admin Estética) */
        .text-primary { color: #8A2BE2 !important; }
        .text-info { color: #393395 !important; }
        
        .form-control, .form-select {
            background-color: transparent !important;
            border: 1px solid rgba(138, 43, 226, 0.3) !important;
            border-radius: 8px;
            transition: all 0.2s;
            padding: 0.6rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #8A2BE2 !important;
            box-shadow: 0 0 0 0.25rem rgba(138, 43, 226, 0.15) !important;
            background-color: #fff !important;
        }
        .form-label {
            font-weight: 600;
            color: #566a7f;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
        }
        .card {
            box-shadow: 0 4px 24px 0 rgba(25, 25, 112, 0.08) !important;
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 8px 32px 0 rgba(25, 25, 112, 0.12) !important;
        }
        
        
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
        .timeline-icon.wait  { background: #dee2e6; color: #6c757d; }
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
        <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Mis solicitudes
        </a>
    </div>
</nav>

@php
    $colorEstado = match($cotizacion->estado) {
        'Pendiente'   => 'warning',
        'En revisión' => 'info',
        'Aceptada'    => 'success',
        'Cancelada'   => 'danger',
        default       => 'secondary',
    };

    $etapas = [
        ['label' => 'Solicitud enviada',    'estados' => ['Pendiente','En revisión','Aceptada','Cancelada']],
        ['label' => 'En revisión',          'estados' => ['En revisión','Aceptada','Cancelada']],
        ['label' => 'Propuesta recibida',   'estados' => ['Aceptada']],
    ];

    $puedeDecidirCliente = $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === null;
@endphp

<section class="py-5">
    <div class="container" style="max-width:1100px">

        {{-- alertas de sesión --}}
        @foreach(['success' => 'alert-success', 'info' => 'alert-info'] as $key => $cls)
            @if(session($key))
            <div class="alert {{ $cls }} alert-dismissible mb-4 shadow-sm border-0 rounded-3">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        @endforeach

        <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="background: linear-gradient(135deg, #393395, #8A2BE2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Detalles de Solicitud {{ $cotizacion->numero_guia }}
                </h2>
                <span class="text-muted small fw-medium">Enviada el {{ $cotizacion->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <span class="badge bg-{{ $colorEstado }} rounded-pill fs-6 px-4 py-2 shadow-sm">{{ $cotizacion->estado }}</span>
        </div>

        <div class="row g-4">
            <!-- Columna Izquierda: Timeline y Datos Originales -->
            <div class="col-lg-5 d-flex flex-column">
                {{-- timeline --}}
                <div class="card border-0 rounded-4 p-4 mb-4">
                    <h6 class="fw-bold mb-4" style="color: #393395;">Progreso de tu solicitud</h6>

                    <div class="timeline-step">
                        <div class="timeline-icon done"><i class="bx bx-check"></i></div>
                        <div>
                            <div class="fw-semibold">Solicitud enviada</div>
                            <div class="text-muted small">{{ $cotizacion->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="timeline-step">
                        @if(in_array($cotizacion->estado, ['En revisión','Aceptada','Cancelada']))
                            <div class="timeline-icon done"><i class="bx bx-check"></i></div>
                        @else
                            <div class="timeline-icon wait"><i class="bx bx-time"></i></div>
                        @endif
                        <div>
                            <div class="fw-semibold">En revisión</div>
                            <div class="text-muted small">Nuestro equipo está preparando tu propuesta</div>
                        </div>
                    </div>

                    <div class="timeline-step">
                        @if($cotizacion->estado === 'Cancelada')
                            <div class="timeline-icon" style="background:#F08080;color:#fff;"><i class="bx bx-x"></i></div>
                        @elseif($cotizacion->estado === 'Aceptada')
                            <div class="timeline-icon done"><i class="bx bx-check"></i></div>
                        @else
                            <div class="timeline-icon wait"><i class="bx bx-time"></i></div>
                        @endif
                        <div>
                            <div class="fw-semibold">
                                @if($cotizacion->estado === 'Cancelada') Solicitud cancelada
                                @elseif($cotizacion->estado === 'Aceptada') Propuesta lista
                                @else Propuesta pendiente
                                @endif
                            </div>
                            @if($cotizacion->estado === 'Aceptada')
                                <div class="text-muted small">Revisa los detalles y confirma o declina el servicio</div>
                            @endif
                        </div>
                    </div>

                    @if($cotizacion->estado === 'Aceptada')
                    <div class="timeline-step" style="padding-bottom:0">
                        @if($cotizacion->decision_cliente === 'confirmada')
                            <div class="timeline-icon done"><i class="bx bx-check"></i></div>
                        @elseif($cotizacion->decision_cliente === 'declinada')
                            <div class="timeline-icon" style="background:#F08080;color:#fff;"><i class="bx bx-x"></i></div>
                        @else
                            <div class="timeline-icon active"><i class="bx bx-question-mark"></i></div>
                        @endif
                        <div>
                            <div class="fw-semibold">Tu decisión</div>
                            @if($cotizacion->decision_cliente === 'confirmada')
                                <div class="text-success small fw-semibold">Confirmaste el servicio</div>
                            @elseif($cotizacion->decision_cliente === 'declinada')
                                <div class="text-danger small fw-semibold">Declinaste la propuesta</div>
                            @else
                                <div class="text-warning small fw-bold" style="color: #ffab00 !important;">Pendiente tu respuesta</div>
                            @endif
                            @if($cotizacion->comentario_cliente)
                                <div class="text-muted small mt-1">{{ $cotizacion->comentario_cliente }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                {{-- datos de la solicitud --}}
                <div class="card border-0 rounded-4 p-4 mb-4 shadow-sm">
                    <h6 class="fw-bold mb-3" style="color: #393395;">Datos originales de tu solicitud</h6>
                    <dl class="row small mb-0 g-3">
                        <dt class="col-5 text-muted">Tipo de servicio</dt>
                        <dd class="col-7 fw-medium">{{ $cotizacion->tipo_servicio }}</dd>

                        @if($cotizacion->fecha_requerida)
                        <dt class="col-5 text-muted">Fecha requerida</dt>
                        <dd class="col-7 fw-medium">{{ \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') }}</dd>
                        @endif

                        @if($cotizacion->origen)
                        <dt class="col-5 text-muted">Origen</dt>
                        <dd class="col-7 fw-medium">{{ $cotizacion->origen }}</dd>
                        @endif

                        @if($cotizacion->destino)
                        <dt class="col-5 text-muted">Destino</dt>
                        <dd class="col-7 fw-medium">{{ $cotizacion->destino }}</dd>
                        @endif

                        @if($cotizacion->descripcion)
                        <dt class="col-5 text-muted">Descripción</dt>
                        <dd class="col-7 fw-medium">{{ $cotizacion->descripcion }}</dd>
                        @endif

                        @if(isset($precio_ia) && $precio_ia > 0)
                        <dt class="col-5 text-muted mt-3"><i class='bx bx-brain text-primary me-1'></i>Precio Estimado (IA)</dt>
                        <dd class="col-7 fw-bold mt-3" style="color: #393395;">${{ number_format($precio_ia, 2) }} MXN</dd>
                        @endif
                    </dl>
                </div>

                <div class="mt-auto">
                    <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="btn btn-outline-secondary px-4 py-2 w-100" style="border-radius: 50rem;">
                        <i class="bx bx-arrow-back me-1"></i> Volver a mis solicitudes
                    </a>
                </div>
            </div>

            <!-- Columna Derecha: Propuesta, Anticipo y Acciones -->
            <div class="col-lg-7 d-flex flex-column gap-4">
                @if(in_array($cotizacion->estado, ['Pendiente', 'En revisión']))
                    <div class="card border-0 rounded-4 p-5 text-center h-100 d-flex justify-content-center align-items-center shadow-sm" style="background: rgba(138, 43, 226, 0.02);">
                        <div>
                            <i class='bx bx-time-five text-primary mb-3' style="font-size: 5rem; opacity: 0.6;"></i>
                            <h5 class="fw-bold" style="color: #393395;">Estamos evaluando tu solicitud</h5>
                            <p class="text-muted small mb-0 px-md-4">En breve, nuestro equipo de coordinación médica estructurará una propuesta a la medida de tus necesidades y publicaremos aquí tu cotización formal con todos los detalles.</p>
                        </div>
                    </div>
                @else
                
                {{-- propuesta del equipo --}}
                @if($cotizacion->estado === 'Aceptada')
                <div class="card border-0 rounded-4 border-start border-4 border-success p-4">
                    <h5 class="fw-bold text-success mb-3"><i class="bx bx-package me-1"></i>Propuesta de servicio</h5>

                    <div class="table-responsive mb-3 rounded-3" style="border: 1px solid rgba(113, 221, 55, 0.2);">
                        <table class="table table-sm mb-0 align-middle">
                            <tbody>
                                @if($cotizacion->km_distancia)
                                <tr>
                                    <td class="text-muted py-2 ps-3">Kilómetros</td>
                                    <td class="text-end py-2">{{ $cotizacion->km_distancia }} km × ${{ number_format($cotizacion->costo_km_unitario, 2) }}</td>
                                    <td class="text-end fw-bold py-2 pe-3">${{ number_format($cotizacion->km_distancia * $cotizacion->costo_km_unitario, 2) }}</td>
                                </tr>
                                @endif
                                @if($cotizacion->costo_ambulancia)
                                <tr>
                                    <td class="text-muted py-2 ps-3">Ambulancia</td>
                                    <td class="text-end py-2">{{ $cotizacion->ambulancia?->tipo?->nombre_tipo ?? '—' }}</td>
                                    <td class="text-end fw-bold py-2 pe-3">${{ number_format($cotizacion->costo_ambulancia, 2) }}</td>
                                </tr>
                                @endif
                                @if($cotizacion->costo_paramedicos)
                                <tr>
                                    <td class="text-muted py-2 ps-3">Paramédicos</td>
                                    <td class="text-end py-2">{{ count($cotizacion->paramedicos_ids ?? []) }} persona(s) × {{ $cotizacion->horas_servicio }}h</td>
                                    <td class="text-end fw-bold py-2 pe-3">${{ number_format($cotizacion->costo_paramedicos, 2) }}</td>
                                </tr>
                                @endif
                                @if($cotizacion->costo_insumos)
                                <tr>
                                    <td class="text-muted py-2 ps-3">Insumos</td>
                                    <td class="text-end py-2">{{ count($cotizacion->insumos_seleccionados ?? []) }} artículo(s)</td>
                                    <td class="text-end fw-bold py-2 pe-3">${{ number_format($cotizacion->costo_insumos, 2) }}</td>
                                </tr>
                                @endif
                                @if(isset($precio_ia) && $precio_ia > 0)
                                <tr style="background-color: rgba(138, 43, 226, 0.05);">
                                    <td colspan="2" class="fw-semibold py-2 ps-3" style="color: #8A2BE2;"><i class='bx bx-brain me-1'></i>Precio original calculado por IA</td>
                                    <td class="text-end fw-bold py-2 pe-3" style="color: #8A2BE2;"><del class="text-muted me-1 small" style="font-size:0.75rem"></del>${{ number_format($precio_ia, 2) }}</td>
                                </tr>
                                @endif
                                <tr style="background-color: rgba(113, 221, 55, 0.1);">
                                    <td colspan="2" class="fw-bold fs-5 py-3 ps-3 text-success">Costo Final a Pagar</td>
                                    <td class="text-end fw-bold fs-5 py-3 pe-3 text-success">${{ number_format($cotizacion->costo, 2) }} MXN</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Explicabilidad del Precio Final -->
                    <div class="mb-3 p-3 rounded-3" style="background-color: rgba(138, 43, 226, 0.05); border: 1px solid rgba(138, 43, 226, 0.1);">
                        <a data-bs-toggle="collapse" href="#collapseExplicacionFinal" role="button" aria-expanded="false" aria-controls="collapseExplicacionFinal" style="color: #393395; text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; font-weight: 600;">
                            <i class='bx bx-info-circle me-2 fs-5'></i> ¿Cómo se calculó tu tarifa final? <i class='bx bx-chevron-down ms-auto'></i>
                        </a>
                        <div class="collapse mt-3" id="collapseExplicacionFinal">
                            <div class="small" style="color: #566a7f;">
                                El equipo administrativo y médico revisó tu solicitud y estructuró esta tarifa considerando los siguientes factores:
                                <ul class="mt-2 mb-0 ps-0" style="list-style: none;">
                                    @if($cotizacion->km_distancia)
                                    <li class="mb-2"><i class='bx bx-map-alt me-2 text-primary'></i> <strong>Distancia de ruta:</strong> Traslado seguro calculando una ruta de {{ $cotizacion->km_distancia }} km.</li>
                                    @endif
                                    
                                    <li class="mb-2"><i class='bx bx-time-five me-2 text-primary'></i> <strong>Tiempo de servicio:</strong> Cobertura base estipulada por {{ $cotizacion->horas_servicio ?? 1 }} hora(s).</li>
                                    
                                    @if($cotizacion->paramedicos_ids)
                                    <li class="mb-2"><i class='bx bx-user-plus me-2 text-primary'></i> <strong>Personal médico:</strong> Asignación de {{ is_array($cotizacion->paramedicos_ids) ? count($cotizacion->paramedicos_ids) : 0 }} paramédico(s) altamente capacitado(s) para el viaje.</li>
                                    @endif
                                    
                                    @if($cotizacion->insumos_seleccionados)
                                    <li><i class='bx bx-injection me-2 text-primary'></i> <strong>Insumos especiales:</strong> Contempla {{ is_array($cotizacion->insumos_seleccionados) ? count($cotizacion->insumos_seleccionados) : 0 }} recurso(s) médico(s) adicional(es) para las necesidades específicas del paciente.</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($cotizacion->incluye)
                    <div class="mb-3 p-3 rounded-3" style="background-color: #f8f9fa;">
                        <strong class="small d-block mb-1 text-primary">El servicio incluye:</strong>
                        <div class="small text-muted" style="white-space:pre-line">{{ $cotizacion->incluye }}</div>
                    </div>
                    @endif

                    @if($cotizacion->respuesta)
                    <div class="alert alert-success py-3 mb-0 small border-0 shadow-sm">
                        <strong class="d-block mb-1"><i class="bx bx-message-dots me-1"></i> Mensaje del equipo médico:</strong> 
                        {{ $cotizacion->respuesta }}
                    </div>
                    @endif
                </div>
                @endif

                {{-- anticipo --}}
                @if($cotizacion->estado === 'Aceptada' && $cotizacion->anticipo > 0)
                @php $pagoAprobado = $cotizacion->mp_pago_estado === 'approved'; @endphp
                <div class="card border-0 rounded-4 p-4 {{ $pagoAprobado ? 'border-start border-4 border-success' : 'border-start border-4 border-warning' }}">
                    <h6 class="fw-bold mb-1" style="color: #393395;">
                        <i class="bx bx-credit-card me-1"></i>Anticipo requerido
                    </h6>

                    @if($pagoAprobado)
                        <div class="alert alert-success py-3 mb-0 mt-3 small border-0 shadow-sm">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-check-circle fs-4"></i>
                                <div>
                                    <strong class="d-block">Anticipo pagado con éxito (${{ number_format($cotizacion->anticipo, 2) }} MXN)</strong>
                                    @if($cotizacion->mp_payment_id)
                                        <span class="text-muted">ID de transacción: {{ $cotizacion->mp_payment_id }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($cotizacion->mp_pago_estado === 'pending')
                        <div class="alert alert-warning py-3 mb-3 mt-3 small border-0 shadow-sm">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-time fs-4 text-warning"></i>
                                <div>
                                    <strong>Pago en proceso de acreditación</strong><br>
                                    <span class="text-muted">Puedes intentar de nuevo si ocurrió un error en la pasarela.</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('cotizaciones.pago.iniciar', $cotizacion) }}"
                           class="btn btn-warning w-100 fw-bold shadow-sm" style="color: #fff !important; background: #ffab00 !important; border: none;">
                            <i class="bx bxl-mastercard me-1"></i> Reintentar pago — ${{ number_format($cotizacion->anticipo, 2) }} MXN
                        </a>
                    @else
                        <p class="text-muted small mt-2 mb-4">
                            Para poder confirmar este servicio de ambulancia y agendarlo, es necesario cubrir un anticipo de
                            <strong class="text-primary">${{ number_format($cotizacion->anticipo, 2) }} MXN</strong> a través de nuestra plataforma segura.
                        </p>
                        <a href="{{ route('cotizaciones.pago.iniciar', $cotizacion) }}"
                           class="btn w-100 fw-bold shadow-sm" style="background: #393395; color: #fff; padding: 12px; border-radius: 50rem;">
                            <i class="bx bxl-mastercard me-1"></i> Pagar anticipo ahora
                        </a>
                    @endif
                </div>
                @endif

                {{-- botones confirmar o declinar --}}
                @if($puedeDecidirCliente)
                <div class="card border-0 rounded-4 p-4">
                    <h5 class="fw-bold mb-1" style="color: #393395;">¿Deseas contratar este servicio?</h5>
                    <p class="text-muted small mb-4">Una vez que confirmes, nuestro equipo de atención se pondrá en contacto para afinar detalles.</p>

                    @if($errors->any())
                    <div class="alert alert-danger small mb-3 border-0 shadow-sm">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('cotizaciones.confirmar', $cotizacion) }}" method="POST">
                        @csrf

                        @if($cotizacion->tipo_servicio === 'Traslado')
                        <div class="alert alert-warning small mb-4 border-0 shadow-sm d-flex gap-2">
                            <i class="bx bx-lock-alt fs-4 text-warning"></i>
                            <div>
                                <strong class="d-block">Datos confidenciales del paciente</strong>
                                Esta información es necesaria para coordinar tu traslado y será tratada con total privacidad médica.
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nombre completo del paciente <span class="text-danger">*</span></label>
                                <input type="text" name="paciente_nombre" class="form-control @error('paciente_nombre') is-invalid @enderror"
                                    value="{{ old('paciente_nombre') }}" placeholder="Ej. María López">
                                @error('paciente_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de nacimiento <span class="text-danger">*</span></label>
                                <input type="date" name="paciente_nacimiento" class="form-control @error('paciente_nacimiento') is-invalid @enderror"
                                    value="{{ old('paciente_nacimiento') }}">
                                @error('paciente_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CURP (opcional)</label>
                                <input type="text" name="paciente_curp" class="form-control @error('paciente_curp') is-invalid @enderror"
                                    value="{{ old('paciente_curp') }}" placeholder="18 caracteres" maxlength="18" style="text-transform:uppercase">
                                @error('paciente_curp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de sangre</label>
                                <select name="paciente_tipo_sangre" class="form-select @error('paciente_tipo_sangre') is-invalid @enderror">
                                    <option value="">— No sé / Prefiero no decir —</option>
                                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
                                        <option value="{{ $tipo }}" {{ old('paciente_tipo_sangre') === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                                @error('paciente_tipo_sangre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Diagnóstico / Motivo médico <span class="text-danger">*</span></label>
                                <textarea name="paciente_diagnostico" rows="3" class="form-control @error('paciente_diagnostico') is-invalid @enderror"
                                    placeholder="Describe brevemente el estado de salud">{{ old('paciente_diagnostico') }}</textarea>
                                @error('paciente_diagnostico')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alergias conocidas</label>
                                <input type="text" name="paciente_alergias" class="form-control"
                                    value="{{ old('paciente_alergias') }}" placeholder="Medicamentos, penicilina, etc.">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Médico tratante</label>
                                <input type="text" name="paciente_medico" class="form-control"
                                    value="{{ old('paciente_medico') }}" placeholder="Nombre del doctor">
                            </div>
                        </div>
                        <hr class="my-4" style="border-color: rgba(138, 43, 226, 0.2);">
                        @endif

                        <div class="mb-4">
                            <label class="form-label">Comentario adicional (opcional)</label>
                            <textarea name="comentario_cliente" rows="2" class="form-control"
                                placeholder="Indicaciones de acceso, referencias de domicilio, etc.">{{ old('comentario_cliente') }}</textarea>
                        </div>

                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" class="btn btn-success flex-grow-1 fw-bold shadow-sm" style="padding: 12px;">
                                <i class="bx bx-check-circle me-1 fs-5 align-middle"></i> Confirmar servicio
                            </button>

                            <button type="button" class="btn btn-outline-danger flex-grow-1 fw-bold" style="padding: 12px; border-radius: 50rem;"
                                data-bs-toggle="modal" data-bs-target="#modal-declinar">
                                <i class="bx bx-x-circle me-1 fs-5 align-middle"></i> Declinar propuesta
                            </button>
                        </div>
                    </form>
                </div>

                {{-- modal declinar --}}
                <div class="modal fade" id="modal-declinar" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow-lg">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-danger">Declinar propuesta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('cotizaciones.declinar', $cotizacion) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p class="text-muted small">¿Estás seguro de que deseas declinar esta propuesta? No podrás revertir esta acción.</p>
                                    <label class="form-label">Motivo (opcional)</label>
                                    <textarea name="comentario_cliente" rows="3" class="form-control"
                                        placeholder="Cuéntanos por qué has decidido no tomar el servicio..."></textarea>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger shadow-sm">Sí, declinar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                {{-- cancelada --}}
                @if($cotizacion->estado === 'Cancelada' && $cotizacion->respuesta)
                <div class="alert alert-danger rounded-4 border-0 shadow-sm d-flex gap-2">
                    <i class="bx bx-error-circle fs-4 text-danger"></i>
                    <div>
                        <strong class="d-block">Motivo de cancelación</strong>
                        {{ $cotizacion->respuesta }}
                    </div>
                </div>
                @endif

                @endif
            </div>
        </div>

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
