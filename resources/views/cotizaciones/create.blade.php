<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitar Servicio — {{ $empresa->nombre ?? config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy-blue: #1A2B4C;
            --premium-purple: #6d28d9;
            --purple-light: #f3f0ff;
            --danger-red: #e11d48;
            --bg-color: #f8fafc;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Poppins', sans-serif;
            color: #334155;
        }

        /* Navbar Premium (Sin icono médico) */
        .navbar-premium {
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(26, 43, 76, 0.05);
            padding: 1rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .navbar-brand {
            color: var(--navy-blue) !important;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        /* Línea de tiempo */
        .timeline-wrapper {
            position: relative;
        }
        .timeline-wrapper::before {
            content: '';
            position: absolute;
            top: 20px;
            bottom: 100px;
            left: 20px;
            width: 2px;
            background: #e2e8f0;
            z-index: 1;
        }
        .timeline-item {
            position: relative;
            padding-left: 60px;
            margin-bottom: 2.5rem;
        }
        .timeline-badge {
            position: absolute;
            left: 0;
            top: 15px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--premium-purple);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            z-index: 2;
            box-shadow: 0 0 0 6px var(--bg-color);
        }

        /* Tarjetas Flotantes */
        .premium-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(26, 43, 76, 0.04);
            padding: 2.2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .premium-card:hover {
            box-shadow: 0 12px 40px rgba(26, 43, 76, 0.07);
        }

        .section-title {
            color: var(--navy-blue);
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1rem;
        }

        /* Inputs Premium */
        .form-label {
            font-weight: 500;
            color: var(--navy-blue);
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }
        .form-control, .form-select {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1.2rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: none !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--premium-purple);
            box-shadow: 0 0 0 4px rgba(109, 40, 217, 0.1) !important;
        }
        .text-danger { color: var(--danger-red) !important; }

        /* Tarjetas de Selección (Traslado/Evento) */
        .tipo-card {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #ffffff;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .tipo-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-3px);
        }
        .tipo-card.selected {
            border-color: var(--premium-purple);
            background: var(--purple-light);
        }
        .tipo-card input { display: none; }
        .tipo-card h4 {
            color: var(--navy-blue);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .tipo-text {
            color: #64748b;
            font-size: 0.85rem;
            margin: 0;
            line-height: 1.5;
        }

        /* Mapas */
        .map-box {
            height: 280px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            z-index: 1; 
        }

        /* Botón Principal */
        .btn-premium {
            background: var(--premium-purple);
            color: #ffffff;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 50rem;
            border: none;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(109, 40, 217, 0.3);
        }
        .btn-premium:hover:not(:disabled) {
            background: #5b21b6;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(109, 40, 217, 0.4);
            color: #fff;
        }
        .btn-premium:disabled {
            background: #cbd5e1;
            box-shadow: none;
            transform: none;
        }

        /* AI Card Refinada */
        #ai-prediction-card {
            background: linear-gradient(145deg, var(--navy-blue) 0%, #2a4374 100%);
            border-radius: 20px;
            border: none;
        }
    </style>
</head>

<body>

<!-- Navbar sin cruz -->
<nav class="navbar navbar-premium sticky-top">
    <div class="container d-flex align-items-center justify-content-center">
        <span class="navbar-brand">
            {{ $empresa->nombre ?? 'Vitae Ambulancias' }}
        </span>
    </div>
</nav>

@php
    $costoKm = $empresa->costo_km ?? 25;
@endphp

<section class="py-5">
<div class="container">

    @if($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius: 12px; background: #fff1f2; border: 1px solid #fecdd3;">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-5">
        
        <!-- COLUMNA IZQUIERDA: Textos e Inteligencia Artificial (Sticky) -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px;">
                <h1 class="fw-bold mb-3" style="color: var(--navy-blue); font-size: 2.2rem; line-height: 1.2;">Solicitud de Servicio Médico</h1>
                <p class="text-muted mb-4" style="font-size: 0.95rem;">Complete el formulario paso a paso. Nuestro motor logístico analizará su requerimiento para ofrecerle una estimación justa y transparente.</p>
                
                {{-- PREDICCIÓN AI (PREMIUM) --}}
                <div id="ai-prediction-card" class="d-none p-4 shadow-lg text-white position-relative overflow-hidden">
                    <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%); pointer-events: none;"></div>
                    
                    <div class="d-flex align-items-center mb-4 border-bottom border-secondary pb-3">
                        <i class='bx bx-brain fs-1 me-3' style="color: #38bdf8;"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">Análisis Predictivo</h5>
                            <small class="text-light" style="opacity: 0.8;">Motor Minería de Datos DPL</small>
                        </div>
                    </div>
                    
                    <div id="ai-loading" class="text-center py-4">
                        <div class="spinner-border text-info mb-3" role="status"></div>
                        <p class="mb-0 font-monospace small" style="opacity: 0.9;" id="ai-loading-text">Evaluando variables...</p>
                    </div>
                    
                    <div id="ai-result" class="d-none">
                        <div class="text-center mb-4">
                            <span class="small d-block mb-1 text-uppercase tracking-wider" style="opacity: 0.7; letter-spacing: 1px;">Estimación Base</span>
                            <h2 class="mb-0 fw-bold" id="est-total" style="color: #38bdf8; font-size: 2.5rem;">$0.00</h2>
                            <h6 class="mb-0 fw-bold mt-2 text-white" id="est-tipo">--</h6>
                        </div>
                        
                        <div class="bg-dark bg-opacity-25 p-3 rounded-3 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class='bx bx-check-shield text-success fs-5 me-2'></i>
                                <span class="small fw-semibold">Validación Histórica</span>
                            </div>
                            <p class="small mb-0 text-light" style="opacity: 0.85;">
                                Filtrados <strong id="badge-outliers" class="text-white">--</strong> outliers de <strong id="badge-analizados" class="text-white">--</strong> registros para garantizar precisión del <strong id="badge-precision" class="text-success">--%</strong>.
                            </p>
                        </div>

                        <div class="d-flex justify-content-between text-center opacity-75 small">
                            <div><i class='bx bx-map-alt text-info d-block fs-5 mb-1'></i> <span id="distancia-explicada">0</span> km</div>
                            <div><i class='bx bx-time-five text-info d-block fs-5 mb-1'></i> <span id="horas-explicadas">1</span> hr(s)</div>
                            <div><i class='bx bx-shield-plus text-info d-block fs-5 mb-1'></i> Seguro DPL</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Formulario con Línea de Tiempo -->
        <div class="col-lg-8">
            <form method="POST" action="{{ route('cotizaciones.store') }}">
            @csrf
            
            <div class="timeline-wrapper">

                {{-- PASO 1: CONTACTO --}}
                <div class="timeline-item">
                    <div class="timeline-badge">1</div>
                    <div class="premium-card">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                            <h3 class="section-title border-0 pb-0 mb-0">Datos del Solicitante</h3>
                            <span class="text-danger small fw-semibold">* Obligatorio</span>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input class="form-control" name="nombre" placeholder="Ej. Juan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Primer apellido <span class="text-danger">*</span></label>
                                <input class="form-control" name="pApellido" placeholder="Ej. Pérez" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Segundo apellido</label>
                                <input class="form-control" name="sApellido" placeholder="Ej. García">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono celular <span class="text-danger">*</span></label>
                                <input class="form-control" name="telefono" placeholder="A 10 dígitos" maxlength="10" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PASO 2: SERVICIO --}}
                <div class="timeline-item">
                    <div class="timeline-badge">2</div>
                    <div class="premium-card">
                        <h3 class="section-title">Detalles del Servicio</h3>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="tipo-card" id="card1">
                                    <input type="radio" name="tipo_servicio" value="Traslado" required>
                                    <h4 class="mb-2">Traslado Programado</h4>
                                    <p class="tipo-text">Llevamos al paciente de un punto A hacia un punto B con total seguridad clínica.</p>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="tipo-card" id="card2">
                                    <input type="radio" name="tipo_servicio" value="Evento" required>
                                    <h4 class="mb-2">Cobertura de Evento</h4>
                                    <p class="tipo-text">Ambulancia y paramédicos a disposición durante toda la duración de su evento.</p>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Descripción de la solicitud <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="descripcion" rows="2" placeholder="Describa brevemente su necesidad operativa..."></textarea>
                        </div>

                        <div id="wrap-padecimientos" class="d-none mb-4">
                            <label class="form-label">Cuadro Clínico del Paciente <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="padecimientos_paciente" rows="2" placeholder="Ej. Hipertensión, requiere oxígeno..."></textarea>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Fecha del servicio <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_requerida" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Duración estimada (Horas) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="horas_servicio" id="horas_servicio" value="1" min="1" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PASO 3: UBICACIÓN --}}
                <div class="timeline-item">
                    <div class="timeline-badge">3</div>
                    <div class="premium-card">
                        <h3 class="section-title">Coordenadas Logísticas</h3>

                        <div class="mb-4">
                            <label class="form-label">Punto de Origen <span class="text-danger">*</span></label>
                            <div id="map-origen" class="map-box mb-3"></div>
                            <input class="form-control" name="origen" id="origen" placeholder="Dirección exacta de recolección">
                        </div>

                        <div id="wrap-destino" class="d-none mb-2">
                            <label class="form-label">Punto de Destino <span class="text-danger">*</span></label>
                            <div id="map-destino" class="map-box mb-3"></div>
                            <input class="form-control" name="destino" id="destino" placeholder="Hospital, clínica o domicilio">
                        </div>
                    </div>
                </div>

                <!-- Elementos ocultos e input submit -->
                <div class="timeline-item" style="padding-left: 60px;">
                    <input type="hidden" name="km_distancia" id="km_distancia" value="0">
                    <input type="hidden" name="precio_final" id="precio_final" value="0">

                    <button class="btn btn-premium w-100 py-3" type="submit">
                        Confirmar y Solicitar Servicio
                    </button>
                </div>

            </div>
            </form>
        </div>
    </div>
