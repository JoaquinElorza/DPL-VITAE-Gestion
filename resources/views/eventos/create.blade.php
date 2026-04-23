<x-layouts.app :title="'Nuevo Evento'">
    <div class="card shadow-sm border-0" style="border-top: 4px solid #8b5cf6; border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-transparent pb-0 pt-4 px-md-5 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-calendar-star me-2 fs-4" style="vertical-align: middle;"></i>Registrar Nuevo Evento</h5>
            <p class="text-muted small mt-1 mb-0">Completa los detalles de duración, asistencia y asigna el servicio correspondiente.</p>
            </div>
            <span class="text-danger small mt-2 mt-sm-0">* datos obligatorios</span>
        </div>
        <div class="card-body px-md-5 pb-5 pt-3">
            <form action="{{ route('eventos.store') }}" method="POST">
                @csrf
                
                <!-- Sección: Detalles del Evento -->
                <h6 class="fw-semibold mt-4 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-info-circle me-1"></i> Detalles del Evento
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Servicio Vinculado <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-ambulance"></i></span>
                            <select name="id_servicio" class="form-select border-start-0 ps-0 @error('id_servicio') is-invalid @enderror" required>
                                <option value="">-- Seleccionar servicio --</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id_servicio }}" {{ old('id_servicio') == $servicio->id_servicio ? 'selected' : '' }}>
                                        #{{ $servicio->id_servicio }} - {{ $servicio->tipo ?? $servicio->estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_servicio')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted fw-medium">Duración <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-time"></i></span>
                            <input type="number" step="0.01" name="duracion" class="form-control border-start-0 ps-0 @error('duracion') is-invalid @enderror" value="{{ old('duracion') }}" placeholder="Ej. 4.5" required>
                            <span class="input-group-text bg-transparent border-start-0">hrs</span>
                        </div>
                        @error('duracion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted fw-medium">Personas <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-group"></i></span>
                            <input type="number" name="personas" class="form-control border-start-0 ps-0 @error('personas') is-invalid @enderror" value="{{ old('personas') }}" placeholder="Ej. 150" required>
                        </div>
                        @error('personas')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-5 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="transform: none;"><i class="bx bx-save me-2"></i>Guardar Evento</button>
                    <a href="{{ route('eventos.index') }}" class="btn btn-secondary ms-3"><i class="bx bx-x me-1"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
