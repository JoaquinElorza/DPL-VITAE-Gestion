<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

@props([
    'pageTitle',
])

<title>@yield('title') — {{ config('app.name', 'Vitae Ambulancias') }}</title>

<meta name="description" content="Plataforma de gestión y solicitud de servicios médicos y ambulancias." />
<meta name="keywords" content="ambulancias, servicios médicos, traslados, urgencias, clínica, salud">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta property="og:title" content="@yield('title') — {{ config('app.name', 'Vitae Ambulancias') }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ asset('assets/img/favicon/favicon.ico') }}" />
<meta property="og:description" content="Plataforma de gestión y solicitud de servicios médicos y ambulancias." />
<meta property="og:site_name" content="{{ config('app.name', 'Vitae Ambulancias') }}" />
<link rel="canonical" href="{{ url()->current() }}">
<link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

@include('partials.styles')

@livewireStyles