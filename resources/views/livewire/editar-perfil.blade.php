<div>
    {{-- modal editar perfil --}}
    <div class="modal fade" id="modal-editar-perfil" tabindex="-1" aria-labelledby="modalEditarPerfilLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="modalEditarPerfilLabel">
                        <i class="bx bx-user-circle me-2 text-primary"></i>Editar Perfil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">

                    {{-- pestañas --}}
                    <ul class="nav nav-tabs mb-4" id="tabPerfil" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-datos-btn" data-bs-toggle="tab"
                                data-bs-target="#tab-datos" type="button" role="tab">
                                <i class="bx bx-id-card me-1"></i> Datos personales
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-password-btn" data-bs-toggle="tab"
                                data-bs-target="#tab-password" type="button" role="tab">
                                <i class="bx bx-lock-alt me-1"></i> Contraseña
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- tab: datos personales --}}
                        <div class="tab-pane fade show active" id="tab-datos" role="tabpanel">
                            <form wire:submit.prevent="guardarPerfil">

                                <div class="mb-3">
                                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                        placeholder="Nombre">
                                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="ap_paterno" class="form-control @error('ap_paterno') is-invalid @enderror"
                                        placeholder="Apellido Paterno">
                                    @error('ap_paterno') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Apellido Materno</label>
                                    <input type="text" wire:model="ap_materno" class="form-control @error('ap_materno') is-invalid @enderror"
                                        placeholder="Apellido Materno (opcional)">
                                    @error('ap_materno') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                                    <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="correo@ejemplo.com">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" wire:model="telefono" class="form-control @error('telefono') is-invalid @enderror"
                                        placeholder="Teléfono (opcional)">
                                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i> Guardar cambios
                                    </button>
                                    <span class="text-success small" x-data="{ show: false }"
                                        x-on:perfil-guardado.window="show = true; setTimeout(() => show = false, 3000)"
                                        x-show="show" x-transition>
                                        <i class="bx bx-check-circle me-1"></i>¡Guardado!
                                    </span>
                                </div>

                            </form>
                        </div>

                        {{-- tab: contraseña --}}
                        <div class="tab-pane fade" id="tab-password" role="tabpanel">
                            <form wire:submit.prevent="cambiarContrasena">

                                <div class="mb-3">
                                    <label class="form-label">Contraseña actual <span class="text-danger">*</span></label>
                                    <input type="password" wire:model="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        placeholder="Contraseña actual">
                                    @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nueva contraseña <span class="text-danger">*</span></label>
                                    <input type="password" wire:model="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Mínimo 8 caracteres">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                                    <input type="password" wire:model="password_confirmation"
                                        class="form-control"
                                        placeholder="Repite la nueva contraseña">
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-lock me-1"></i> Cambiar contraseña
                                    </button>
                                    <span class="text-success small" x-data="{ show: false }"
                                        x-on:contrasena-cambiada.window="show = true; setTimeout(() => show = false, 3000)"
                                        x-show="show" x-transition>
                                        <i class="bx bx-check-circle me-1"></i>¡Contraseña actualizada!
                                    </span>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
