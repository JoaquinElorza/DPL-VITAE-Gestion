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
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        // Todo usuario registrado es un cliente
        \App\Models\Cliente::create(['id_usuario' => $user->id_usuario]);

        Auth::login($user);

        $this->redirectIntended(route('cotizaciones.mis-solicitudes', absolute: false), navigate: true);
    }
}; ?>

@section('title', 'Register Page')

@section('page-style')
@vite([
    'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

<div>
    <h4 class="mb-1">{{ __('Adventure starts here') }} 🚀</h4>
    <p class="mb-6">{{ __('Make your app management easy and fun!') }}</p>

    @if (session('status'))
        <div class="alert alert-info mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="register" class="mb-6">
        <div class="mb-6">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input
                wire:model="nombre"
                type="text"
                class="form-control @error('nombre') is-invalid @enderror"
                id="nombre"
                required
                autofocus
                autocomplete="given-name"
                placeholder="{{ __('Nombre') }}"
            >
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-6">
            <label for="ap_paterno" class="form-label">{{ __('Apellido Paterno') }}</label>
            <input
                wire:model="ap_paterno"
                type="text"
                class="form-control @error('ap_paterno') is-invalid @enderror"
                id="ap_paterno"
                required
                autocomplete="family-name"
                placeholder="{{ __('Apellido Paterno') }}"
            >
            @error('ap_paterno')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-6">
            <label for="ap_materno" class="form-label">{{ __('Apellido Materno') }}</label>
            <input
                wire:model="ap_materno"
                type="text"
                class="form-control @error('ap_materno') is-invalid @enderror"
                id="ap_materno"
                autocomplete="additional-name"
                placeholder="{{ __('Apellido Materno (opcional)') }}"
            >
            @error('ap_materno')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-6">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input
                wire:model="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                required
                autocomplete="email"
                placeholder="{{ __('Enter your email') }}"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-6 form-password-toggle">
            <label class="form-label" for="password">{{ __('Password') }}</label>
            <div class="input-group input-group-merge">
                <input
                    wire:model="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    required
                    autocomplete="new-password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                >
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-6 form-password-toggle">
            <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
            <div class="input-group input-group-merge">
                <input
                    wire:model="password_confirmation"
                    type="password"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                >
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-8">
            <div class="form-check mb-0 ms-2">
                <input wire:model="terms" type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms">
                <label class="form-check-label" for="terms">
                    {{ __('I agree to') }}
                    <a href="javascript:void(0);">{{ __('privacy policy & terms') }}</a>
                </label>
                @error('terms')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-grid w-100 mb-6">
            {{ __('Sign up') }}
        </button>
    </form>

    <p class="text-center">
        <span>{{ __('Already have an account?') }}</span>
        <a href="{{ route('login') }}" wire:navigate>
            <span>{{ __('Sign in instead') }}</span>
        </a>
    </p>
</div>
