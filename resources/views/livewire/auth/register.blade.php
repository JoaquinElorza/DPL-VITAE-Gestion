<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $nombre = '';
    public string $ap_paterno = '';
    public string $ap_materno = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $terms = false;

    public function register(): void
    {
        $validated = $this->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'ap_paterno' => ['required', 'string', 'max:100'],
            'ap_materno' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'Debes aceptar los términos y el aviso de privacidad para continuar.'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        // Todo usuario registrado es un cliente
        \App\Models\Cliente::create(['id_usuario' => $user->id_usuario]);

        Auth::login($user);

        $this->redirectIntended(route('cotizaciones.mis-solicitudes', absolute: false), navigate: true);
    }
}; ?>

@section('title', 'Registro de Cuenta')

@section('page-style')
@vite([
    'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

<div>
    @php
        $empresa = \App\Models\Empresa::first();
        $nombreEmpresa = $empresa ? $empresa->nombre : 'Vitae Ambulancias';
    @endphp
    
    <div class="mb-4">
        <h4 class="mb-2 fw-bold" style="color: #1A2B4C;">Registro de Cliente</h4>
        <p class="mb-4 text-muted">Crea tu cuenta en <strong>{{ $nombreEmpresa }}</strong> para solicitar y gestionar servicios médicos de forma segura.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-info mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="register" class="mb-5">
        
        <div class="row g-3 mb-4">
            <div class="col-12">
                <label for="nombre" class="form-label" style="color: #1A2B4C; font-weight: 500;">Nombre(s)</label>
                <input
                    wire:model="nombre"
                    type="text"
                    class="form-control @error('nombre') is-invalid @enderror"
                    id="nombre"
                    required
                    autofocus
                    autocomplete="given-name"
                    placeholder="Ej. Juan Carlos"
                >
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="ap_paterno" class="form-label" style="color: #1A2B4C; font-weight: 500;">Primer Apellido</label>
                <input
                    wire:model="ap_paterno"
                    type="text"
                    class="form-control @error('ap_paterno') is-invalid @enderror"
                    id="ap_paterno"
                    required
                    autocomplete="family-name"
                    placeholder="Ej. Pérez"
                >
                @error('ap_paterno')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="ap_materno" class="form-label" style="color: #1A2B4C; font-weight: 500;">Segundo Apellido</label>
                <input
                    wire:model="ap_materno"
                    type="text"
                    class="form-control @error('ap_materno') is-invalid @enderror"
                    id="ap_materno"
                    autocomplete="additional-name"
                    placeholder="(Opcional)"
                >
                @error('ap_materno')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="email" class="form-label" style="color: #1A2B4C; font-weight: 500;">Correo Electrónico</label>
            <input
                wire:model="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                required
                autocomplete="email"
                placeholder="correo@ejemplo.com"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 form-password-toggle" x-data="{ show: false }">
            <label class="form-label" for="password" style="color: #1A2B4C; font-weight: 500;">Contraseña</label>
            <div class="input-group input-group-merge">
                <input
                    wire:model="password"
                    x-bind:type="show ? 'text' : 'password'"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    required
                    autocomplete="new-password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                >
                <span class="input-group-text cursor-pointer" @click="show = !show">
                    <i class="bx" :class="show ? 'bx-show' : 'bx-hide'"></i>
                </span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4 form-password-toggle" x-data="{ show: false }">
            <label class="form-label" for="password_confirmation" style="color: #1A2B4C; font-weight: 500;">Confirmar Contraseña</label>
            <div class="input-group input-group-merge">
                <input
                    wire:model="password_confirmation"
                    x-bind:type="show ? 'text' : 'password'"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                >
                <span class="input-group-text cursor-pointer" @click="show = !show">
                    <i class="bx" :class="show ? 'bx-show' : 'bx-hide'"></i>
                </span>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <div class="form-check mb-0 ms-2">
                <input wire:model="terms" type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms">
                <label class="form-check-label" for="terms">
                    Acepto el <a href="javascript:void(0);" style="color: #6d28d9; font-weight: 500;">aviso de privacidad y términos de servicio</a>
                </label>
                @error('terms')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn d-grid w-100 mb-4" style="background-color: #6d28d9; color: white; font-weight: 600; border-radius: 8px; padding: 10px; border: none; box-shadow: 0 4px 14px 0 rgba(109, 40, 217, 0.39);">
            Registrarse
        </button>
    </form>

    <p class="text-center">
        <span class="text-muted">¿Ya tienes una cuenta registrada?</span>
        <a href="{{ route('login') }}" wire:navigate style="color: #6d28d9; font-weight: 600; text-decoration: none;">
            <span>Inicia sesión aquí</span>
        </a>
    </p>
</div>