<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitar Cotización — {{ $empresa->nombre ?? config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">

    <style>
        body {
            background:#f5f5f9;
            font-family:sans-serif;
        }

        .form-card {
            border:none;
            border-radius:16px;
            background:#fff;
            box-shadow: 0 4px 24px 0 rgba(25, 25, 112, 0.08);
        }
        
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

        .step-badge {
            width:30px;
            height:30px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#8A2BE2;
            color:#fff;
            font-weight:bold;
        }

        .map-box {
            height:300px;
            border-radius:10px;
        }

        .tipo-card {
            border:2px solid #e5e5e5;
            border-radius:10px;
            padding:16px;
            cursor:pointer;
            transition:.2s ease;
            background:#fff;
        }

        .tipo-card:hover {
            border-color:#8A2BE2;
            transform:translateY(-2px);
        }

        .tipo-card.selected {
            border-color:#8A2BE2;
            background:rgba(138, 43, 226, 0.05);
            box-shadow:0 4px 15px rgba(138,43,226,.15);
        }

        .tipo-card input {
            display:none;
        }

        .tipo-title {
            font-weight:bold;
            color:#333;
            margin-bottom:5px;
        }

        .tipo-text {
            color:#666;
            font-size:14px;
        }
    </style>
</head>

<body>

<nav class="navbar bg-white shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold">
            {{ $empresa->nombre ?? config('app.name') }}
        </span>
    </div>
</nav>

@php
    $costoKm = $empresa->costo_km ?? 25;
@endphp

<section class="py-5">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="form-card p-4">

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('cotizaciones.store') }}">
@csrf