</div>
</section>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
function initMapas() {
    if (typeof L === 'undefined') return;
    try {
        window.mapOrigen = L.map('map-origen').setView([17.06, -96.72], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19,
        }).addTo(mapOrigen);
        window.markerOrigen = L.marker([17.06, -96.72], {draggable:true}).addTo(mapOrigen);

        window.mapDestino = L.map('map-destino').setView([17.06, -96.72], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19,
        }).addTo(mapDestino);
        window.markerDestino = L.marker([17.06, -96.72], {draggable:true}).addTo(mapDestino);

        window.updateDistance = function() {
            let latLngOrigen = markerOrigen.getLatLng();
            let latLngDestino = markerDestino.getLatLng();
            let distanceMeters = latLngOrigen.distanceTo(latLngDestino);
            let distanceKm = (distanceMeters / 1000).toFixed(2);
            document.getElementById('km_distancia').value = distanceKm;
            triggerPrediction();
        };

        markerOrigen.on('dragend', function(e){
            let p = e.target.getLatLng();
            let origenInput = document.getElementById('origen');
            origenInput.value = p.lat + ', ' + p.lng;
            origenInput.dispatchEvent(new Event('input'));
            window.updateDistance();
        });

        markerDestino.on('dragend', function(e){
            let p = e.target.getLatLng();
            let destinoInput = document.getElementById('destino');
            destinoInput.value = p.lat + ', ' + p.lng;
            destinoInput.dispatchEvent(new Event('input'));
            window.updateDistance();
        });
    } catch(e) {
        console.warn('Mapa no disponible:', e);
    }
}

