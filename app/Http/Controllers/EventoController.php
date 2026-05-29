<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Servicio;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $porPagina = $request->get('por_pagina', 10);
        $query = Evento::with('servicio');
        if ($search = $request->get('search')) {
            $query->where('id_evento', 'LIKE', "%{$search}%")
                  ->orWhere('duracion', 'LIKE', "%{$search}%")
                  ->orWhere('personas', 'LIKE', "%{$search}%")
                  ->orWhere('id_servicio', 'LIKE', "%{$search}%");
        }
        $eventos = $query->paginate($porPagina)->appends($request->query());
        return view('eventos.index', compact('eventos', 'porPagina'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        return view('eventos.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_servicio' => 'required|exists:servicio,id_servicio',
            'duracion' => 'required|numeric',
            'personas' => 'required|integer',
        ]);
        Evento::create($data);
        return redirect()->route('eventos.index')->with('success', 'Evento creado.');
    }

    public function show(Evento $evento)
    {
        $evento->load('servicio');
        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $servicios = Servicio::all();
        return view('eventos.edit', compact('evento', 'servicios'));
    }

    public function update(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'id_servicio' => 'required|exists:servicio,id_servicio',
            'duracion' => 'required|numeric',
            'personas' => 'required|integer',
        ]);
        $evento->update($data);
        return redirect()->route('eventos.index')->with('success', 'Evento actualizado.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado.');
    }
}
