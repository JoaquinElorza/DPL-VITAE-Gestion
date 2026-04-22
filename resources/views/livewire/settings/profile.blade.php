<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $nombre = '';
    public string $ap_paterno = '';
    public string $ap_materno = '';
    public string $email = '';

    public function mount(): void
    {
        $this->nombre = Auth::user()->nombre;
        $this->ap_paterno = Auth::user()->ap_paterno;
        $this->ap_materno = Auth::user()->ap_materno ?? '';
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'ap_paterno' => ['required', 'string', 'max:100'],
            'ap_materno' => ['nullable', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id_usuario, 'id_usuario')
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->nombre);
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

@section('title', 'Profile')

<section>
    @include('partials.settings-heading')

    <x-settings.layout :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="mb-6 w-50">
            <div class="mb-4">
                <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                <input type="text" id="nombre" wire:model="nombre" class="form-control" placeholder="Nombre" required autofocus autocomplete="given-name">
            </div>

            <div class="mb-4">
                <label for="ap_paterno" class="form-label">{{ __('Apellido Paterno') }}</label>
                <input type="text" id="ap_paterno" wire:model="ap_paterno" class="form-control" placeholder="Apellido Paterno" required autocomplete="family-name">
            </div>

            <div class="mb-4">
                <label for="ap_materno" class="form-label">{{ __('Apellido Materno') }}</label>
                <input type="text" id="ap_materno" wire:model="ap_materno" class="form-control" placeholder="Apellido Materno (opcional)" autocomplete="additional-name">
            </div>

            <div class="mb-4">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input type="email" id="email" wire:model="email" class="form-control" placeholder="email@example.com" required autocomplete="email">

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div class="mt-3">
                        <p class="text-warning">
                            {{ __('Your email address is unverified.') }}
                            <a href="#" wire:click.prevent="resendVerificationNotification" class="text-info">{{ __('Click here to re-send the verification email.') }}</a>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-success">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