let predictionTimeout = null;
function triggerPrediction() {
    clearTimeout(predictionTimeout);
    
    const tipoServicio = document.querySelector('input[name="tipo_servicio"]:checked');
    if (!tipoServicio) return;
    
    const isTraslado = tipoServicio.value === 'Traslado';
    const inputOrigen = document.getElementById('origen');
    const inputDestino = document.getElementById('destino');
    const horasServicio = document.getElementById('horas_servicio');
    
    if (isTraslado && (!inputOrigen.value || !inputDestino.value)) return;
    if (!isTraslado && !inputOrigen.value) return;

    document.getElementById('ai-prediction-card').classList.remove('d-none');
    document.getElementById('ai-loading').classList.remove('d-none');
    document.getElementById('ai-result').classList.add('d-none');
    
    const messages = ["Extrayendo dataset histórico...", "Filtrando valores atípicos...", "Calculando proyección..."];
    let msgIndex = 0;
    const msgInterval = setInterval(() => {
        document.getElementById('ai-loading-text').innerText = messages[msgIndex % messages.length];
        msgIndex++;
    }, 800);

    const payload = {
        km_distancia: document.getElementById('km_distancia').value,
        horas_servicio: horasServicio.value,
        tipo_servicio: tipoServicio.value
    };

    predictionTimeout = setTimeout(() => {
        fetch(`/predecir?km_distancia=${payload.km_distancia}&horas_servicio=${payload.horas_servicio}&tipo_servicio=${payload.tipo_servicio}`)
            .then(res => res.json())
            .then(data => {
                clearInterval(msgInterval);
                document.getElementById('ai-loading').classList.add('d-none');
                document.getElementById('ai-result').classList.remove('d-none');
                
                document.getElementById('est-total').innerText = '$' + Number(data.precio_sugerido).toLocaleString('en-US', {minimumFractionDigits: 2});
                document.getElementById('est-tipo').innerText = data.tipo_traslado;
                document.getElementById('precio_final').value = data.precio_sugerido;
                
                document.getElementById('distancia-explicada').innerText = payload.km_distancia;
                document.getElementById('horas-explicadas').innerText = payload.horas_servicio;

                if(data.precision_modelo) {
                    document.getElementById('badge-precision').innerText = data.precision_modelo;
                    document.getElementById('badge-analizados').innerText = data.traslados_analizados;
                    document.getElementById('badge-outliers').innerText = data.outliers_filtrados;
                }
                
                document.querySelector('button[type="submit"]').disabled = false;
            })
            .catch(err => {
                clearInterval(msgInterval);
                console.error(err);
            });
    }, 1500); 
}

