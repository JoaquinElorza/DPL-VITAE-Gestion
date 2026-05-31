@section('title', 'Cotización ' . $cotizacion->numero_guia)

@section('vendor-style')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --dpl-navy: #1A2B4C;
        --dpl-purple: #6d28d9;
        --dpl-purple-light: #f3f0ff;
        --dpl-green: #10B981;
        --dpl-red: #e11d48;
        --bg-color: #f1f5f9;
    }

    body, .layout-wrapper, .layout-page, .content-wrapper {
        font-family: 'Poppins', sans-serif !important;
        background-color: var(--bg-color) !important;
    }

    .ultra-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(26, 43, 76, 0.06);
        border: 1px solid rgba(109, 40, 217, 0.05);
        margin-bottom: 2rem;
        position: relative;
    }

    .ultra-header-gradient {
        background: linear-gradient(135deg, var(--dpl-navy) 0%, var(--dpl-purple) 100%);
        color: white;
        padding: 2rem 1.5rem;
        border-radius: 24px 24px 0 0;
        position: relative;
        overflow: hidden;
    }
    .ultra-header-gradient::after {
        content: ''; position: absolute; top: -50%; right: -20%;
        width: 200px; height: 200px; background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .ultra-header-step {
        padding: 1.5rem 1.5rem 0.5rem;
        position: relative;
    }
    .step-badge {
        position: absolute;
        top: -15px;
        left: -15px;
        width: 45px;
        height: 45px;
        background: var(--dpl-purple);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(109, 40, 217, 0.3);
        transform: rotate(-5deg);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .info-box {
        background: var(--bg-color);
        padding: 1rem;
        border-radius: 16px;
        border-left: 4px solid var(--dpl-purple);
        transition: all 0.3s;
    }
    .info-box:hover {
        background: var(--dpl-purple-light);
        transform: translateY(-2px);
    }
    .info-label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
        margin-bottom: 0.2rem;
        display: block;
    }
    .info-value {
        font-size: 0.95rem;
        color: var(--dpl-navy);
        font-weight: 600;
        margin: 0;
        word-break: break-word;
    }

    .table-flotante {
        border-collapse: separate;
        border-spacing: 0 10px;
        width: 100%;
        margin-top: -10px;
    }
    .table-flotante th {
        border: none;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        padding: 0 1rem;
    }
    .table-flotante td {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-style: solid none;
        padding: 1rem;
        color: var(--dpl-navy);
        transition: all 0.2s;
    }
    .table-flotante td:first-child {
        border-left: 1px solid #e2e8f0;
        border-radius: 16px 0 0 16px;
    }
    .table-flotante td:last-child {
        border-right: 1px solid #e2e8f0;
        border-radius: 0 16px 16px 0;
    }
    .table-flotante tr.table-success td {
        background: var(--dpl-purple-light) !important;
        border-color: #ddd6fe;
        color: var(--dpl-purple);
        font-weight: 600;
    }

    .input-ultra {
        background-color: var(--bg-color);
        border: 2px solid transparent;
        border-radius: 14px;
        padding: 0.8rem 1.2rem;
        font-weight: 500;
        color: var(--dpl-navy);
        transition: all 0.3s;
    }
    .input-ultra:focus {
        background-color: #ffffff;
        border-color: var(--dpl-purple);
        box-shadow: 0 10px 25px rgba(109, 40, 217, 0.1);
    }
    .input-group-text.ultra {
        background: transparent;
        border: none;
        font-weight: 700;
        color: #64748b;
    }

    .btn-ultra {
        background: var(--dpl-purple);
        color: white;
        border-radius: 16px;
        padding: 1rem 2rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        border: none;
        box-shadow: 0 8px 20px rgba(109, 40, 217, 0.2);
        transition: all 0.3s ease;
    }
    .btn-ultra:hover {
        background: #5b21b6;
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(109, 40, 217, 0.3);
        color: white;
    }
    .btn-ultra-success {
        background: linear-gradient(135deg, var(--dpl-green), #059669);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }
    .btn-ultra-success:hover { background: #059669; box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4); }

    .ticket-box {
        background: repeating-linear-gradient(45deg, #ffffff, #ffffff 10px, #f8fafc 10px, #f8fafc 20px);
        border: 2px dashed #cbd5e1;
        border-radius: 20px;
        padding: 1.5rem;
    }

    @media print {
        .no-print, #layout-navbar, #layout-menu { display: none !important; }
        body, .container-xxl { background: white !important; margin: 0; padding: 0; }
        .col-lg-5 { width: 100% !important; }
        .col-lg-7 { display: none !important; }
        .ultra-card { box-shadow: none !important; border: 1px solid #000; border-radius: 0; }
        .ultra-header-gradient { background: #eee !important; color: #000 !important; }
    }
</style>
@endsection

<x-layouts.app :title="'Cotización ' . $cotizacion->numero_guia">

@php
    $tieneOrigen  = $cotizacion->lat_origen  && $cotizacion->lng_origen;
    $tieneDestino = $cotizacion->lat_destino && $cotizacion->lng_destino;
    $tieneMapa    = $tieneOrigen || $tieneDestino;

    if ($tieneOrigen && $tieneDestino) {
        $mapsLink  = 'https://www.google.com/maps/dir/'.$cotizacion->lat_origen.','.$cotizacion->lng_origen . '/'.$cotizacion->lat_destino.','.$cotizacion->lng_destino;
    } elseif ($tieneOrigen) {
        $mapsLink  = 'https://www.google.com/maps?q='.$cotizacion->lat_origen.','.$cotizacion->lng_origen;
    } elseif ($tieneDestino) {
        $mapsLink  = 'https://www.google.com/maps?q='.$cotizacion->lat_destino.','.$cotizacion->lng_destino;
    }

    $colorEstado = match($cotizacion->estado) {
        'Pendiente'   => '#f59e0b',
        'En revisión' => '#0ea5e9',
        'Aceptada'    => 'var(--dpl-green)',
        'Cancelada'   => 'var(--dpl-red)',
        default       => '#64748b',
    };
@endphp

@if(session('success'))
<div class="alert alert-success border-0 mb-4 no-print d-flex align-items-center" style="background: var(--dpl-green); color: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(16,185,129,0.3);">
    <i class='bx bx-check-shield fs-3 me-3'></i>
    <div><strong>¡Éxito!</strong> {{ session('success') }}</div>
    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

@if($cotizacion->estado === 'En revisión')
<form action="{{ route('cotizaciones.aceptar', $cotizacion) }}" method="POST" id="form-paquete">
@csrf
<div class="row g-4">
    <!-- COLUMNA IZQUIERDA (Info + Operador + Insumos) -->
    <div class="col-lg-5">
        
        <!-- Detalles de Solicitud -->
        <div class="ultra-card">
            <div class="ultra-header-gradient">
                <span class="badge mb-2" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(4px); font-size: 0.8rem;">
                    <i class="bx bx-radio-circle-marked me-1"></i> {{ $cotizacion->estado }}
                </span>
                <h3 class="fw-bold mb-1 text-white" style="font-size: 2rem;">{{ $cotizacion->numero_guia }}</h3>
                <p class="mb-0 text-white-50"><i class="bx bx-time-five me-1"></i>{{ $cotizacion->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div class="p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Detalles de la Solicitud</h6>
                <div class="info-grid">
                    <div class="info-box">
                        <span class="info-label"><i class="bx bx-user me-1"></i>Solicitante</span>
                        <p class="info-value">{{ $cotizacion->nombre }}</p>
                    </div>
                    <div class="info-box">
                        <span class="info-label"><i class="bx bx-phone me-1"></i>Teléfono</span>
                        <p class="info-value">{{ $cotizacion->telefono }}</p>
                    </div>
                    <div class="info-box">
                        <span class="info-label"><i class="bx bx-calendar-event me-1"></i>Requerido</span>
                        <p class="info-value">{{ $cotizacion->fecha_requerida ? \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') : '—' }}</p>
                    </div>
                    <div class="info-box" style="border-left-color: var(--dpl-navy);">
                        <span class="info-label"><i class="bx bx-category me-1"></i>Tipo</span>
                        <p class="info-value">{{ $cotizacion->tipo_servicio }}</p>
                    </div>
                </div>

                @if(isset($precio_ia) && $precio_ia > 0)
                <div class="info-box mt-3" style="background: linear-gradient(135deg, var(--dpl-purple-light), #ffffff); border-left-color: var(--dpl-purple);">
                    <span class="info-label text-purple"><i class="bx bx-brain me-1"></i>Sugerencia de IA</span>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <h4 class="mb-0 fw-bold" style="color: var(--dpl-purple);">${{ number_format($precio_ia, 2) }}</h4>
                        <span class="badge bg-purple">{{ $clusterCalculado }} Cluster</span>
                    </div>
                </div>
                @endif

                <hr class="my-4" style="border-color: #e2e8f0;">

                <div class="info-grid">
                    @if($cotizacion->origen)
                    <div class="info-box" style="grid-column: span 2; border-left-color: #3b82f6;">
                        <span class="info-label"><i class="bx bx-map me-1"></i>Origen</span>
                        <p class="info-value">{{ $cotizacion->origen }}</p>
                    </div>
                    @endif
                    @if($cotizacion->destino)
                    <div class="info-box" style="grid-column: span 2; border-left-color: #10b981;">
                        <span class="info-label"><i class="bx bx-map-pin me-1"></i>Destino</span>
                        <p class="info-value">{{ $cotizacion->destino }}</p>
                    </div>
                    @endif
                </div>

                @if($cotizacion->padecimientos_paciente)
                <div class="info-box mt-3" style="border-left-color: var(--dpl-red); background: #fff1f2;">
                    <span class="info-label" style="color: var(--dpl-red);"><i class="bx bx-plus-medical me-1"></i>Padecimientos</span>
                    <p class="info-value" style="color: #9f1239;">{{ $cotizacion->padecimientos_paciente }}</p>
                </div>
                @endif
            </div>

            @if($tieneMapa)
            <div class="p-3 bg-light text-center" style="border-radius: 0 0 24px 24px;">
                <a href="{{ $mapsLink }}" target="_blank" class="btn btn-ultra w-100 py-2" style="background: var(--dpl-navy);">
                    <i class="bx bx-directions me-2"></i>Abrir Ruta en Maps
                </a>
            </div>
            @endif
        </div>

        <!-- PASO 3 (Movido a la Izquierda) -->
        <div class="ultra-card mt-0">
            <div class="ultra-header-step">
                <div class="step-badge">3</div>
                <h4 class="fw-bold ms-5 mb-0" style="color: var(--dpl-navy);">Personal Operativo</h4>
            </div>
            <div class="p-4 pt-2">
                <input type="text" id="search-op" class="form-control input-ultra mb-3" placeholder="Filtrar operador..." oninput="pagers.op && pagers.op.filter()">
                <div class="table-responsive">
                    <table class="table table-flotante mb-0">
                        <thead><tr><th width="40">Sel.</th><th>Operador</th><th class="text-end">Tarifa</th></tr></thead>
                        <tbody id="tbody-op">
                            @foreach($operadores as $op)
                            @php $selected = old('id_operador', $operadorSugerido) == $op->id_usuario; @endphp
                            <tr class="op-row {{ $selected ? 'table-success' : '' }}" style="cursor:pointer" onclick="seleccionarOp(this, {{ $op->id_usuario }})">
                                <td class="text-center"><input type="radio" name="id_operador" value="{{ $op->id_usuario }}" class="form-check-input" style="transform: scale(1.3);" {{ $selected ? 'checked' : '' }}></td>
                                <td class="fw-bold" style="font-size: 0.9rem;">{{ $op->usuario->nombre ?? '—' }} {{ $op->usuario->ap_paterno ?? '' }}</td>
                                <td class="text-end text-muted" style="font-size: 0.9rem;">${{ number_format($op->salario_hora, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="pager-op"></div>
            </div>
        </div>

        <!-- PASO 5 (Movido a la Izquierda) -->
        <div class="ultra-card mt-0">
            <div class="ultra-header-step">
                <div class="step-badge">5</div>
                <h4 class="fw-bold ms-5 mb-0" style="color: var(--dpl-navy);">Insumos Extras</h4>
            </div>
            <div class="p-4 pt-2">
                <input type="text" id="search-ins" class="form-control input-ultra mb-3" placeholder="Buscar material..." oninput="pagers.ins && pagers.ins.filter()">
                <div class="table-responsive">
                    <table class="table table-flotante mb-0">
                        <thead><tr><th width="40">Inc.</th><th>Material</th><th width="80">Cant.</th><th class="text-end">Total</th></tr></thead>
                        <tbody id="tbody-ins">
                            @foreach($insumos as $ins)
                            <tr class="ins-row" data-costo="{{ $ins->costo_unidad }}" data-id="{{ $ins->id_insumo }}">
                                <td class="text-center"><input type="checkbox" name="insumos[{{ $ins->id_insumo }}][seleccionado]" value="1" class="form-check-input ins-check" style="transform: scale(1.3);" onchange="recalcularInsumos()"></td>
                                <td class="fw-bold" style="font-size: 0.9rem;">{{ $ins->nombre_insumo }}<br><small class="text-muted fw-normal">${{ number_format($ins->costo_unidad, 2) }} c/u</small></td>
                                <td><input type="number" name="insumos[{{ $ins->id_insumo }}][cantidad]" class="form-control input-ultra text-center p-1 ins-cant" value="1" min="1" onchange="recalcularInsumos()"></td>
                                <td class="text-end fw-bold text-success ins-subtotal" style="font-size: 0.9rem;">$0.00</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="pager-ins"></div>
                <div class="text-end mt-3"><span class="info-label d-inline">Total Insumos: </span><h4 class="d-inline text-success fw-bold" id="sub_insumos">$0.00</h4></div>
            </div>
        </div>

    </div>

    <!-- COLUMNA DERECHA (Logística + Vehículo + Paramédicos + Ticket) -->
    <div class="col-lg-7">
        
        <!-- PASO 1 -->
        <div class="ultra-card mt-0">
            <div class="ultra-header-step">
                <div class="step-badge">1</div>
                <h4 class="fw-bold ms-5 mb-0" style="color: var(--dpl-navy);">Logística y Distancia</h4>
            </div>
            <div class="p-4 pt-2">
                <div class="row g-3 bg-light p-3 rounded-4">
                    <div class="col-md-3">
                        <label class="info-label">Distancia</label>
                        <div class="input-group" style="background: white; border-radius: 12px;">
                            <input type="number" id="inp_km" name="km_distancia" step="0.01" min="0"
                                class="form-control input-ultra border-0" required
                                value="{{ old('km_distancia', $cotizacion->km_distancia ?? $kmCalculado) }}">
                            <span class="input-group-text ultra">km</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="info-label">Tarifa</label>
                        <div class="input-group" style="background: white; border-radius: 12px;">
                            <span class="input-group-text ultra">$</span>
                            <input type="number" id="inp_tarifa_km" name="costo_km_unitario" step="0.01" min="0"
                                class="form-control input-ultra border-0 px-0" required
                                value="{{ old('costo_km_unitario', $empresa->costo_km ?? 25) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="info-label">Horas</label>
                        <div class="input-group" style="background: white; border-radius: 12px;">
                            <input type="number" id="inp_horas" name="horas_servicio" step="0.5" min="0.5"
                                class="form-control input-ultra border-0" value="{{ old('horas_servicio', 1) }}">
                            <span class="input-group-text ultra">hrs</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="info-label text-success">Subtotal</label>
                        <div class="input-group" style="background: var(--dpl-green); border-radius: 12px;">
                            <span class="input-group-text ultra text-white">$</span>
                            <input type="text" id="sub_km" class="form-control input-ultra border-0 bg-transparent text-white fw-bold px-0" readonly value="0.00">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PASO 2 -->
        <div class="ultra-card mt-0">
            <div class="ultra-header-step">
                <div class="step-badge">2</div>
                <div class="d-flex justify-content-between align-items-center ms-5">
                    <h4 class="fw-bold mb-0" style="color: var(--dpl-navy);">Vehículo Especializado</h4>
                    @if($cotizacion->tipo_ambulancia_preferida)
                    <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 10px;"><i class="bx bx-star"></i> Prefiere: {{ $cotizacion->tipo_ambulancia_preferida }}</span>
                    @endif
                </div>
            </div>
            <div class="p-4 pt-2">
                <input type="text" id="search-amb" class="form-control input-ultra mb-3" placeholder="Buscar por placa o tipo..." oninput="pagers.amb && pagers.amb.filter()">
                <div class="table-responsive">
                    <table class="table table-flotante mb-0">
                        <thead><tr><th width="50">Sel.</th><th>Unidad</th><th>Categoría</th><th class="text-end">Base</th></tr></thead>
                        <tbody id="tbody-amb">
                            @foreach($ambulancias as $amb)
                            <tr class="amb-row" data-costo-tipo="{{ $amb->tipo->costo_base ?? 0 }}" data-salario-op="0" style="cursor:pointer" onclick="seleccionarAmb(this, {{ $amb->id_ambulancia }})">
                                <td class="text-center"><input type="radio" name="id_ambulancia" value="{{ $amb->id_ambulancia }}" class="form-check-input" style="transform: scale(1.5);" {{ old('id_ambulancia') == $amb->id_ambulancia ? 'checked' : '' }}></td>
                                <td class="fw-bold">{{ $amb->placa }}</td>
                                <td>{{ $amb->tipo->nombre_tipo ?? '—' }}</td>
                                <td class="text-end fw-bold text-success">${{ number_format($amb->tipo->costo_base ?? 0, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="pager-amb"></div>
                <div class="text-end mt-2"><span class="info-label d-inline">Total Vehículo: </span><h4 class="d-inline text-success fw-bold" id="sub_ambulancia">$0.00</h4></div>
            </div>
        </div>

        <!-- PASO 4 -->
        <div class="ultra-card mt-0">
            <div class="ultra-header-step">
                <div class="step-badge">4</div>
                <h4 class="fw-bold ms-5 mb-0" style="color: var(--dpl-navy);">Paramédicos <span class="badge bg-warning text-dark ms-2" style="font-size:0.8rem">Mín. 2</span></h4>
            </div>
            <div class="p-4 pt-2">
                <input type="text" id="search-pm" class="form-control input-ultra mb-3" placeholder="Filtrar paramédico..." oninput="pagers.pm && pagers.pm.filter()">
                <div class="table-responsive">
                    <table class="table table-flotante mb-0">
                        <thead><tr><th width="50">Sel.</th><th>Elemento</th><th class="text-end">Tarifa</th><th class="text-end">Acumulado</th></tr></thead>
                        <tbody id="tabla-paramedicos">
                            @foreach($paramedicos as $pm)
                            @php $checked = is_array(old('paramedicos_ids')) && in_array($pm->id_usuario, old('paramedicos_ids')); @endphp
                            <tr class="pm-row {{ $checked ? 'table-success' : '' }}" data-salario="{{ $pm->salario_hora }}" style="cursor:pointer" onclick="toggleParamedico(this)">
                                <td class="text-center"><input type="checkbox" name="paramedicos_ids[]" value="{{ $pm->id_usuario }}" class="form-check-input pm-check" style="transform: scale(1.5);" {{ $checked ? 'checked' : '' }}></td>
                                <td class="fw-bold">{{ $pm->usuario->nombre ?? '—' }} {{ $pm->usuario->ap_paterno ?? '' }}</td>
                                <td class="text-end text-muted">${{ number_format($pm->salario_hora, 2) }}</td>
                                <td class="text-end fw-bold text-success pm-subtotal">$0.00</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="pager-pm"></div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span id="aviso-min-pm" class="badge bg-danger p-2 d-none"><i class="bx bx-error"></i> Selecciona mínimo 2</span>
                    <div class="ms-auto text-end"><span class="info-label d-inline">Total Paramédicos: </span><h4 class="d-inline text-success fw-bold" id="sub_paramedicos">$0.00</h4></div>
                </div>
            </div>
        </div>

        <!-- TICKET FINAL (PASO 6) -->
        <div class="ultra-card mt-0" style="border: 2px solid var(--dpl-green); overflow: visible;">
            <div class="step-badge" style="background: var(--dpl-green); box-shadow: 0 8px 20px rgba(16,185,129,0.4);"><i class='bx bx-check-double'></i></div>
            
            <div class="p-5">
                <div class="ticket-box mb-4">
                    <h5 class="fw-bold text-center mb-4" style="color: var(--dpl-navy);"><i class="bx bx-receipt text-success me-2"></i>Recibo Estimado</h5>
                    
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom"><span class="text-muted fw-semibold">Traslado (Km)</span><span id="res_km" class="fw-bold text-dark">$0.00</span></div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom"><span class="text-muted fw-semibold">Uso Vehicular</span><span id="res_amb" class="fw-bold text-dark">$0.00</span></div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom"><span class="text-muted fw-semibold">Cuota Paramédicos</span><span id="res_pm" class="fw-bold text-dark">$0.00</span></div>
                    <div class="d-flex justify-content-between mb-4 pb-2 border-bottom"><span class="text-muted fw-semibold">Insumos y Material</span><span id="res_ins" class="fw-bold text-dark">$0.00</span></div>
                    
                    <div class="d-flex justify-content-between align-items-end mt-2">
                        <span class="fw-bold" style="color: var(--dpl-green); font-size: 1.2rem;">TOTAL COTIZADO</span>
                        <span id="res_total" class="fw-bold text-success" style="font-size: 2.2rem; line-height: 1;">$0.00 <span style="font-size:1rem; color:#64748b;">MXN</span></span>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="info-label text-dark"><i class='bx bx-credit-card me-1'></i>Anticipo Sugerido (Opcional)</label>
                        <div class="input-group">
                            <span class="input-group-text ultra bg-light">$</span>
                            <input type="number" name="anticipo" step="0.01" min="0" class="form-control input-ultra fw-bold fs-5 text-primary" placeholder="0.00" value="{{ old('anticipo', $cotizacion->anticipo ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="info-label text-dark">Mensaje al Cliente (Instrucciones)</label>
                        <input type="text" name="respuesta" class="form-control input-ultra" placeholder="Ej. Favor de tener listo el historial..." value="{{ old('respuesta', $cotizacion->respuesta) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="info-label text-dark m-0">Glosa Médica (¿Qué incluye?)</label>
                        <button type="button" class="btn btn-sm" style="background: var(--bg-color); color: var(--dpl-purple); border-radius: 10px;" onclick="generarIncluye()"><i class="bx bx-refresh"></i> Autogenerar texto</button>
                    </div>
                    <textarea name="incluye" id="inp_incluye" rows="4" class="form-control input-ultra" placeholder="Detalle automático..." required>{{ old('incluye', $cotizacion->incluye) }}</textarea>
                </div>

                <button type="submit" class="btn btn-ultra btn-ultra-success w-100 py-3 fs-5" id="btn-aceptar">
                    <i class="bx bx-paper-plane me-2"></i> Emitir Propuesta al Cliente
                </button>
            </div>
        </div>
        
    </div>
</div>
</form>

<!-- BLOQUE PARA RECHAZAR SOLICITUD (Abajo del todo) -->
<div class="row">
    <div class="col-12">
        <div class="ultra-card" style="border: 1px solid var(--dpl-red);">
            <div class="p-4" style="background: #fff1f2; border-radius: 24px;">
                <h5 class="fw-bold" style="color: var(--dpl-red);"><i class="bx bx-x-circle me-2"></i>Cancelar Solicitud</h5>
                <form action="{{ route('cotizaciones.rechazar', $cotizacion) }}" method="POST" class="mt-3">
                    @csrf
                    <textarea name="respuesta" rows="2" required class="form-control input-ultra mb-3" placeholder="Explique al cliente por qué no es posible el servicio..."></textarea>
                    <button type="submit" class="btn btn-danger px-4" style="border-radius: 14px; font-weight:600;" onclick="return confirm('¿Declinación definitiva?')">Rechazar Formalmente</button>
                </form>
            </div>
        </div>
    </div>
</div>

@else
<!-- VISTA CUANDO YA FUE ACEPTADA O CANCELADA -->
<div class="row g-4">
    <div class="col-lg-5">
        <!-- Detalles de Solicitud (Version solo vista) -->
        <div class="ultra-card">
            <div class="ultra-header-gradient">
                <span class="badge mb-2" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(4px); font-size: 0.8rem;">
                    <i class="bx bx-radio-circle-marked me-1"></i> {{ $cotizacion->estado }}
                </span>
                <h3 class="fw-bold mb-1 text-white" style="font-size: 2rem;">{{ $cotizacion->numero_guia }}</h3>
                <p class="mb-0 text-white-50"><i class="bx bx-time-five me-1"></i>{{ $cotizacion->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div class="p-4">
                <div class="info-grid">
                    <div class="info-box"><span class="info-label"><i class="bx bx-user me-1"></i>Solicitante</span><p class="info-value">{{ $cotizacion->nombre }}</p></div>
                    <div class="info-box"><span class="info-label"><i class="bx bx-phone me-1"></i>Teléfono</span><p class="info-value">{{ $cotizacion->telefono }}</p></div>
                    <div class="info-box"><span class="info-label"><i class="bx bx-calendar-event me-1"></i>Requerido</span><p class="info-value">{{ $cotizacion->fecha_requerida ? \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') : '—' }}</p></div>
                    <div class="info-box" style="border-left-color: var(--dpl-navy);"><span class="info-label"><i class="bx bx-category me-1"></i>Tipo</span><p class="info-value">{{ $cotizacion->tipo_servicio }}</p></div>
                </div>

                <hr class="my-4" style="border-color: #e2e8f0;">

                <div class="info-grid">
                    @if($cotizacion->origen)
                    <div class="info-box" style="grid-column: span 2; border-left-color: #3b82f6;"><span class="info-label"><i class="bx bx-map me-1"></i>Origen</span><p class="info-value">{{ $cotizacion->origen }}</p></div>
                    @endif
                    @if($cotizacion->destino)
                    <div class="info-box" style="grid-column: span 2; border-left-color: #10b981;"><span class="info-label"><i class="bx bx-map-pin me-1"></i>Destino</span><p class="info-value">{{ $cotizacion->destino }}</p></div>
                    @endif
                </div>
            </div>
            
            @if($tieneMapa)
            <div class="p-3 bg-light text-center" style="border-radius: 0 0 24px 24px;">
                <a href="{{ $mapsLink }}" target="_blank" class="btn btn-ultra w-100 py-2" style="background: var(--dpl-navy);">
                    <i class="bx bx-directions me-2"></i>Abrir Ruta en Maps
                </a>
            </div>
            @endif
        </div>

        @if($cotizacion->datos_paciente)
        <div class="ultra-card mt-4">
            <div class="ultra-header-gradient" style="background: linear-gradient(135deg, var(--dpl-red), #9f1239); padding: 1.5rem;">
                <h5 class="mb-0 text-white"><i class="bx bx-lock-alt me-2"></i>Expediente Clínico del Paciente</h5>
            </div>
            <div class="p-4">
                <div class="info-grid">
                    <div class="info-box"><span class="info-label">Nombre</span><p class="info-value">{{ $cotizacion->datos_paciente['nombre'] ?? '—' }}</p></div>
                    <div class="info-box"><span class="info-label">Nacimiento</span><p class="info-value">{{ $cotizacion->datos_paciente['nacimiento'] ? \Carbon\Carbon::parse($cotizacion->datos_paciente['nacimiento'])->format('d/m/Y') : '—' }}</p></div>
                    <div class="info-box" style="grid-column: span 2;"><span class="info-label">Diagnóstico</span><p class="info-value">{{ $cotizacion->datos_paciente['diagnostico'] ?? '—' }}</p></div>
                    <div class="info-box"><span class="info-label text-danger">Alergias</span><p class="info-value text-danger">{{ $cotizacion->datos_paciente['alergias'] ?? 'Ninguna' }}</p></div>
                    <div class="info-box"><span class="info-label">Médico Tratante</span><p class="info-value">{{ $cotizacion->datos_paciente['medico'] ?? '—' }}</p></div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="ultra-card p-4 no-print" style="border: 2px dashed #cbd5e1;">
            <h6 class="fw-bold mb-3 text-muted"><i class="bx bx-refresh"></i> Modificar Estado Manual</h6>
            <form action="{{ route('cotizaciones.update', $cotizacion) }}" method="POST" class="d-flex gap-3">
                @csrf @method('PUT')
                <select name="estado" class="form-select input-ultra flex-grow-1">
                    @foreach(['Pendiente','En revisión','Aceptada','Cancelada'] as $est)
                        <option value="{{ $est }}" {{ $cotizacion->estado === $est ? 'selected' : '' }}>{{ $est }}</option>
                    @endforeach
                </select>
                <button class="btn btn-ultra" style="padding: 0.5rem 1.5rem;"><i class="bx bx-check"></i></button>
            </form>
        </div>
    </div>
    
    <div class="col-lg-7">
        @if($cotizacion->estado === 'Aceptada')
        <div class="ultra-card" style="border-top: 6px solid var(--dpl-green);">
            <div class="p-4">
                <h5 class="fw-bold mb-4" style="color: var(--dpl-green);"><i class="bx bx-check-shield"></i> Cotización Cerrada y Enviada</h5>
                
                <div class="ticket-box">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Traslado</span><span class="fw-bold">${{ number_format($cotizacion->km_distancia * $cotizacion->costo_km_unitario, 2) }}</span></div>
                    @if($cotizacion->costo_ambulancia)<div class="d-flex justify-content-between mb-2"><span class="text-muted">Ambulancia</span><span class="fw-bold">${{ number_format($cotizacion->costo_ambulancia, 2) }}</span></div>@endif
                    @if($cotizacion->costo_paramedicos)<div class="d-flex justify-content-between mb-2"><span class="text-muted">Paramédicos</span><span class="fw-bold">${{ number_format($cotizacion->costo_paramedicos, 2) }}</span></div>@endif
                    @if($cotizacion->costo_insumos)<div class="d-flex justify-content-between mb-2"><span class="text-muted">Insumos extras</span><span class="fw-bold">${{ number_format($cotizacion->costo_insumos, 2) }}</span></div>@endif
                    <hr>
                    <div class="d-flex justify-content-between fs-4"><span class="fw-bold text-success">TOTAL</span><span class="fw-bold text-success">${{ number_format($cotizacion->costo, 2) }}</span></div>
                </div>

                @if($cotizacion->incluye)
                <div class="mt-4 p-3 bg-light rounded-4">
                    <span class="info-label text-dark">Incluye:</span>
                    <p class="small text-muted mb-0" style="white-space: pre-line;">{{ $cotizacion->incluye }}</p>
                </div>
                @endif

                @if($cotizacion->respuesta)
                <div class="alert mt-3 mb-0 small" style="background-color: #d1fae5; color: #065f46; border: none; border-radius: 12px;">
                    <i class="bx bx-message-rounded-detail me-1"></i> {{ $cotizacion->respuesta }}
                </div>
                @endif
            </div>
        </div>
        @endif

        @if($cotizacion->estado === 'Cancelada' && $cotizacion->respuesta)
        <div class="ultra-card" style="border-top: 4px solid var(--dpl-red);">
            <div class="p-4">
                <h5 class="fw-bold" style="color: var(--dpl-red);"><i class="bx bx-x-circle"></i> Cotización Cancelada</h5>
                <div class="alert mt-3 mb-0 small" style="background-color: #ffe4e6; color: #9f1239; border: none; border-radius: 12px;">
                    <strong>Motivo del rechazo:</strong> {{ $cotizacion->respuesta }}
                </div>
            </div>
        </div>
        @endif

        @if($cotizacion->decision_cliente)
        <div class="ultra-card mt-4">
            <div class="p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.9rem; letter-spacing: 0.5px;">Respuesta del cliente</h6>
                @if($cotizacion->decision_cliente === 'confirmada')
                    <div class="alert py-3 mb-0 small" style="background-color: #d1fae5; color: #065f46; border: none; border-radius: 12px;">
                        <i class="bx bx-check-circle me-1 fs-5 align-middle"></i>
                        <strong class="align-middle">El cliente confirmó el servicio.</strong>
                        @if($cotizacion->comentario_cliente)<hr style="border-color: #a7f3d0;">{{ $cotizacion->comentario_cliente }}@endif
                    </div>
                @else
                    <div class="alert py-3 mb-0 small" style="background-color: #ffe4e6; color: #9f1239; border: none; border-radius: 12px;">
                        <i class="bx bx-x-circle me-1 fs-5 align-middle"></i>
                        <strong class="align-middle">El cliente declinó la propuesta.</strong>
                        @if($cotizacion->comentario_cliente)<hr style="border-color: #fecdd3;">{{ $cotizacion->comentario_cliente }}@endif
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<div class="mt-4 p-4 d-flex justify-content-between align-items-center" style="background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    <a href="{{ route('cotizaciones.index') }}" class="btn btn-ultra" style="background: var(--bg-color); color: var(--dpl-navy); box-shadow: none;"><i class="bx bx-arrow-back me-2"></i>Regresar al panel</a>
    <div class="d-flex gap-3">
        <button class="btn btn-ultra" onclick="window.print()"><i class="bx bx-printer me-2"></i>Imprimir</button>
        <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" onsubmit="return confirm('¿Eliminar registro por completo?')">
            @csrf @method('DELETE')
            <button class="btn btn-ultra" style="background: var(--dpl-red);"><i class="bx bx-trash"></i></button>
        </form>
    </div>
</div>

@php
$datosParamedicos = $paramedicos->map(fn($p) => ['id' => $p->id_usuario, 'nombre' => trim(($p->usuario->nombre ?? '') . ' ' . ($p->usuario->ap_paterno ?? '')), 'salario' => (float)$p->salario_hora])->values();
$datosInsumos = $insumos->map(fn($i) => ['id' => $i->id_insumo, 'nombre' => $i->nombre_insumo, 'costo' => (float)$i->costo_unidad])->values();
@endphp

<script>
var pagers = {};
function TablePager(cfg) {
    var tbody   = document.getElementById(cfg.tbody);
    var searchEl = cfg.search ? document.getElementById(cfg.search) : null;
    var pagerEl  = cfg.pager  ? document.getElementById(cfg.pager)  : null;
    var pageSize = cfg.pageSize || 8;
    var page = 1;

    function allRows() { return tbody ? Array.from(tbody.querySelectorAll('tr')) : []; }
    function filteredRows() { var q = searchEl ? searchEl.value.trim().toLowerCase() : ''; return allRows().filter(function(r) { return !q || r.textContent.toLowerCase().includes(q); }); }

    function render() {
        var rows = filteredRows();
        var pages = Math.ceil(rows.length / pageSize) || 1;
        page = Math.max(1, Math.min(page, pages));
        var start = (page - 1) * pageSize;

        allRows().forEach(function(r) { r.style.display = 'none'; });
        rows.slice(start, start + pageSize).forEach(function(r) { r.style.display = ''; });

        if (pagerEl) {
            if (rows.length <= pageSize) { pagerEl.innerHTML = ''; } else {
                pagerEl.innerHTML = '<div class="d-flex justify-content-between mt-3 align-items-center"><small class="text-muted fw-bold">' + rows.length + ' regs</small><div class="btn-group btn-group-sm"><button type="button" class="btn btn-light" ' + (page<=1?'disabled':'') + ' onclick="pagers[\'' + cfg.id + '\'].prev()">‹</button><button type="button" class="btn btn-light" ' + (page>=pages?'disabled':'') + ' onclick="pagers[\'' + cfg.id + '\'].next()">›</button></div></div>';
            }
        }
    }
    if (searchEl) searchEl.addEventListener('input', function() { page = 1; render(); });
    render();
    return { filter: function() { page = 1; render(); }, prev: function() { page--; render(); }, next: function() { page++; render(); } };
}

var datosParamedicos = @json($datosParamedicos);
var datosInsumos     = @json($datosInsumos);

function fmt(n) { return '$' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
function getKm()     { return parseFloat(document.getElementById('inp_km')?.value)     || 0; }
function getTarifa() { return parseFloat(document.getElementById('inp_tarifa_km')?.value) || 0; }
function getHoras()  { return parseFloat(document.getElementById('inp_horas')?.value)   || 1; }

function seleccionarAmb(row, id) {
    document.querySelectorAll('.amb-row').forEach(r => r.classList.remove('table-success'));
    row.classList.add('table-success');
    row.querySelector('input[type=radio]').checked = true;
    actualizarSubtotalAmb(); recalcularTotal();
}
function getAmbCosto() {
    var sel = document.querySelector('.amb-row.table-success');
    if (!sel) return 0;
    return (parseFloat(sel.dataset.costoTipo) || 0) + ((parseFloat(sel.dataset.salarioOp) || 0) * getHoras());
}
function actualizarSubtotalAmb() { document.getElementById('sub_ambulancia').textContent = fmt(getAmbCosto()); }

function seleccionarOp(row, id) {
    document.querySelectorAll('.op-row').forEach(r => r.classList.remove('table-success'));
    row.classList.add('table-success');
    row.querySelector('input[type=radio]').checked = true;
}

function toggleParamedico(row) {
    var cb = row.querySelector('.pm-check');
    cb.checked = !cb.checked;
    row.classList.toggle('table-success', cb.checked);
    recalcularParamedicos();
}
function recalcularParamedicos() {
    var horas = getHoras(), total = 0, selCount = 0, avisoMin = document.getElementById('aviso-min-pm');
    document.querySelectorAll('.pm-row').forEach(function(row) {
        var cb = row.querySelector('.pm-check'), sub = cb.checked ? (parseFloat(row.dataset.salario) || 0) * horas : 0;
        if (cb.checked) selCount++;
        row.querySelector('.pm-subtotal').textContent = fmt(sub);
        total += sub;
    });
    if (avisoMin) avisoMin.classList.toggle('d-none', selCount >= 2);
    document.getElementById('sub_paramedicos').textContent = fmt(total);
    recalcularTotal();
}

function recalcularInsumos() {
    var total = 0;
    document.querySelectorAll('.ins-row').forEach(function(row) {
        var cb = row.querySelector('.ins-check'), sub = cb.checked ? (parseFloat(row.dataset.costo) || 0) * (parseInt(row.querySelector('.ins-cant').value) || 1) : 0;
        row.querySelector('.ins-subtotal').textContent = fmt(sub);
        total += sub;
    });
    document.getElementById('sub_insumos').textContent = fmt(total);
    recalcularTotal();
}

function recalcularKm() {
    var sub = getKm() * getTarifa();
    document.getElementById('sub_km').value = sub.toFixed(2);
    recalcularTotal();
}

function recalcularTotal() {
    var km = getKm() * getTarifa(), amb = getAmbCosto(), pm = parseFloat(document.getElementById('sub_paramedicos').textContent.replace(/[$,]/g,'')) || 0, ins = parseFloat(document.getElementById('sub_insumos').textContent.replace(/[$,]/g,'')) || 0;
    var tot = km + amb + pm + ins;
    document.getElementById('res_km').textContent = fmt(km);
    document.getElementById('res_amb').textContent = fmt(amb);
    document.getElementById('res_pm').textContent = fmt(pm);
    document.getElementById('res_ins').textContent = fmt(ins);
    document.getElementById('res_total').textContent = fmt(tot);
}

function generarIncluye() {
    var lines = [];
    var km = getKm(), tar = getTarifa();
    if (km > 0) lines.push('• Traslado de ' + km + ' km (tarifa $' + tar.toFixed(2) + '/km)');
    var ambRow = document.querySelector('.amb-row.table-success');
    if (ambRow) lines.push('• Ambulancia ' + (ambRow.querySelectorAll('td')[2]?.textContent.trim() || ''));
    var opRow = document.querySelector('.op-row.table-success');
    if (opRow) lines.push('• Operador: ' + (opRow.querySelectorAll('td')[1]?.textContent.trim() || ''));
    var pmNames = [];
    document.querySelectorAll('.pm-row').forEach(function(row) { if (row.querySelector('.pm-check').checked) pmNames.push(row.querySelectorAll('td')[1].textContent.trim()); });
    if (pmNames.length) lines.push('• ' + pmNames.length + ' paramédico(s): ' + pmNames.join(', ') + ' (' + getHoras() + ' hrs)');
    document.querySelectorAll('.ins-row').forEach(function(row) { if (row.querySelector('.ins-check').checked) lines.push('• ' + row.querySelectorAll('td')[1].textContent.trim() + ' (x' + row.querySelector('.ins-cant').value + ')'); });
    document.getElementById('inp_incluye').value = lines.join('\n');
}

document.addEventListener('DOMContentLoaded', function () {
    var inpKm = document.getElementById('inp_km'), inpTarifa = document.getElementById('inp_tarifa_km'), inpHoras = document.getElementById('inp_horas');
    if (inpKm) inpKm.addEventListener('input', recalcularKm);
    if (inpTarifa) inpTarifa.addEventListener('input', recalcularKm);
    if (inpHoras) inpHoras.addEventListener('input', function() { actualizarSubtotalAmb(); recalcularParamedicos(); });
    document.querySelectorAll('.pm-row').forEach(function(row) { row.querySelector('.pm-check').addEventListener('change', function() { row.classList.toggle('table-success', this.checked); recalcularParamedicos(); }); });

    recalcularKm(); recalcularParamedicos(); recalcularInsumos();

    if (document.getElementById('tbody-amb')) pagers.amb = TablePager({ id: 'amb', tbody: 'tbody-amb', search: 'search-amb', pager: 'pager-amb', pageSize: 6 });
    if (document.getElementById('tbody-op')) pagers.op = TablePager({ id: 'op', tbody: 'tbody-op', search: 'search-op', pager: 'pager-op', pageSize: 6 });
    if (document.getElementById('tabla-paramedicos')) pagers.pm = TablePager({ id: 'pm', tbody: 'tabla-paramedicos', search: 'search-pm', pager: 'pager-pm', pageSize: 6 });
    if (document.getElementById('tbody-ins')) pagers.ins = TablePager({ id: 'ins', tbody: 'tbody-ins', search: 'search-ins', pager: 'pager-ins', pageSize: 6 });
});
</script>
</x-layouts.app>