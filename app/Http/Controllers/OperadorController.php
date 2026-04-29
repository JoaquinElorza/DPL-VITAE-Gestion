<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OperadorController extends Controller
{
    public function index()
    {
        $operadores = Operador::with('usuario')
            ->withCount(['servicios as en_servicio' => fn($q) => $q->where('estado', 'Activo')])
            ->paginate(8);
        return view('operadores.index', compact('operadores'));
    }

    public function create()
    {
        return view('operadores.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre'          => 'required|string|max:100',
            'ap_paterno'      => 'required|string|max:100',
            'ap_materno'      => 'nullable|string|max:100',
            'telefono'        => 'required|string|max:15',
            'email'           => 'required|email|max:150|unique:users,email',
            'password'        => 'required|string|min:8|confirmed',
            'numero_licencia' => 'required|string|max:50',
            'fecha_licencia'  => 'required|date',
            'salario'         => 'required|numeric|min:7468',
        ];

        $messages = [
            'nombre.required'      => 'El nombre es obligatorio.',
            'ap_paterno.required'  => 'El primer apellido es obligatorio.',
            'telefono.required'    => 'El número de teléfono es obligatorio.',
            'salario.min'          => 'El salario no puede ser menor al mínimo de la LFT ($7,468.00).',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
        ];

        $request->validate($rules, $messages);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'nombre'     => $request->nombre,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'telefono'   => $request->telefono,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
            ]);

            Operador::create([
                'id_usuario'      => $user->id_usuario,
                'numero_licencia' => $request->numero_licencia,
                'fecha_licencia'  => $request->fecha_licencia,
                'salario_hora'    => $request->salario / 160, 
            ]);
        });

        return redirect()->route('operadores.index')->with('success', 'Operador creado correctamente.');
    }

    public function show(Operador $operador)
    {
        $operador->load(['usuario', 'servicios.ambulancia']);
        $enServicio = $operador->servicios->where('estado', 'Activo')->isNotEmpty();
        return view('operadores.show', compact('operador', 'enServicio'));
    }

    public function edit(Operador $operador)
    {
        $operador->load('usuario');
        return view('operadores.edit', compact('operador'));
    }

    public function update(Request $request, Operador $operador)
    {
        $rules = [
            'nombre'          => 'required|string|max:100',
            'ap_paterno'      => 'required|string|max:100',
            'ap_materno'      => 'nullable|string|max:100',
            'telefono'        => 'required|string|max:15',
            'email'           => 'required|email|max:150|unique:users,email,' . $operador->id_usuario . ',id_usuario',
            'password'        => 'nullable|string|min:8|confirmed',
            'numero_licencia' => 'required|string|max:50',
            'fecha_licencia'  => 'required|date',
            'salario'         => 'required|numeric|min:7468',
        ];

        $messages = [
            'nombre.required'     => 'El nombre es obligatorio.',
            'ap_paterno.required' => 'El primer apellido es obligatorio.',
            'salario.min'         => 'El salario no puede ser menor al mínimo de la LFT ($7,468.00).',
        ];

        $request->validate($rules, $messages);

        DB::transaction(function () use ($request, $operador) {
            $userData = [
                'nombre'     => $request->nombre,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'telefono'   => $request->telefono,
                'email'      => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $operador->usuario->update($userData);

            $operador->update([
                'numero_licencia' => $request->numero_licencia,
                'fecha_licencia'  => $request->fecha_licencia,
                'salario_hora'    => $request->salario / 160,
            ]);
        });

        return redirect()->route('operadores.index')->with('success', 'Operador actualizado correctamente.');
    }

    public function destroy(Operador $operador)
    {
        DB::transaction(function () use ($operador) {
            $operador->delete();
            $operador->usuario->delete();
        });
        return redirect()->route('operadores.index')->with('success', 'Operador eliminado.');
    }
}