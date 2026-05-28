<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $empresa->nombre ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>

/* ───────── HERO PREMIUM ───────── */

.hero-premium {
    background:
        radial-gradient(circle at top left, rgba(167,139,250,.15), transparent 30%),
        radial-gradient(circle at bottom right, rgba(220,38,38,.08), transparent 25%),
        #ffffff;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(167,139,250,.15);
    color: #6d28d9;
    border: 1px solid rgba(167,139,250,.25);
    padding: .7rem 1.2rem;
    border-radius: 999px;
    font-weight: 600;
    font-size: .95rem;
}

.hero-title {
    font-size: clamp(3rem, 6vw, 5.5rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.04em;
}

.hero-description {
    font-size: 1.15rem;
    line-height: 1.8;
    font-weight: 400;
    color: #64748b;
}


.hero-image {
    width: 100%;
    max-width: 420px;
    height: 520px;
    object-fit: cover;

    border-radius: 32px;
    border: 8px solid white;

    box-shadow:
        0 30px 60px rgba(15,23,42,.12);
}

.hero-image-wrapper {
    position: relative;
    display: inline-block;
}

.hero-image-wrapper::before {
    content: '';
    position: absolute;
    inset: -20px;
    background:
        linear-gradient(
            135deg,
            rgba(167,139,250,.2),
            rgba(15,23,42,.08)
        );

    border-radius: 40px;
    z-index: -1;
}

.hero-placeholder {
    width: 520px;
    height: 520px;
    border-radius: 32px;
    background:
        linear-gradient(135deg, #dc2626, #ef4444);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 7rem;
}

.hero-premium .btn-danger {
    background: linear-gradient(
        135deg,
        #dc2626,
        #991b1b
    );

    border: none;

    box-shadow:
        0 10px 30px rgba(220,38,38,.25);

    transition: all .3s ease;
}

.hero-premium .btn-danger:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.hero-premium .btn-outline-dark:hover {
    transform: translateY(-2px);
}

.card-info {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 24px;

    padding: 28px 26px;

    transition: all 0.3s ease;

    box-shadow: none;
}

.card-info:hover {
    transform: translateY(-6px);

    border-color: rgba(167, 139, 250, 0.35);

    box-shadow:
        0 20px 40px rgba(15, 23, 42, 0.06);
}

.card-info h4,
.card-info h6 {
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}

.card-info p {
    color: #64748b;
    line-height: 1.6;
    font-size: 0.95rem;
}

.card-info.premium {
    background: linear-gradient(
        135deg,
        rgba(167, 139, 250, 0.04),
        rgba(220, 38, 38, 0.02)
    );

    border: 1px solid rgba(167, 139, 250, 0.15);
}

.section-title {
    font-size: 2.4rem;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.section-title-left {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.btn {
    font-weight: 600;
    letter-spacing: -0.01em;
}

section {
    padding-top: 120px;
    padding-bottom: 120px;
}

@media (min-width: 1400px) {
    .container {
        max-width: 1240px;
    }
}

section + section {
    margin-top: 40px;
}

section {
    border-top: 1px solid rgba(15, 23, 42, 0.06);
}
    

.section-title,
.section-title-left {
    margin-bottom: 48px;
}

#contacto .row,
#valores .row {
    row-gap: 24px;
}

p {
    font-size: 1rem;
    line-height: 1.75;
    color: #475569;
}

body {
    font-family: 'Inter', sans-serif;
    color: #0f172a;
    font-weight: 400;
}

h1, h2 {
    letter-spacing: -0.03em;
}

h1, h2, h3 {
    font-family: 'Manrope', sans-serif;
    color: #0f172a;
    letter-spacing: -0.03em;
}

    </style>
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
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

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="#mision">Misión y Visión</a></li>
                <li class="nav-item"><a class="nav-link" href="#valores">Valores</a></li>
                <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>

                @auth
                    <li class="nav-item">
                        <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="nav-link text-muted">
                            <i class="bx bx-list-ul me-1"></i> Mis solicitudes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-primary btn-sm px-3">
                            <i class="bx bx-calculator me-1"></i> Cotizar
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-primary btn-sm px-3">
                            <i class="bx bx-calculator me-1"></i> Cotizar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3">Iniciar Sesión</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>


{{-- ── Hero Premium ── --}}
<section class="hero-premium">
    <div class="container">
        <div class="row align-items-center min-vh-100 py-5">

            {{-- Texto --}}
            <div class="col-lg-6">

                <span class="hero-badge">
                    Atención médica profesional
                </span>

                <h1 class="hero-title mt-4">
                    Atención prehospitalaria
                    rápida, segura y profesional.
                </h1>

                <p class="hero-description mt-4">
                    En DPL VITAE
                    brindamos servicios médicos y traslados especializados
                    con personal capacitado y unidades equipadas para cada situación.
                </p>

                <div class="d-flex flex-wrap gap-3 mt-5">

                    <a href="{{ route('cotizaciones.create') }}"
                       class="btn btn-danger btn-lg px-4 py-3 rounded-pill">
                        Cotizar Servicio
                    </a>

                    <a href="#nosotros"
                       class="btn btn-outline-dark btn-lg px-4 py-3 rounded-pill">
                        Conocer más
                    </a>

                </div>

                {{-- Métricas --}}
                <div class="row mt-5 g-5">

                    <div class="col-4">
                        <h3 class="fw-bold text-danger mb-1">24/7</h3>
                        <small class="text-muted">
                            Atención continua
                        </small>
                    </div>

                    <div class="col-4">
                        <h3 class="fw-bold text-danger mb-1">+100</h3>
                        <small class="text-muted">
                            Servicios realizados
                        </small>
                    </div>

                    <div class="col-4">
                        <h3 class="fw-bold text-danger mb-1">100%</h3>
                        <small class="text-muted">
                            Personal capacitado
                        </small>
                    </div>

                </div>

            </div>

            {{-- Imagen --}}
            <div class="col-lg-6 text-center mt-5 mt-lg-0">

                <div class="hero-image-wrapper">

                    @if($empresa && $empresa->imagen_nombre)
                        <img
                            src="{{ asset('storage/' . $empresa->imagen_nombre) }}"
                            alt="{{ $empresa->nombre }}"
                            class="hero-image">
                    @else

                        <div class="hero-placeholder">
                            <i class="bx bx-plus-medical"></i>
                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>
</section>

{{-- ── Nosotros ── --}}
@if($empresa)
<section id="nosotros" class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="section-title-left mt-2 mb-4">{{ $empresa->nombre }}</h2>
                @if($empresa->descripcion)
                    <p class="text-muted fs-5">{{ $empresa->descripcion }}</p>
                @endif
                @if($empresa->slogan)
                    <blockquote class="blockquote border-start border-primary border-3 ps-3 mt-4">
                        <p class="fst-italic text-primary fs-5">"{{ $empresa->slogan }}"</p>
                    </blockquote>
                @endif
            </div>
            <div class="col-lg-6 text-center">
                @if($empresa->imagen_nombre)
                    <img src="{{ asset('storage/' . $empresa->imagen_nombre) }}"
                         alt="{{ $empresa->nombre }}" class="img-fluid rounded-3 shadow">
                @else
                    <div class="rounded-3 p-5 d-flex align-items-center justify-content-center"
                         style="min-height:300px; background: linear-gradient(135deg, #8b5cf6, #3b82f6);">
                        <i class="bx bx-ambulance text-white" style="font-size:8rem;"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── Misión y Visión ── --}}
@if($empresa && ($empresa->mision || $empresa->vision))
<section id="mision" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Misión y Visión</h2>
        </div>
        <div class="row g-5">
            @if($empresa->mision)
                <div class="card card-info h-100 p-2">
                        <h4>Misión</h4>
                    <p class="text-muted mb-0">{{ $empresa->mision }}</p>
                </div>
            @endif
            @if($empresa->vision)
                <div class="card card-info h-100 p-4">
                        <h4>Visión</h4>
                    <p class="text-muted mb-0">{{ $empresa->vision }}</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ── Valores ── --}}
