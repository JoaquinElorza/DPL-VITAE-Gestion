<x-layouts.app :title="'Nuevo Tipo de Ambulancia'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Nuevo Tipo de Ambulancia</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tipos-ambulancia.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_tipo" class="form-control @error('nombre_tipo') is-invalid @enderror" value="{{ old('nombre_tipo') }}" required>
                        @error('nombre_tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Costo base del servicio <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="costo_base" step="0.01" min="0"
                                class="form-control @error('costo_base') is-invalid @enderror"
                                value="{{ old('costo_base', 0) }}" required>
                            <span class="input-group-text">MXN</span>
                        </div>
                        <small class="text-muted">Costo fijo por usar este tipo de ambulancia en un servicio.</small>
                        @error('costo_base')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('tipos-ambulancia.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
