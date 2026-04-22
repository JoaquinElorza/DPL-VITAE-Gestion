<x-layouts.app :title="'Nuevo Paciente'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Nuevo Paciente</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pacientes.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ap. Paterno <span class="text-danger">*</span></label>
                        <input type="text" name="ap_paterno" class="form-control @error('ap_paterno') is-invalid @enderror" value="{{ old('ap_paterno') }}" required>
                        @error('ap_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ap. Materno</label>
                        <input type="text" name="ap_materno" class="form-control @error('ap_materno') is-invalid @enderror" value="{{ old('ap_materno') }}">
                        @error('ap_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sexo</label>
                        <select name="sexo" class="form-select @error('sexo') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @error('sexo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}">
                        @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" step="0.01" name="peso" class="form-control @error('peso') is-invalid @enderror" value="{{ old('peso') }}">
                        @error('peso')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Oxígeno (%)</label>
                        <input type="number" step="0.01" name="oxigeno" class="form-control @error('oxigeno') is-invalid @enderror" value="{{ old('oxigeno') }}">
                        @error('oxigeno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Servicio <span class="text-danger">*</span></label>
                        <select name="id_servicio" class="form-select @error('id_servicio') is-invalid @enderror" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->id_servicio }}" {{ old('id_servicio') == $servicio->id_servicio ? 'selected' : '' }}>
                                    #{{ $servicio->id_servicio }} - {{ $servicio->tipo ?? $servicio->estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_servicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <select name="id_direccion" class="form-select @error('id_direccion') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($direcciones as $direccion)
                                <option value="{{ $direccion->id_direccion }}" {{ old('id_direccion') == $direccion->id_direccion ? 'selected' : '' }}>
                                    {{ $direccion->nombre_calle }} {{ $direccion->n_exterior }} - {{ $direccion->colonia->nombre_colonia ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
