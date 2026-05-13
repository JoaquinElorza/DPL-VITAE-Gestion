<?php

namespace App\Http\Controllers;

use App\Models\Paramedico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParamedicoController extends Controller
{
    public function index()
    {
        $paramedicos = Paramedico::with('usuario')->paginate(8);
        return view('paramedicos.index', compact('paramedicos'));
    }

    public function create()
    {
        return view('paramedicos.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre'     => 'required|string|max:100',
            'ap_paterno' => 'required|string|max:100',
            'ap_materno' => 'nullable|string|max:100',
            'telefono'   => 'required|string|max:15',
            'email'      => 'required|email|max:150|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'categoria'  => 'required|string',
            'salario'    => 'required|numeric|min:7468',
        ];

        $messages = [
            'nombre.required'     => 'El nombre es obligatorio.',
            'ap_paterno.required' => 'El primer apellido es obligatorio.',
            'salario.min'         => 'El salario no puede ser menor al mínimo de la LFT ($7,468.00).',
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

            Paramedico::create([
                'id_usuario'   => $user->id_usuario,
                'categoria'    => $request->categoria,
                'salario_hora' => $request->salario / 160,
            ]);
        });

        return redirect()->route('paramedicos.index')->with('success', 'Paramédico creado correctamente.');
    }

    public function show(Paramedico $paramedico)
    {
        $paramedico->load(['usuario', 'servicios']);
        return view('paramedicos.show', compact('paramedico'));
    }

    public function edit(Paramedico $paramedico)
    {
        $paramedico->load('usuario');
        return view('paramedicos.edit', compact('paramedico'));
    }

    public function update(Request $request, Paramedico $paramedico)
    {
        $rules = [
            'nombre'     => 'required|string|max:100',
            'ap_paterno' => 'required|string|max:100',
            'ap_materno' => 'nullable|string|max:100',
            'telefono'   => 'required|string|max:15',
            'email'      => 'required|email|max:150|unique:users,email,' . $paramedico->id_usuario . ',id_usuario',
            'password'   => 'nullable|string|min:8|confirmed',
            'categoria'  => 'required|string',
            'salario'    => 'required|numeric|min:7468',
        ];

        $messages = [
            'nombre.required'     => 'El nombre es obligatorio.',
            'ap_paterno.required' => 'El primer apellido es obligatorio.',
            'salario.min'         => 'El salario no puede ser menor al mínimo de la LFT ($7,468.00).',
        ];

        $request->validate($rules, $messages);

        DB::transaction(function () use ($request, $paramedico) {
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

            $paramedico->usuario->update($userData);

            $paramedico->update([
                'categoria'    => $request->categoria,
                'salario_hora' => $request->salario / 160,
            ]);
        });

        return redirect()->route('paramedicos.index')->with('success', 'Paramédico actualizado correctamente.');
    }

    public function destroy(Paramedico $paramedico)
    {
        DB::transaction(function () use ($paramedico) {
            $paramedico->usuario->delete();
            $paramedico->delete();
        });
        return redirect()->route('paramedicos.index')->with('success', 'Paramédico eliminado.');
    }
}