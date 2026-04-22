@section('title', 'Editar Empresa')
<x-layouts.app :title="'Editar Empresa'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Empresa</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('empresas.update', $empresa) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $empresa->nombre) }}" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slogan</label>
                        <input type="text" name="slogan" class="form-control @error('slogan') is-invalid @enderror" value="{{ old('slogan', $empresa->slogan) }}">
                        @error('slogan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $empresa->telefono) }}">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo', $empresa->correo) }}">
                        @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sitio Web</label>
                        <input type="text" name="sitio_web" class="form-control @error('sitio_web') is-invalid @enderror" value="{{ old('sitio_web', $empresa->sitio_web) }}">
                        @error('sitio_web')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $empresa->direccion) }}">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Costo por kilómetro <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="costo_km" step="0.01" min="0"
                                class="form-control @error('costo_km') is-invalid @enderror"
                                value="{{ old('costo_km', $empresa->costo_km ?? 25.00) }}" required>
                            <span class="input-group-text">MXN/km</span>
                        </div>
                        <small class="text-muted">Tarifa base que se aplica al calcular cotizaciones por distancia.</small>
                        @error('costo_km')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $empresa->descripcion) }}</textarea>
                        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Misión</label>
                        <textarea name="mision" rows="3" class="form-control @error('mision') is-invalid @enderror">{{ old('mision', $empresa->mision) }}</textarea>
                        @error('mision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Visión</label>
                        <textarea name="vision" rows="3" class="form-control @error('vision') is-invalid @enderror">{{ old('vision', $empresa->vision) }}</textarea>
                        @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Valores</label>
                        <textarea name="valores" rows="3" class="form-control @error('valores') is-invalid @enderror">{{ old('valores', $empresa->valores) }}</textarea>
                        @error('valores')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Logo</label>
                        @if($empresa->logo_nombre)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $empresa->logo_nombre) }}" alt="Logo actual" style="height:60px;object-fit:contain;">
                                <small class="text-muted d-block">Logo actual</small>
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Dejar vacío para conservar el actual</small>
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Imagen Principal</label>
                        @if($empresa->imagen_nombre)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $empresa->imagen_nombre) }}" alt="Imagen actual" style="height:60px;object-fit:contain;">
                                <small class="text-muted d-block">Imagen actual</small>
                            </div>
                        @endif
                        <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Dejar vacío para conservar la actual</small>
                        @error('imagen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
