<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitar Cotización — {{ $empresa->nombre ?? config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

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

{{-- PRECIO --}}
<div id="wrap-estimado" class="d-none alert alert-info">
    <div>Tipo: <span id="est-tipo"></span></div>
    <div>Total: <b id="est-total"></b></div>
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
let tipoActual = null;
let costoKm = {{ $costoKm }};

function selectTipo(nombre, costo, el){
    document.querySelectorAll('.tipo-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');

    tipoActual = {nombre, costo};

    document.getElementById('wrap-estimado').classList.remove('d-none');
    document.getElementById('est-tipo').innerText = nombre;
    document.getElementById('est-total').innerText = '$' + costo.toFixed(2);
}

// Inicialización del Mapa
let map = L.map('map-origen').setView([17.06, -96.72], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'OSM'
}).addTo(map);

let marker = L.marker([17.06, -96.72], {draggable:true}).addTo(map);
marker.on('dragend', function(e){
    let p = e.target.getLatLng();
    let origenInput = document.getElementById('origen');
    origenInput.value = p.lat + ', ' + p.lng;
    origenInput.dispatchEvent(new Event('input')); // Disparar evento para validación secuencial
});

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
    const inputOrigen = document.querySelector('input[name="origen"]');
    const inputDestino = document.querySelector('input[name="destino"]');
    const inputPadecimientos = document.querySelector('textarea[name="padecimientos_paciente"]');
    const btnSubmit = document.querySelector('button[type="submit"]');

    // Deshabilitar todos excepto el primero al cargar
    inputPApellido.disabled = true;
    inputSApellido.disabled = true;
    inputTelefono.disabled = true;
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
            inputDescripcion.disabled = true;
            inputDescripcion.value = '';
            inputDescripcion.dispatchEvent(new Event('input'));
        }
    });

    radiosTipo.forEach(radio => {
        radio.addEventListener('change', () => {
            if(radio.checked) {
                inputDescripcion.disabled = false;
                inputDescripcion.dispatchEvent(new Event('input'));
            }
        });
    });

    inputDescripcion.addEventListener('input', () => {
        if(hasValue(inputDescripcion)) {
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
    });

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

</body>
</html>