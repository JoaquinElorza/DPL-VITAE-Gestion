<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Servicio;
use App\Models\Direccion;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $porPagina = $request->get('por_pagina', 10);

        // Subquery to get the latest id_paciente for each unique patient
        $subquery = Paciente::selectRaw('MAX(id_paciente) as id')
            ->groupBy('curp', 'nombre', 'ap_paterno', 'ap_materno');

        $query = Paciente::whereIn('id_paciente', $subquery)->with(['servicio', 'direccion']);

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('ap_paterno', 'LIKE', "%{$search}%")
                  ->orWhere('ap_materno', 'LIKE', "%{$search}%")
                  ->orWhere('curp', 'LIKE', "%{$search}%");
            });
        }

        $pacientes = $query->paginate($porPagina)->appends($request->query());

        // Inject the count of services for each patient in the current page
        foreach ($pacientes->items() as $paciente) {
            $paciente->total_servicios = Paciente::when($paciente->curp, function($q) use ($paciente) {
                    return $q->where('curp', $paciente->curp);
                }, function($q) use ($paciente) {
                    return $q->whereNull('curp')
                             ->where('nombre', $paciente->nombre)
                             ->where('ap_paterno', $paciente->ap_paterno)
                             ->where('ap_materno', $paciente->ap_materno);
                })->count();
        }

        return view('pacientes.index', compact('pacientes', 'porPagina'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        $direcciones = Direccion::with('colonia')->get();
        return view('pacientes.create', compact('servicios', 'direcciones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'oxigeno' => 'nullable|numeric',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:1',
            'peso' => 'nullable|numeric',
            'id_servicio' => 'required|exists:servicio,id_servicio',
            'id_direccion' => 'nullable|exists:direccion,id_direccion',
            'curp' => 'required|string|size:18',
        ]);
        Paciente::create($data);
        return redirect()->route('pacientes.index')->with('success', 'Paciente creado.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load(['servicio', 'direccion.colonia.municipio', 'padecimientos']);

        // Get all services for this unique physical patient
        $idServicios = Paciente::when($paciente->curp, function($q) use ($paciente) {
                return $q->where('curp', $paciente->curp);
            }, function($q) use ($paciente) {
                return $q->whereNull('curp')
                         ->where('nombre', $paciente->nombre)
                         ->where('ap_paterno', $paciente->ap_paterno)
                         ->where('ap_materno', $paciente->ap_materno);
            })
            ->pluck('id_servicio');

        $servicios = Servicio::whereIn('id_servicio', $idServicios)
            ->orderBy('fecha_hora', 'desc')
            ->get();

        return view('pacientes.show', compact('paciente', 'servicios'));
    }

    public function edit(Paciente $paciente)
    {
        $servicios = Servicio::all();
        $direcciones = Direccion::with('colonia')->get();
        return view('pacientes.edit', compact('paciente', 'servicios', 'direcciones'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'oxigeno' => 'nullable|numeric',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:1',
            'peso' => 'nullable|numeric',
            'id_servicio' => 'required|exists:servicio,id_servicio',
            'id_direccion' => 'nullable|exists:direccion,id_direccion',
            'curp' => 'required|string|size:18',
        ]);
        $paciente->update($data);
        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado.');
    }

    public function destroy(Paciente $paciente)
    {
        // Delete all rows associated with this unique physical patient
        $query = Paciente::query();
        if ($paciente->curp) {
            $query->where('curp', $paciente->curp);
        } else {
            $query->whereNull('curp')
                  ->where('nombre', $paciente->nombre)
                  ->where('ap_paterno', $paciente->ap_paterno)
                  ->where('ap_materno', $paciente->ap_materno);
        }
        $query->delete();

        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado.');
    }
}
