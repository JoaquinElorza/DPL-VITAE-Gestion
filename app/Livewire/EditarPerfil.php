<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditarPerfil extends Component
{
    // Datos personales
    public string $nombre      = '';
    public string $ap_paterno  = '';
    public string $ap_materno  = '';
    public string $email       = '';
    public string $telefono    = '';

    // Contraseña
    public string $current_password      = '';
    public string $password              = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->nombre     = $user->nombre       ?? '';
        $this->ap_paterno = $user->ap_paterno   ?? '';
        $this->ap_materno = $user->ap_materno   ?? '';
        $this->email      = $user->email        ?? '';
        $this->telefono   = $user->telefono     ?? '';
    }

    public function guardarPerfil(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'nombre'     => ['required', 'string', 'max:100'],
            'ap_paterno' => ['required', 'string', 'max:100'],
            'ap_materno' => ['nullable', 'string', 'max:100'],
            'email'      => [
                'required', 'string', 'email', 'max:150',
                Rule::unique('users', 'email')->ignore($user->id_usuario, 'id_usuario'),
            ],
            'telefono'   => ['nullable', 'string', 'max:20'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('perfil-guardado');
    }

    public function cambiarContrasena(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password'         => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('contrasena-cambiada');
    }

    public function render()
    {
        return view('livewire.editar-perfil');
    }
}
