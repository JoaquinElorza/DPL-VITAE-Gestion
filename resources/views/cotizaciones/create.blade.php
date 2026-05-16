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
            box-shadow:0 10px 40px rgba(0,0,0,.08);
        }

        .step-badge {
            width:30px;
            height:30px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#6366f1;
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
            border-color:#6366f1;
            transform:translateY(-2px);
        }

        .tipo-card.selected {
            border-color:#6366f1;
            background:#eef2ff;
            box-shadow:0 4px 15px rgba(99,102,241,.15);
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
<div class="d-flex gap-2 align-items-center mb-3">
    <div class="step-badge">1</div>
    <h5 class="mb-0">Contacto</h5>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <input class="form-control" name="nombre" placeholder="Nombre" required>
        <input class="form-control" name="pApellido" placeholder="Primer apellido" required>
        <input class="form-control" name="sApellido" placeholder="Segundo apellido">
    </div>
    <div class="col-md-6">
        <input class="form-control" name="telefono" placeholder="Teléfono" maxlength="10" required>
    </div>
</div>

<hr>

{{-- SERVICIO --}}
<div class="d-flex gap-2 align-items-center mb-3">
    <div class="step-badge">2</div>
    <h5 class="mb-0">Servicio</h5>
</div>

<div style="display:flex; gap:15px;">

    <label class="tipo-card" id="card1">
        <input type="radio" name="tipo" value="basica">
        
        <h4>Traslado Programado</h4>
        <p>Llevamos al paciente de punto A a punto B.</p>
    </label>

    <label class="tipo-card" id="card2">
        <input type="radio" name="tipo" value="avanzada">

        <h4>Evento Privado</h4>
        <p>Ambulancia y personal capacitado a la disposicion de su evento.</p>
    </label>

</div>

<textarea class="form-control mb-3" name="descripcion" placeholder="Descripción"></textarea>

<div id="wrap-padecimientos" class="d-none mb-3">
    <textarea class="form-control" name="padecimientos_paciente"
        placeholder="Padecimientos del paciente"></textarea>
</div>

<hr>


<hr>

{{-- MAPA --}}
<div class="d-flex gap-2 align-items-center mb-3">
    <div class="step-badge">4</div>
    <h5 class="mb-0">Ubicación</h5>
</div>

<div id="map-origen" class="map-box mb-2"></div>

<input class="form-control mb-3" name="origen" id="origen">

<div id="wrap-destino" class="d-none">
    <div id="map-destino" class="map-box mb-2"></div>
    <input class="form-control" name="destino" id="destino">
</div>

<hr>

{{-- PRECIO --}}
<div id="wrap-estimado" class="d-none alert alert-info">
    <div>Tipo: <span id="est-tipo"></span></div>
    <div>Total: <b id="est-total"></b></div>
</div>

<button class="btn btn-primary w-100 mt-3">
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

document.getElementById('tipo_servicio').addEventListener('change', e => {
    let tipo = e.target.value;

    document.getElementById('wrap-padecimientos')
        .classList.toggle('d-none', tipo !== 'Traslado');

    document.getElementById('wrap-destino')
        .classList.toggle('d-none', tipo !== 'Traslado');
});

let map = L.map('map-origen').setView([17.06, -96.72], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'OSM'
}).addTo(map);

let marker = L.marker([17.06, -96.72], {draggable:true}).addTo(map);

marker.on('dragend', function(e){
    let p = e.target.getLatLng();
    document.getElementById('origen').value = p.lat + ', ' + p.lng;
});

</script>

<script>
    const cards = document.querySelectorAll('.tipo-card');
    const radios = document.querySelectorAll('input[type="radio"]');

    radios.forEach(radio => {
        radio.addEventListener('change', () => {

            cards.forEach(card => {
                card.classList.remove('selected');
            });

            radio.closest('.tipo-card').classList.add('selected');
        });
    });
</script>

</body>
</html>