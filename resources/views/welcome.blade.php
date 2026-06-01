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
            max-width: 500px;
            height: auto;
            object-fit: contain;
            mix-blend-mode: multiply; /* Magia para hacer que el JPG blanco se fusione */
        }

        .hero-premium .btn-danger {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border: none;
            box-shadow: 0 10px 30px rgba(220,38,38,.25);
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
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
        }

        .card-info h4, .card-info h6 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .card-info p {
            color: #64748b;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .section-title { font-size: 2.4rem; font-weight: 800; letter-spacing: -0.03em; }
        .section-title-left { font-size: 2rem; font-weight: 800; letter-spacing: -0.03em; }
        .btn { font-weight: 600; letter-spacing: -0.01em; }

        section { padding-top: 0px; padding-bottom: 120px; }
        @media (min-width: 1400px) { .container { max-width: 1240px; } }
        section + section { margin-top: 40px; }
        section { border-top: 1px solid rgba(15, 23, 42, 0.06); }
        .section-title, .section-title-left { margin-bottom: 48px; }
        #contacto .row { row-gap: 24px; }
        p { font-size: 1rem; line-height: 1.75; color: #475569; }
        body { font-family: 'Inter', sans-serif; color: #0f172a; font-weight: 400; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; color: #0f172a; letter-spacing: -0.03em; }

    </style>
</head>

<body>

    {{-- ── Navbar Corregido ── --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('assets/img/icono-login.jpg') }}" alt="Logo" style="height: 45px; mix-blend-mode: multiply;">
                <span class="fw-bold" style="color: #6d28d9;">{{ $empresa->nombre ?? config('app.name') }}</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="nav-link text-muted fw-semibold">
                                <i class="bx bx-list-ul me-1"></i> Mis solicitudes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-dark rounded-pill px-4">
                                Cotizar Servicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="btn btn-danger rounded-pill px-4" style="background: #6d28d9; border-color: #6d28d9;">
                                Mi Panel
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-dark rounded-pill px-4">
                                Cotizar Servicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-danger rounded-pill px-4" style="background: #6d28d9; border-color: #6d28d9;">
                                Iniciar Sesión
                            </a>
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
                    <span class="hero-badge">Atención médica profesional</span>
                    <h1 class="hero-title mt-4">Atención prehospitalaria rápida, segura y profesional.</h1>
                    <p class="hero-description mt-4">En DPL VITAE brindamos servicios médicos y traslados especializados con personal capacitado y unidades equipadas para cada situación.</p>

                    <div class="d-flex flex-wrap gap-3 mt-5">
                        <a href="{{ route('cotizaciones.create') }}" class="btn btn-danger btn-lg px-4 py-3 rounded-pill">Cotizar Servicio</a>
                        <a href="#nosotros" class="btn btn-outline-dark btn-lg px-4 py-3 rounded-pill">Conocer más</a>
                    </div>

                    {{-- Métricas --}}
                    <div class="row mt-5 g-5">
                        <div class="col-4">
                            <h3 class="fw-bold text-danger mb-1">24/7</h3>
                            <small class="text-muted">Atención continua</small>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold text-danger mb-1">+100</h3>
                            <small class="text-muted">Servicios realizados</small>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold text-danger mb-1">100%</h3>
                            <small class="text-muted">Personal capacitado</small>
                        </div>
                    </div>
                </div>

                {{-- Imagen Inyectada Perfectamente --}}
                <div class="col-lg-6 text-center mt-5 mt-lg-0 d-flex justify-content-center">
                    <img src="{{ asset('assets/img/icono-login.jpg') }}" alt="Logo DPL Vitae" class="hero-image">
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
                        <blockquote class="blockquote border-start border-3 ps-3 mt-4" style="border-color: #6d28d9 !important;">
                            <p class="fst-italic fs-5" style="color: #6d28d9;">"{{ $empresa->slogan }}"</p>
                        </blockquote>
                    @endif
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('assets/img/icono-login.jpg') }}" alt="{{ $empresa->nombre }}" class="img-fluid" style="max-width: 400px; mix-blend-mode: multiply;">
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
            <div class="row g-4">
                @if($empresa->mision)
                    <div class="col-12 col-md-6">
                        <div class="card card-info h-100 p-4 border-0 shadow-sm">
                            <h4 class="mb-3">Misión</h4>
                            <p class="text-muted mb-0">{{ $empresa->mision }}</p>
                        </div>
                    </div>
                @endif
                @if($empresa->vision)
                    <div class="col-12 col-md-6">
                        <div class="card card-info h-100 p-4 border-0 shadow-sm">
                            <h4 class="mb-3">Visión</h4>
                            <p class="text-muted mb-0">{{ $empresa->vision }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>