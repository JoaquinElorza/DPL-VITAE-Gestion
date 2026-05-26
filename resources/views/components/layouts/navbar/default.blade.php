@php
    $user        = auth()->user()?->loadMissing(['operador', 'paramedico', 'cliente']);
    $esEmpleado  = $user && $user->esEmpleado();
    $iniciales   = $user ? strtoupper(mb_substr($user->nombre, 0, 1)) . strtoupper(mb_substr($user->ap_paterno, 0, 1)) : '';
    // Definimos un fallback vacío por si el controlador no envía la variable
    $ambulancias = $ambulancias ?? (($user && $user->operador) ? $user->operador->ambulancias : collect());
@endphp

<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">

  <div class="navbar-nav-right d-flex align-items-center w-100 justify-content-between" id="navbar-collapse">

    <button id="navbar-hamburger-btn" class="d-xl-none btn btn-sm p-1 me-2 navbar-hamburger-btn" aria-label="Abrir menú">
      <span class="hamburger-icon">
        <span></span>
        <span></span>
        <span></span>
      </span>
    </button>

    @if($esEmpleado)
    <div class="d-flex align-items-center gap-2 ms-2">
      <span class="fw-bold fs-6 d-none d-sm-inline-block" style="color: #393395;">
        {{ $user->nombre }} {{ $user->ap_paterno }}
      </span>
      <span class="badge rounded-pill px-3 py-1" style="background-color: rgba(138, 43, 226, 0.1); color: #8A2BE2; border: 1px solid rgba(138, 43, 226, 0.2);">
        @if($user->operador) 
            <i class="bx bx-headphone me-1"></i>Operador 
        @else 
            <i class="bx bx-plus-medical me-1"></i>Paramédico 
        @endif
      </span>
    </div>
    @else
    <div class="navbar-nav align-items-center me-auto">
      <div class="nav-item d-flex align-items-center">
        <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
        <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none" placeholder="Search..." aria-label="Search..." />
      </div>
    </div>
    @endif

    <ul class="navbar-nav flex-row align-items-center ms-auto gap-1">

      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        @if(Auth::check())
          <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="perfil-initials" style="width:40px;height:40px;font-size:.9rem;">{{ $iniciales }}</div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <button class="navbar-perfil-btn px-3 py-2" type="button" data-bs-toggle="modal" data-bs-target="#modal-editar-perfil" data-bs-dismiss="dropdown">
                <div class="d-flex align-items-center gap-3">
                  <div class="perfil-initials">{{ $iniciales }}</div>
                  <div class="flex-grow-1 min-width-0">
                    <div class="fw-bold text-truncate" style="font-size:.93rem;">
                      {{ $user->nombre }} {{ $user->ap_paterno }} {{ $user->ap_materno }}
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                      @if($esEmpleado)
                        @if($user->operador)
                          <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(138, 43, 226, 0.1); color: #8A2BE2; border: 1px solid rgba(138, 43, 226, 0.2); font-size: 0.75rem;">
                              <i class="bx bx-headphone me-1"></i>Operador
                          </span>
                          {{-- VALIDACIÓN ANTI-ERROR --}}
                          @if(isset($ambulancias) && count($ambulancias) > 0)
                            @foreach($ambulancias as $amb)
                              <span class="badge bg-label-secondary" style="font-size:.7rem;">
                                <i class="bx bx-ambulance me-1"></i>{{ $amb->placa }}
                              </span>
                            @endforeach
                          @endif
                        @else
                          <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(138, 43, 226, 0.1); color: #8A2BE2; border: 1px solid rgba(138, 43, 226, 0.2); font-size: 0.75rem;">
                              <i class="bx bx-plus-medical me-1"></i>Paramédico
                          </span>
                        @endif
                      @elseif($user->esCliente())
                        <span class="role-chip bg-label-warning"><i class="bx bx-user"></i>Cliente</span>
                      @else
                        <span class="role-chip bg-label-danger"><i class="bx bx-shield"></i>Admin</span>
                      @endif
                      <span class="text-muted" style="font-size:.75rem;">
                        <i class="bx bx-envelope me-1"></i>{{ $user->email }}
                      </span>
                    </div>
                  </div>
                  <div class="flex-shrink-0 text-muted">
                    <i class="bx bx-edit-alt fs-5"></i>
                  </div>
                </div>
              </button>
            </li>
            <li><div class="dropdown-divider my-1"></div></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item dropdown-item-logout" type="submit">
                  <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Cerrar sesión</span>
                </button>
              </form>
            </li>
          </ul>
        @endif
      </li>

    </ul>
  </div>
</nav>