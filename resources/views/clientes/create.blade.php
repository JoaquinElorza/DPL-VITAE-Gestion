<x-layouts.app :title="'Nuevo Cliente'">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #8b5cf6; border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-transparent pb-0 pt-4 px-md-5 d-flex align-items-center flex-wrap gap-3">
                    <a href="{{ route('clientes.index') }}" class="btn btn-icon btn-outline-secondary btn-sm" style="border-radius: 50%;">
                        <i class="bx bx-arrow-back"></i>
                    </a>
                    <div>
                        <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-user-plus me-2 fs-4" style="vertical-align: middle;"></i>Registrar Nuevo Cliente</h5>
                        <p class="text-muted small mt-1 mb-0">Se creará una cuenta de acceso para el cliente.</p>
                    </div>
                    <span class="text-danger small ms-auto mt-2 mt-md-0">* datos obligatorios</span>
                </div>

                <div class="card-body px-md-5 pb-5 pt-4">
                    <form action="{{ route('clientes.store') }}" method="POST">
                        @csrf
                        
                        <!-- Datos Personales -->
                        <h6 class="fw-semibold mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                            <i class="bx bx-id-card me-1"></i> Datos Personales
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Nombre <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge shadow-none">
                                    <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-user"></i></span>
                                    <input type="text" name="nombre" class="form-control border-start-0 ps-0 @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Nombre(s)" required>
                                </div>
                                @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Apellido Paterno <span class="text-danger">*</span></label>
                                <input type="text" name="ap_paterno" class="form-control @error('ap_paterno') is-invalid @enderror" value="{{ old('ap_paterno') }}" placeholder="Primer apellido" required>
                                @error('ap_paterno')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Apellido Materno</label>
                                <input type="text" name="ap_materno" class="form-control @error('ap_materno') is-invalid @enderror" value="{{ old('ap_materno') }}" placeholder="Segundo apellido">
                                @error('ap_materno')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Teléfono</label>
                                <div class="input-group input-group-merge shadow-none">
                                    <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-phone"></i></span>
                                    <input type="text" name="telefono" class="form-control border-start-0 ps-0 @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" placeholder="Ej. 9511234567">
                                </div>
                                @error('telefono')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Acceso al sistema -->
                        <h6 class="fw-semibold mb-3 pb-2 mt-4" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                            <i class="bx bx-lock-alt me-1"></i> Acceso al Sistema
                        </h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted fw-medium">Correo Electrónico <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge shadow-none">
                                    <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                                </div>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge shadow-none">
                                    <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-key"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" placeholder="Mínimo 8 caracteres" required>
                                    <span class="input-group-text bg-transparent cursor-pointer" onclick="togglePass(this)"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge shadow-none">
                                    <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-check-shield"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0 border-end-0 ps-0" placeholder="Repite la contraseña" required>
                                    <span class="input-group-text bg-transparent cursor-pointer" onclick="togglePass(this)"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex align-items-center">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" style="transform: none;"><i class="bx bx-save me-2"></i>Crear Cliente</button>
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary ms-3"><i class="bx bx-x me-1"></i>Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function togglePass(btn) {
        var input = btn.closest('.input-group').querySelector('input');
        var icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bx-hide', 'bx-show');
        } else {
            input.type = 'password';
            icon.classList.replace('bx-show', 'bx-hide');
        }
    }
    </script>
</x-layouts.app>
