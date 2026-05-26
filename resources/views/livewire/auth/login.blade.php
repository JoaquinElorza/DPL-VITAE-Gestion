<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $user = Auth::user();
        $user->load(['operador', 'paramedico', 'cliente']);

        if ($user->esEmpleado()) {
            $this->redirectIntended(default: route('empleado.mi-panel', absolute: false), navigate: true);
        } elseif ($user->esCliente()) {
            $this->redirectIntended(default: route('cotizaciones.mis-solicitudes', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
};
?>
@section('title', 'Login Page')

@section('page-style')
@vite([
    'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

<div>
    @php
        $empresa = \App\Models\Empresa::first();
        $nombreEmpresa = $empresa ? $empresa->nombre : config('app.name');
    @endphp
    <x-auth-header :title="'Bienvenido a ' . $nombreEmpresa" :description="__('Ingresa tu correo y contraseña para iniciar sesión')" />

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-info mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="mb-6">
        <div class="mb-6">
            <label for="email" class="form-label">{{ __('Correo Electrónico o Nombre de Usuario') }}</label>
            <input
                wire:model="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                required
                autofocus
                autocomplete="email"
                placeholder="{{ __('Ingresa tu correo') }}"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-6 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate>
                        <span>{{ __('¿Olvidaste tu Contraseña?') }}</span>
                    </a>
                @endif
            </div>
            <div class="input-group input-group-merge password-input-group">
                <input
                    wire:model="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    required
                    autocomplete="current-password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                >
                <span class="input-group-text cursor-pointer toggle-password" id="togglePassword" data-bs-toggle="tooltip" data-bs-placement="top" title="Mostrar contraseña">
                    <i class="bx bx-show"></i>
                </span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-8">
            <div class="d-flex justify-content-between mt-8">
                <div class="form-check mb-0 ms-2">
                    <input wire:model="remember" type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">
                        {{ __('Recuérdame') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <button type="submit" class="btn btn-primary d-grid w-100">{{ __('Iniciar Sesión') }}</button>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="text-center">
            <span>{{ __('¿Nuevo en nuestra plataforma?') }}</span>
            <a href="{{ route('register') }}" wire:navigate>
                <span>{{ __('Crea una cuenta') }}</span>
            </a>
        </p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const passwordGroup = document.querySelector('.password-input-group');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Cambiar icono
                const icon = togglePassword.querySelector('i');
                if (type === 'text') {
                    icon.className = 'bx bx-hide';
                    togglePassword.setAttribute('data-bs-original-title', 'Ocultar contraseña');
                } else {
                    icon.className = 'bx bx-show';
                    togglePassword.setAttribute('data-bs-original-title', 'Mostrar contraseña');
                }
                
                // Actualizar tooltip
                const tooltip = bootstrap.Tooltip.getInstance(togglePassword);
                if (tooltip) {
                    tooltip.update();
                }
            });
            
            // Inicializar tooltip
            new bootstrap.Tooltip(togglePassword);
        }
    });
</script>