document.getElementById('horas_servicio').addEventListener('change', triggerPrediction);

const cards = document.querySelectorAll('.tipo-card');
const radios = document.querySelectorAll('input[name="tipo_servicio"]');

radios.forEach(radio => {
    radio.addEventListener('change', (e) => {
        cards.forEach(card => card.classList.remove('selected'));
        radio.closest('.tipo-card').classList.add('selected');

        let tipo = e.target.value;
        document.getElementById('wrap-padecimientos').classList.toggle('d-none', tipo !== 'Traslado');
        document.getElementById('wrap-destino').classList.toggle('d-none', tipo !== 'Traslado');
        
        if (tipo === 'Traslado') {
            setTimeout(() => {
                if (window.mapDestino) window.mapDestino.invalidateSize();
            }, 200);
        }
        document.querySelector('textarea[name="descripcion"]').dispatchEvent(new Event('input'));
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const inputNombre = document.querySelector('input[name="nombre"]');
    const inputPApellido = document.querySelector('input[name="pApellido"]');
    const inputSApellido = document.querySelector('input[name="sApellido"]');
    const inputTelefono = document.querySelector('input[name="telefono"]');
    const radiosTipo = document.querySelectorAll('input[name="tipo_servicio"]');
    const inputDescripcion = document.querySelector('textarea[name="descripcion"]');
    const inputFecha = document.querySelector('input[name="fecha_requerida"]');
    const inputHoras = document.querySelector('input[name="horas_servicio"]');
    const inputOrigen = document.querySelector('input[name="origen"]');
    const inputDestino = document.querySelector('input[name="destino"]');
    const inputPadecimientos = document.querySelector('textarea[name="padecimientos_paciente"]');
    const btnSubmit = document.querySelector('button[type="submit"]');

    initMapas();

    inputPApellido.disabled = true;
    inputSApellido.disabled = true;
    inputTelefono.disabled = true;
    inputFecha.disabled = true;
    inputHoras.disabled = true;
    inputDescripcion.disabled = true;
    inputOrigen.disabled = true;
    inputDestino.disabled = true;
    inputPadecimientos.disabled = true;
    btnSubmit.disabled = true;

    radiosTipo.forEach(r => {
        r.disabled = true;
        r.closest('.tipo-card').style.opacity = '0.5';
        r.closest('.tipo-card').style.pointerEvents = 'none';
    });

    const hasValue = (el) => el.value.trim().length > 0;

    inputNombre.addEventListener('input', () => {
        if(hasValue(inputNombre)) {
            inputPApellido.disabled = false;
        } else {
            inputPApellido.disabled = true;
            inputPApellido.value = '';
            inputPApellido.dispatchEvent(new Event('input'));
        }
    });

    inputPApellido.addEventListener('input', () => {
        if(hasValue(inputPApellido)) {
            inputSApellido.disabled = false;
            inputTelefono.disabled = false;
        } else {
            inputSApellido.disabled = true;
            inputTelefono.disabled = true;
            inputSApellido.value = '';
            inputTelefono.value = '';
            inputTelefono.dispatchEvent(new Event('input'));
        }
    });

    inputTelefono.addEventListener('input', () => {
        if(inputTelefono.value.trim().length >= 10) {
            radiosTipo.forEach(r => {
                r.disabled = false;
                r.closest('.tipo-card').style.opacity = '1';
                r.closest('.tipo-card').style.pointerEvents = 'auto';
            });
        } else {
            radiosTipo.forEach(r => {
                r.disabled = true;
                r.checked = false;
                r.closest('.tipo-card').style.opacity = '0.5';
                r.closest('.tipo-card').style.pointerEvents = 'none';
                r.closest('.tipo-card').classList.remove('selected');
            });
            inputFecha.disabled = true;
            inputHoras.disabled = true;
            inputDescripcion.disabled = true;
            inputFecha.value = '';
            inputHoras.value = '1';
            inputDescripcion.value = '';
            inputDescripcion.dispatchEvent(new Event('input'));
        }
    });

    radiosTipo.forEach(radio => {
        radio.addEventListener('change', () => {
            if(radio.checked) {
                inputFecha.disabled = false;
                inputHoras.disabled = false;
                inputDescripcion.disabled = false;
                inputDescripcion.dispatchEvent(new Event('input'));
            }
        });
    });

    const checkPaso2 = () => {
        if(hasValue(inputDescripcion) && hasValue(inputFecha) && hasValue(inputHoras)) {
            inputOrigen.disabled = false;
            const isTraslado = document.querySelector('input[name="tipo_servicio"]:checked')?.value === 'Traslado';
            if(isTraslado) {
                inputPadecimientos.disabled = false;
                inputDestino.disabled = false;
            } else {
                inputPadecimientos.disabled = true;
                inputDestino.disabled = true;
                inputPadecimientos.value = '';
                inputDestino.value = '';
            }
            checkSubmit();
        } else {
            inputOrigen.disabled = true;
            inputPadecimientos.disabled = true;
            inputDestino.disabled = true;
            inputOrigen.value = '';
            inputPadecimientos.value = '';
            inputDestino.value = '';
            checkSubmit();
        }
    };

    inputDescripcion.addEventListener('input', checkPaso2);
    inputFecha.addEventListener('input', checkPaso2);
    inputHoras.addEventListener('input', checkPaso2);

    inputOrigen.addEventListener('input', checkSubmit);
    inputDestino.addEventListener('input', checkSubmit);
    inputPadecimientos.addEventListener('input', checkSubmit);

    function checkSubmit() {
        const isTraslado = document.querySelector('input[name="tipo_servicio"]:checked')?.value === 'Traslado';
        let isValid = false;

        if (isTraslado) {
            if (hasValue(inputOrigen) && hasValue(inputDestino) && hasValue(inputPadecimientos)) {
                isValid = true;
            }
        } else {
            if (hasValue(inputOrigen)) {
                isValid = true;
            }
        }

        btnSubmit.disabled = !isValid;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>