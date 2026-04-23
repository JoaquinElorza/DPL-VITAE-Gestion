<x-layouts.app :title="'Nuevo Padecimiento'">
    <div class="card shadow-sm border-0" style="border-top: 4px solid #8b5cf6; border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-transparent pb-0 pt-4 px-md-5 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-plus-medical me-2 fs-4" style="vertical-align: middle;"></i>Registrar Nuevo Padecimiento</h5>
            <p class="text-muted small mt-1 mb-0">Completa los detalles de riesgo y costo asociado al padecimiento.</p>
            </div>
            <span class="text-danger small mt-2 mt-sm-0">* datos obligatorios</span>
        </div>
        <div class="card-body px-md-5 pb-5 pt-3">
            <form action="{{ route('padecimientos.store') }}" method="POST">
                @csrf
                
                <h6 class="fw-semibold mt-4 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-info-circle me-1"></i> Detalles del Padecimiento
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Nombre del Padecimiento <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-pulse"></i></span>
                            <input type="text" name="nombre_padecimiento" class="form-control border-start-0 ps-0 @error('nombre_padecimiento') is-invalid @enderror" value="{{ old('nombre_padecimiento') }}" placeholder="Ej. Diabetes, Hipertensión..." required>
                        </div>
                        @error('nombre_padecimiento')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted fw-medium">Nivel de Riesgo <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-error"></i></span>
                            <input type="text" name="nivel_riesgo" class="form-control border-start-0 ps-0 @error('nivel_riesgo') is-invalid @enderror" value="{{ old('nivel_riesgo') }}" placeholder="Ej. Alto, Medio..." required>
                        </div>
                        @error('nivel_riesgo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted fw-medium">Costo Extra <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-dollar"></i></span>
                            <input type="number" step="0.01" name="costo_extra" class="form-control border-start-0 ps-0 @error('costo_extra') is-invalid @enderror" value="{{ old('costo_extra') }}" placeholder="Ej. 150.00" required>
                        </div>
                        @error('costo_extra')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-5 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="transform: none;"><i class="bx bx-save me-2"></i>Guardar Padecimiento</button>
                    <a href="{{ route('padecimientos.index') }}" class="btn btn-secondary ms-3"><i class="bx bx-x me-1"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
