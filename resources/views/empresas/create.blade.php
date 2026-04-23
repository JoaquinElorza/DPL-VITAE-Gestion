@section('title', 'Nueva Empresa')
<x-layouts.app :title="'Nueva Empresa'">
    <div class="card shadow-sm border-0" style="border-top: 4px solid #8b5cf6; border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-transparent pb-0 pt-4 px-md-5 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-buildings me-2 fs-4" style="vertical-align: middle;"></i>Registrar Nueva Empresa</h5>
            <p class="text-muted small mt-1 mb-0">Configura los datos generales, identidad y contacto de la nueva organización.</p>
            </div>
            <span class="text-danger small mt-2 mt-sm-0">* datos obligatorios</span>
        </div>
        <div class="card-body px-md-5 pb-5 pt-3">
            <form action="{{ route('empresas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Información General -->
                <h6 class="fw-semibold mt-4 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-info-circle me-1"></i> Información General
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Nombre de la Empresa <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-building"></i></span>
                            <input type="text" name="nombre" class="form-control border-start-0 ps-0 @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Nombre comercial" required>
                        </div>
                        @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Slogan</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-text"></i></span>
                            <input type="text" name="slogan" class="form-control border-start-0 ps-0 @error('slogan') is-invalid @enderror" value="{{ old('slogan') }}" placeholder="Frase distintiva">
                        </div>
                        @error('slogan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Logotipo <small class="text-muted fw-normal">(máx 2MB)</small></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-image"></i></span>
                            <input type="file" name="logo" class="form-control border-start-0 ps-0 @error('logo') is-invalid @enderror" accept="image/*">
                        </div>
                        @error('logo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Imagen Principal <small class="text-muted fw-normal">(máx 4MB)</small></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-image-alt"></i></span>
                            <input type="file" name="imagen" class="form-control border-start-0 ps-0 @error('imagen') is-invalid @enderror" accept="image/*">
                        </div>
                        @error('imagen')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Información de Contacto -->
                <h6 class="fw-semibold mt-4 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-phone-call me-1"></i> Información de Contacto
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Teléfono</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-phone"></i></span>
                            <input type="text" name="telefono" class="form-control border-start-0 ps-0 @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" placeholder="Número de contacto">
                        </div>
                        @error('telefono')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Correo Electrónico</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-envelope"></i></span>
                            <input type="email" name="correo" class="form-control border-start-0 ps-0 @error('correo') is-invalid @enderror" value="{{ old('correo') }}" placeholder="contacto@empresa.com">
                        </div>
                        @error('correo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Sitio Web</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-globe"></i></span>
                            <input type="text" name="sitio_web" class="form-control border-start-0 ps-0 @error('sitio_web') is-invalid @enderror" value="{{ old('sitio_web') }}" placeholder="https://www.empresa.com">
                        </div>
                        @error('sitio_web')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted fw-medium">Dirección Física</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-map"></i></span>
                            <input type="text" name="direccion" class="form-control border-start-0 ps-0 @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}" placeholder="Calle, Número, Colonia, Ciudad...">
                        </div>
                        @error('direccion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Identidad Corporativa -->
                <h6 class="fw-semibold mt-4 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-bulb me-1"></i> Identidad Corporativa
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Descripción</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0 align-items-start pt-2"><i class="bx bx-align-left"></i></span>
                            <textarea name="descripcion" rows="3" class="form-control border-start-0 ps-0 @error('descripcion') is-invalid @enderror" placeholder="Breve descripción de la empresa...">{{ old('descripcion') }}</textarea>
                        </div>
                        @error('descripcion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Misión</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0 align-items-start pt-2"><i class="bx bx-target-lock"></i></span>
                            <textarea name="mision" rows="3" class="form-control border-start-0 ps-0 @error('mision') is-invalid @enderror" placeholder="Propósito de la empresa...">{{ old('mision') }}</textarea>
                        </div>
                        @error('mision')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Visión</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0 align-items-start pt-2"><i class="bx bx-show-alt"></i></span>
                            <textarea name="vision" rows="3" class="form-control border-start-0 ps-0 @error('vision') is-invalid @enderror" placeholder="Hacia dónde se dirige la empresa...">{{ old('vision') }}</textarea>
                        </div>
                        @error('vision')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Valores</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0 align-items-start pt-2"><i class="bx bx-diamond"></i></span>
                            <textarea name="valores" rows="3" class="form-control border-start-0 ps-0 @error('valores') is-invalid @enderror" placeholder="Principios éticos y profesionales...">{{ old('valores') }}</textarea>
                        </div>
                        @error('valores')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-5 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="transform: none;"><i class="bx bx-save me-2"></i>Guardar Empresa</button>
                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary ms-3"><i class="bx bx-x me-1"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
