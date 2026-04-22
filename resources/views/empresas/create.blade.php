@section('title', 'Nueva Empresa')
<x-layouts.app :title="'Nueva Empresa'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Nueva Empresa</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('empresas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slogan</label>
                        <input type="text" name="slogan" class="form-control @error('slogan') is-invalid @enderror" value="{{ old('slogan') }}">
                        @error('slogan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}">
                        @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sitio Web</label>
                        <input type="text" name="sitio_web" class="form-control @error('sitio_web') is-invalid @enderror" value="{{ old('sitio_web') }}">
                        @error('sitio_web')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Misión</label>
                        <textarea name="mision" rows="3" class="form-control @error('mision') is-invalid @enderror">{{ old('mision') }}</textarea>
                        @error('mision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Visión</label>
                        <textarea name="vision" rows="3" class="form-control @error('vision') is-invalid @enderror">{{ old('vision') }}</textarea>
                        @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Valores</label>
                        <textarea name="valores" rows="3" class="form-control @error('valores') is-invalid @enderror">{{ old('valores') }}</textarea>
                        @error('valores')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Logo <small class="text-muted">(máx 2MB)</small></label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Imagen Principal <small class="text-muted">(máx 4MB)</small></label>
                        <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*">
                        @error('imagen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