{{-- CONTACTO --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2 align-items-center">
        <div class="step-badge">1</div>
        <h5 class="mb-0 fw-bold" style="color: #393395;">Contacto</h5>
    </div>
    <span class="text-danger small fw-semibold">* datos obligatorios</span>
</div>

<div class="row g-3 mb-4">
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
        <input class="form-control" name="sApellido" placeholder="Ej. García (Opcional)">
    </div>
    <div class="col-md-6">
        <label class="form-label">Teléfono <span class="text-danger">*</span></label>
        <input class="form-control" name="telefono" placeholder="A 10 dígitos" maxlength="10" required>
    </div>
</div>

<hr>

{{-- SERVICIO --}}
<div class="d-flex gap-2 align-items-center mb-3">
    <div class="step-badge">2</div>
    <h5 class="mb-0 fw-bold" style="color: #393395;">Servicio</h5>
</div>

<div style="display:flex; gap:15px; margin-bottom: 15px;">

    <label class="tipo-card w-50" id="card1">
        <input type="radio" name="tipo_servicio" value="Traslado" required>
        <h4>Traslado Programado</h4>
        <p class="tipo-text">Llevamos al paciente de punto A a punto B.</p>
    </label>

    <label class="tipo-card w-50" id="card2">
        <input type="radio" name="tipo_servicio" value="Evento" required>
        <h4>Evento Privado</h4>
        <p class="tipo-text">Ambulancia y personal capacitado a la disposicion de su evento.</p>
    </label>

</div>

<div class="mb-3">
    <label class="form-label">Descripción de la solicitud <span class="text-danger">*</span></label>
    <textarea class="form-control" name="descripcion" placeholder="Explica brevemente lo que necesitas..."></textarea>
</div>

<div id="wrap-padecimientos" class="d-none mb-3">
    <label class="form-label">Padecimientos o estado del paciente <span class="text-danger">*</span></label>
    <textarea class="form-control" name="padecimientos_paciente"
        placeholder="Ej. Hipertensión, requiere oxígeno, etc."></textarea>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Fecha requerida <span class="text-danger">*</span></label>
        <input type="date" class="form-control" name="fecha_requerida" min="{{ date('Y-m-d') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Horas de servicio requeridas <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="horas_servicio" id="horas_servicio" value="1" min="1" required>
    </div>
</div>

<hr>

{{-- MAPA --}}
<div class="d-flex gap-2 align-items-center mb-3">
    <div class="step-badge">3</div>
    <h5 class="mb-0 fw-bold" style="color: #393395;">Ubicación</h5>
</div>

<div class="mb-3">
    <label class="form-label">Dirección de origen <span class="text-danger">*</span></label>
    <div id="map-origen" class="map-box mb-2"></div>
    <input class="form-control" name="origen" id="origen" placeholder="Dirección exacta de recolección">
</div>

<div id="wrap-destino" class="d-none mb-3">
    <label class="form-label">Dirección de destino <span class="text-danger">*</span></label>
    <div id="map-destino" class="map-box mb-2"></div>
    <input class="form-control" name="destino" id="destino" placeholder="Hospital o lugar de destino">
</div>

<hr>

{{-- PRECIO / PREDICCIÓN AI --}}
<div id="ai-prediction-card" class="d-none mb-4 p-4" style="border-radius:16px; background: linear-gradient(135deg, #191970 0%, #393395 100%); color:white; box-shadow: 0 10px 30px rgba(57, 51, 149, 0.3); position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%); opacity: 0.5; pointer-events: none;"></div>
    
    <div class="d-flex align-items-center mb-3">
        <i class='bx bx-brain bx-tada fs-2 me-2' style="color: #FF7F50;"></i>
        <h5 class="mb-0 fw-bold">Predicción Inteligente</h5>
    </div>
    
    <div id="ai-loading" class="text-center py-3">
        <div class="spinner-border text-light mb-2" role="status"></div>
        <p class="mb-0 small" style="opacity: 0.8;" id="ai-loading-text">Analizando variables de predicción...</p>
    </div>
    
    <div id="ai-result" class="d-none">
        <div class="row text-center">
            <div class="col-6" style="border-right: 1px solid rgba(255,255,255,0.2);">
                <span class="small d-block mb-1" style="opacity: 0.8;">Precio Sugerido</span>
                <h3 class="mb-0 fw-bold" id="est-total" style="color: #FF7F50;">$0.00</h3>
            </div>
            <div class="col-6">
                <span class="small d-block mb-1" style="opacity: 0.8;">Tipo de Traslado</span>
                <h5 class="mb-0 fw-bold mt-1" id="est-tipo">--</h5>
            </div>
        </div>
        
        <!-- Explicabilidad (Collapse custom) -->
        <div class="mt-3" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 12px;">
            <a data-bs-toggle="collapse" href="#collapseExplicacion" role="button" aria-expanded="false" aria-controls="collapseExplicacion" style="color: white; text-decoration: none; font-size: 0.9rem; display: flex; align-items: center;">
                <i class='bx bx-info-circle me-2'></i> ¿Cómo calculamos este precio? <i class='bx bx-chevron-down ms-auto'></i>
            </a>
            <div class="collapse mt-3" id="collapseExplicacion">
                <div class="p-3" style="background: rgba(0,0,0,0.15); border-radius: 8px; font-size: 0.85rem; color: #f0f0f0;">
                    Nuestra IA analizó tu solicitud considerando los siguientes factores para ofrecerte la tarifa más precisa y justa:
                    <ul class="mt-2 mb-2 ps-0" style="list-style: none;">
                        <li class="mb-2"><i class='bx bx-map-alt me-1' style="color: #FF7F50;"></i> <strong>Distancia de ruta:</strong> Calculado en base al trayecto de <span id="distancia-explicada">0</span> km.</li>
                        <li class="mb-2"><i class='bx bx-time-five me-1' style="color: #FF7F50;"></i> <strong>Tiempo base:</strong> Incluye la cobertura por <span id="horas-explicadas">1</span> hora(s).</li>
                        <li><i class='bx bx-plus-medical me-1' style="color: #FF7F50;"></i> <strong>Nivel de atención:</strong> Contempla paramédicos y el equipo médico necesario.</li>
                    </ul>
                    <em style="opacity: 0.8; font-size: 0.8rem;">*Nota: Este es un costo base estimado de alta precisión. Si se requiere oxígeno o equipo extra, podría haber un ajuste final.</em>
                </div>
            </div>
        </div>
        
        <!-- Trust Badges IA -->
        <div class="mt-3 pt-3" style="border-top: 1px dashed rgba(255,255,255,0.2); font-size: 0.8rem; opacity: 0.9;">
            <div class="d-flex align-items-start text-start mb-2">
                <i class='bx bx-check-shield me-2 mt-1' style="color: #10B981; font-size: 1.1rem;"></i>
                <div>
                    <strong>Validado por Inteligencia Artificial:</strong> Precio calculado analizando <span id="badge-analizados" class="fw-bold">--</span> traslados previos con una precisión del <span id="badge-precision" class="fw-bold" style="color: #10B981;">--%</span>.
                </div>
            </div>
            <div class="d-flex align-items-start text-start">
                <i class='bx bx-filter-alt me-2 mt-1' style="color: #FFB020; font-size: 1.1rem;"></i>
                <div>
                    <strong>Garantía de Cobro Justo:</strong> El motor ha filtrado <span id="badge-outliers" class="fw-bold">--</span> anomalías (outliers) de precios históricos para ofrecerte la tarifa más competitiva.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden inputs to send distance and predicted price -->
    <input type="hidden" name="km_distancia" id="km_distancia" value="0">
    <input type="hidden" name="precio_final" id="precio_final" value="0">
</div>

<button class="btn w-100 mt-3 text-white fw-bold shadow-sm" style="background: #393395; border: none; padding: 12px; font-size: 1.1rem; border-radius: 50rem; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(57, 51, 149, 0.2);" onmouseover="this.style.background='#2a2575'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#393395'; this.style.transform='none';" type="submit">
    Enviar solicitud
</button>

</form>

</div>
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
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(mapOrigen);
        window.markerOrigen = L.marker([17.06, -96.72], {draggable:true}).addTo(mapOrigen);

        window.mapDestino = L.map('map-destino').setView([17.06, -96.72], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
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

// Lógica de Predicción AI
let predictionTimeout = null;
function triggerPrediction() {
    clearTimeout(predictionTimeout);
    
    // Check if we have enough data to predict
    const tipoServicio = document.querySelector('input[name="tipo_servicio"]:checked');
    if (!tipoServicio) return;
    
    const isTraslado = tipoServicio.value === 'Traslado';
    const inputOrigen = document.getElementById('origen');
    const inputDestino = document.getElementById('destino');
    const horasServicio = document.getElementById('horas_servicio');
    
    if (isTraslado && (!inputOrigen.value || !inputDestino.value)) return;
    if (!isTraslado && !inputOrigen.value) return;

    // Show AI Card and Loading State
    document.getElementById('ai-prediction-card').classList.remove('d-none');
    document.getElementById('ai-loading').classList.remove('d-none');
    document.getElementById('ai-result').classList.add('d-none');
    
    const messages = ["Analizando variables de predicción...", "Ejecutando modelo de regresión...", "Calculando factores de riesgo..."];
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
                
                // Actualizar valores de explicabilidad
                document.getElementById('distancia-explicada').innerText = payload.km_distancia;
                document.getElementById('horas-explicadas').innerText = payload.horas_servicio;

                // Actualizar los Trust Badges
                if(data.precision_modelo) {
                    document.getElementById('badge-precision').innerText = data.precision_modelo + '%';
                    document.getElementById('badge-analizados').innerText = data.traslados_analizados;
                    document.getElementById('badge-outliers').innerText = data.outliers_filtrados;
                }
                
                // Actualizar validación de form
                document.querySelector('button[type="submit"]').disabled = false;
                document.querySelector('button[type="submit"]').style.opacity = '1';
                document.querySelector('button[type="submit"]').style.cursor = 'pointer';
            })
            .catch(err => {
                clearInterval(msgInterval);
                console.error(err);
            });
    }, 1500); // Artificial delay to show the "Wow" AI thinking animation
}

