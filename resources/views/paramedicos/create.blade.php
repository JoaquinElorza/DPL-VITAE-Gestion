<x-layouts.app title="Nuevo Paramédico">

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-top: 4px solid #8b5cf6; border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-transparent pb-0 pt-4 px-md-5 d-flex align-items-center flex-wrap gap-3">
                <a href="{{ route('paramedicos.index') }}" class="btn btn-icon btn-outline-secondary btn-sm" style="border-radius: 50%;">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <div>
                    <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-plus-medical me-2 fs-4" style="vertical-align: middle;"></i>Registrar Nuevo Paramédico</h5>
                    <p class="text-muted small mt-1 mb-0">Se creará una cuenta de acceso para el paramédico.</p>
                </div>
                <span class="text-danger small ms-auto mt-2 mt-md-0">* datos obligatorios</span>
            </div>

            @if($errors->any())
            <div class="px-md-5 pt-3">
                <div class="alert alert-danger mb-0">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            </div>
            @endif

            <div class="card-body px-md-5 pb-5 pt-4">
                <form action="{{ route('paramedicos.store') }}" method="POST">
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
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-medium">Apellido Paterno <span class="text-danger">*</span></label>
                            <input type="text" name="ap_paterno" class="form-control @error('ap_paterno') is-invalid @enderror" value="{{ old('ap_paterno') }}" placeholder="Primer apellido" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-medium">Apellido Materno</label>
                            <input type="text" name="ap_materno" class="form-control @error('ap_materno') is-invalid @enderror" value="{{ old('ap_materno') }}" placeholder="Segundo apellido">
                        </div>
                    </div>

                    <!-- Acceso al sistema -->
                    <h6 class="fw-semibold mb-3 pb-2 mt-4" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                        <i class="bx bx-lock-alt me-1"></i> Acceso al Sistema
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label text-muted fw-medium">Correo Electrónico <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge shadow-none">
                                <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-envelope"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-medium">Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge shadow-none">
                                <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-key"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" placeholder="Mínimo 8 caracteres" required>
                                <span class="input-group-text bg-transparent cursor-pointer" onclick="togglePass(this)"><i class="bx bx-hide"></i></span>
                            </div>
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

                    <!-- Datos Laborales -->
                    <h6 class="fw-semibold mb-3 pb-2 mt-4" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                        <i class="bx bx-briefcase me-1"></i> Datos Laborales
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label text-muted fw-medium">Salario por Hora <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge shadow-none">
                                <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-dollar"></i></span>
                                <input type="number" step="0.01" min="0" name="salario_hora" class="form-control border-start-0 ps-0 @error('salario_hora') is-invalid @enderror" value="{{ old('salario_hora') }}" placeholder="0.00" required>
                                <span class="input-group-text bg-transparent border-start-0">MXN/hr</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="transform: none;"><i class="bx bx-save me-2"></i>Crear Paramédico</button>
                        <a href="{{ route('paramedicos.index') }}" class="btn btn-secondary ms-3"><i class="bx bx-x me-1"></i>Cancelar</a>
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
