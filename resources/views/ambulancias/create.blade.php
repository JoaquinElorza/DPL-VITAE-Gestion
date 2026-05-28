<x-layouts.app :title="'Nueva Ambulancia'">
    <div class="card shadow-sm border-0" style="border-top: 4px solid #8b5cf6; border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-transparent pb-0 pt-4 px-md-5 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-ambulance me-2 fs-4" style="vertical-align: middle;"></i>Registrar Nueva Ambulancia</h5>
            <p class="text-muted small mt-1 mb-0">Ingresa la información de la unidad para incorporarla a la flotilla.</p>
            </div>
            <span class="text-danger small mt-2 mt-sm-0">* datos obligatorios</span>
        </div>
        <div class="card-body px-md-5 pb-5 pt-3">
            <form action="{{ route('ambulancias.store') }}" method="POST">
                @csrf
                
                <h6 class="fw-semibold mt-4 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-info-circle me-1"></i> Detalles de la Unidad
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Placa <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-car"></i></span>
                            <input type="text" name="placa" class="form-control border-start-0 ps-0 @error('placa') is-invalid @enderror" value="{{ old('placa') }}" placeholder="Ej. YX-123-AB" required>
                        </div>
                        @error('placa')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Estado <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-traffic-cone"></i></span>
                            <select name="estado" class="form-select border-start-0 ps-0 @error('estado') is-invalid @enderror" required>
                                <option value="">-- Seleccionar --</option>
                                @foreach(['Disponible','En servicio','En mantenimiento'] as $est)
                                    <option value="{{ $est }}" {{ old('estado') === $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Tipo de Ambulancia <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-first-aid"></i></span>
                            <select name="id_tipo_ambulancia" class="form-select border-start-0 ps-0 @error('id_tipo_ambulancia') is-invalid @enderror" required>
                                <option value="">-- Seleccionar --</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id_tipo_ambulancia }}" {{ old('id_tipo_ambulancia') == $tipo->id_tipo_ambulancia ? 'selected' : '' }}>
                                        {{ $tipo->nombre_tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_tipo_ambulancia')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="mt-5 d-flex align-items-center">
                    <button type="submit" class="btn btn-info px-4 shadow-sm" style="transform: none;"><i class="bx bx-save me-2"></i>Guardar Ambulancia</button>
                    <a href="{{ route('ambulancias.index') }}" class="btn btn-danger ms-3"><i class="bx bx-x me-1"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
