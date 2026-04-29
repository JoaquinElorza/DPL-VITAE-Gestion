<x-layouts.app title="Editar Operador">

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('operadores.index') }}" class="btn btn-icon btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-bold">Editar Operador</h4>
                <small class="text-muted">
                    {{ $operador->usuario?->nombre ?? 'Sin nombre' }} {{ $operador->usuario?->ap_paterno ?? '' }}
                </small>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="/operadores/{{ $operador->id_usuario }}" method="POST">
            @csrf @method('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-user me-1 text-primary"></i>Datos personales</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre', $operador->usuario?->nombre) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                            <input type="text" name="ap_paterno"
                                class="form-control @error('ap_paterno') is-invalid @enderror"
                                value="{{ old('ap_paterno', $operador->usuario?->ap_paterno) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" name="ap_materno"
                                class="form-control @error('ap_materno') is-invalid @enderror"
                                value="{{ old('ap_materno', $operador->usuario?->ap_materno) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $operador->usuario?->telefono) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-lock me-1 text-warning"></i>Acceso al sistema</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $operador->usuario?->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nueva contraseña</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Dejar vacío para no cambiar">
                                <span class="input-group-text cursor-pointer" onclick="togglePass(this)">
                                    <i class="bx bx-hide"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar contraseña</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password_confirmation"
                                    class="form-control" placeholder="Repite la nueva contraseña">
                                <span class="input-group-text cursor-pointer" onclick="togglePass(this)">
                                    <i class="bx bx-hide"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-certification me-1 text-info"></i>Documentación</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Número de Licencia <span class="text-danger">*</span></label>
                            <input type="text" name="numero_licencia"
                                class="form-control @error('numero_licencia') is-invalid @enderror"
                                value="{{ old('numero_licencia', $operador->numero_licencia) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Vencimiento <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_licencia"
                                class="form-control @error('fecha_licencia') is-invalid @enderror"
                                value="{{ old('fecha_licencia', $operador->fecha_licencia) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-briefcase me-1 text-success"></i>Datos laborales</h6>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <label class="form-label">Salario Mensual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="7468" name="salario"
                                class="form-control @error('salario') is-invalid @enderror"
                                value="{{ old('salario', ($operador->salario_hora * 160)) }}" required>
                            <span class="input-group-text">MXN</span>
                        </div>
                        <small class="text-muted mt-1 d-block">Mínimo legal LFT: $7,468.00</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Guardar cambios
                </button>
                <a href="{{ route('operadores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>

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