@if($empresa && $empresa->valores)
<section id="valores" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Valores</h2>
        </div>
        @php
            $valoresList = array_filter(
                array_map('trim', preg_split('/[\n,]+/', $empresa->valores))
            );
        @endphp
        <div class="row g-3 justify-content-center">
            @foreach($valoresList as $valor)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card card-info text-center p-3">
                    <i class="bx bx-check-shield text-primary fs-2 mb-2"></i>
                    <p class="fw-semibold mb-0">{{ $valor }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Contacto ── --}}
@if($empresa && ($empresa->telefono || $empresa->correo || $empresa->sitio_web || $empresa->direccion))
<section id="contacto" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Contacto</h2>
        </div>
        <div class="row g-4 justify-content-center">
            @if($empresa->telefono)
            <div class="col-sm-6 col-md-3">
                <div class="card card-info text-center p-4">
                    <i class="bx bx-phone text-primary fs-2 mb-2"></i>
                    <h6 class="fw-semibold">Teléfono</h6>
                    <p class="text-muted mb-0">{{ $empresa->telefono }}</p>
                </div>
            </div>
            @endif
            @if($empresa->correo)
            <div class="col-sm-6 col-md-3">
                <div class="card card-info text-center p-4">
                    <i class="bx bx-envelope text-primary fs-2 mb-2"></i>
                    <h6 class="fw-semibold">Correo</h6>
                    <p class="text-muted mb-0">{{ $empresa->correo }}</p>
                </div>
            </div>
            @endif
            @if($empresa->sitio_web)
            <div class="col-sm-6 col-md-3">
                <div class="card card-info text-center p-4">
                    <i class="bx bx-globe text-primary fs-2 mb-2"></i>
                    <h6 class="fw-semibold">Sitio Web</h6>
                    <a href="{{ $empresa->sitio_web }}" target="_blank" class="text-primary">
                        {{ $empresa->sitio_web }}
                    </a>
                </div>
            </div>
            @endif
            @if($empresa->direccion)
            <div class="col-sm-6 col-md-3">
                <div class="card card-info text-center p-4">
                    <i class="bx bx-map text-primary fs-2 mb-2"></i>
                    <h6 class="fw-semibold">Dirección</h6>
                    <p class="text-muted mb-0">{{ $empresa->direccion }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Sin empresa registrada --}}
@if(!$empresa)
<section class="py-5 text-center">
    <div class="container">
        <i class="bx bx-info-circle text-muted" style="font-size:4rem;"></i>
        <h3 class="mt-3 text-muted">Aún no hay información de la empresa.</h3>
        <a href="{{ route('login') }}" class="btn btn-primary mt-3">Configurar desde el panel</a>
    </div>
</section>
@endif

{{-- ── Footer ── --}}
<footer class="py-4">
    <div class="container text-center">
        <p class="mb-1">
            &copy; {{ date('Y') }}
            <strong class="text-white">{{ $empresa->nombre ?? config('app.name') }}</strong>
            — Todos los derechos reservados.
        </p>
        @if($empresa && $empresa->correo)
            <small><a href="mailto:{{ $empresa->correo }}">{{ $empresa->correo }}</a></small>
        @endif
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>