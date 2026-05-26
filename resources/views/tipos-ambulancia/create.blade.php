<x-layouts.app :title="'Nuevo Tipo de Ambulancia'">
    <div class="card shadow-sm border-0" style="border-top: 4px solid #8b5cf6; border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-transparent pb-0 pt-4 px-md-5 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-first-aid me-2 fs-4" style="vertical-align: middle;"></i>Registrar Tipo de Ambulancia</h5>
            <p class="text-muted small mt-1 mb-0">Define una nueva categoría de unidad y su costo base.</p>
            </div>
            <span class="text-danger small mt-2 mt-sm-0">* datos obligatorios</span>
        </div>
        <div class="card-body px-md-5 pb-5 pt-3">
            <form action="{{ route('tipos-ambulancia.store') }}" method="POST">
                @csrf
                
                <h6 class="fw-semibold mt-4 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-info-circle me-1"></i> Detalles de la Categoría
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Nombre <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-purchase-tag"></i></span>
                            <input type="text" name="nombre_tipo" class="form-control border-start-0 ps-0 @error('nombre_tipo') is-invalid @enderror" value="{{ old('nombre_tipo') }}" placeholder="Ej. Terapia Intensiva" required>
                        </div>
                        @error('nombre_tipo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Costo base del servicio <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-dollar"></i></span>
                            <input type="number" name="costo_base" step="0.01" min="0" class="form-control border-start-0 ps-0 @error('costo_base') is-invalid @enderror" value="{{ old('costo_base', 0) }}" required>
                            <span class="input-group-text bg-transparent border-start-0">MXN</span>
                        </div>
                        <small class="text-muted d-block mt-1">Costo fijo por usar este tipo de ambulancia en un servicio.</small>
                        @error('costo_base')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mt-4">
                        <label class="form-label text-muted fw-medium">Descripción</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0 align-items-start pt-2"><i class="bx bx-align-left"></i></span>
                            <textarea name="descripcion" class="form-control border-start-0 ps-0 @error('descripcion') is-invalid @enderror" rows="3" placeholder="Detalles sobre el equipamiento...">{{ old('descripcion') }}</textarea>
                        </div>
                        @error('descripcion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-5 d-flex align-items-center">
                    <button type="submit" class="btn btn-info px-4 shadow-sm" style="transform: none;"><i class="bx bx-save me-2"></i>Guardar Tipo de Ambulancia</button>
                    <a href="{{ route('tipos-ambulancia.index') }}" class="btn btn-danger ms-3"><i class="bx bx-x me-1"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