document.getElementById('horas_servicio').addEventListener('change', triggerPrediction);

// CORRECCIÓN: Lógica correcta para manejar los botones de radio
const cards = document.querySelectorAll('.tipo-card');
const radios = document.querySelectorAll('input[name="tipo_servicio"]');

radios.forEach(radio => {
    radio.addEventListener('change', (e) => {
        // Quitar la clase selected a todos
        cards.forEach(card => {
            card.classList.remove('selected');
        });

        // Añadir la clase selected al contenedor del radio actual
        radio.closest('.tipo-card').classList.add('selected');

        // Mostrar u ocultar padecimientos y destino según la opción
        let tipo = e.target.value;
        document.getElementById('wrap-padecimientos').classList.toggle('d-none', tipo !== 'Traslado');
        document.getElementById('wrap-destino').classList.toggle('d-none', tipo !== 'Traslado');
        
        if (tipo === 'Traslado') {
            setTimeout(() => {
                if (window.mapDestino) {
                    window.mapDestino.invalidateSize();
                }
            }, 200);
        }
        
        // Al cambiar de tipo de servicio, re-evaluar los campos dependientes
        document.querySelector('textarea[name="descripcion"]').dispatchEvent(new Event('input'));
    });
});

// Lógica de llenado secuencial de izquierda a derecha, de arriba hacia abajo
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

    // Deshabilitar todos excepto el primero al cargar
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
    btnSubmit.style.opacity = '0.5';

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
        if(isValid) {
            btnSubmit.style.opacity = '1';
            btnSubmit.style.cursor = 'pointer';
        } else {
            btnSubmit.style.opacity = '0.5';
            btnSubmit.style.cursor = 'not-allowed';
        }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>