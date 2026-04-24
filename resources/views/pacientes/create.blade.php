<x-layouts.app :title="'Nuevo Paciente'">
    <div class="card shadow-sm border-0" style="border-top: 4px solid #8b5cf6; border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-transparent pb-0 pt-4 px-md-5 d-flex align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-user-plus me-2 fs-4" style="vertical-align: middle;"></i>Registrar Nuevo Paciente</h5>
                <p class="text-muted small mt-1 mb-0">Completa la información personal, médica y de servicio del paciente.</p>
            </div>
            <span class="text-danger small ms-auto">* datos obligatorios</span>
        </div>
        <div class="card-body px-md-5 pb-5 pt-3">
            <form action="{{ route('pacientes.store') }}" method="POST">
                @csrf
                
                <!-- Sección 1: Datos Personales -->
                <h6 class="fw-semibold mt-4 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-id-card me-1"></i> Datos Personales
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Nombre <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-user"></i></span>
                            <input type="text" name="nombre" class="form-control border-start-0 ps-0 @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Nombre(s)" required>
                        </div>
                        @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Apellido Paterno <span class="text-danger">*</span></label>
                        <input type="text" name="ap_paterno" class="form-control @error('ap_paterno') is-invalid @enderror" value="{{ old('ap_paterno') }}" placeholder="Primer apellido" required>
                        @error('ap_paterno')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-medium">Apellido Materno</label>
                        <input type="text" name="ap_materno" class="form-control @error('ap_materno') is-invalid @enderror" value="{{ old('ap_materno') }}" placeholder="Segundo apellido">
                        @error('ap_materno')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Sexo</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-male-sign"></i></span>
                            <select name="sexo" class="form-select border-start-0 ps-0 @error('sexo') is-invalid @enderror">
                                <option value="">-- Seleccionar --</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                        @error('sexo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Fecha de Nacimiento</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-calendar"></i></span>
                            <input type="date" name="fecha_nacimiento" class="form-control border-start-0 ps-0 @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}">
                        </div>
                        @error('fecha_nacimiento')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Sección 2: Datos Fisiológicos -->
                <h6 class="fw-semibold mt-5 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-health me-1"></i> Signos Vitales y Fisiología
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Peso del Paciente (kg)</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-dumbbell"></i></span>
                            <input type="number" step="0.01" name="peso" class="form-control border-start-0 ps-0 @error('peso') is-invalid @enderror" value="{{ old('peso') }}" placeholder="Ej. 75.5">
                            <span class="input-group-text bg-transparent border-start-0">kg</span>
                        </div>
                        @error('peso')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Nivel de Oxígeno (%)</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-wind"></i></span>
                            <input type="number" step="0.01" name="oxigeno" class="form-control border-start-0 ps-0 @error('oxigeno') is-invalid @enderror" value="{{ old('oxigeno') }}" placeholder="Ej. 98">
                            <span class="input-group-text bg-transparent border-start-0">%</span>
                        </div>
                        @error('oxigeno')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Sección 3: Asignación -->
                <h6 class="fw-semibold mt-5 mb-3 pb-2" style="color: #6366f1; border-bottom: 1px solid rgba(139, 92, 246, 0.15);">
                    <i class="bx bx-map-pin me-1"></i> Servicio y Ubicación
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
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-medium">Dirección del Paciente</label>
                        <div class="input-group input-group-merge shadow-none">
                            <span class="input-group-text bg-transparent text-primary border-end-0"><i class="bx bx-home-alt"></i></span>
                            <select name="id_direccion" class="form-select border-start-0 ps-0 @error('id_direccion') is-invalid @enderror">
                                <option value="">-- Seleccionar dirección --</option>
                                @foreach($direcciones as $direccion)
                                    <option value="{{ $direccion->id_direccion }}" {{ old('id_direccion') == $direccion->id_direccion ? 'selected' : '' }}>
                                        {{ $direccion->nombre_calle }} {{ $direccion->n_exterior }} - {{ $direccion->colonia->nombre_colonia ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_direccion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-5 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="transform: none;"><i class="bx bx-save me-2"></i>Guardar Paciente</button>
                    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary ms-3"><i class="bx bx-x me-1"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
