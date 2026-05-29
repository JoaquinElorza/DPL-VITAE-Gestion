<?php

namespace App\Http\Controllers;

use App\Models\Ambulancia;
use App\Models\TipoAmbulancia;
use Illuminate\Http\Request;

class AmbulanciaController extends Controller
{
    public function index(Request $request)
    {
        $porPagina = $request->get('por_pagina', 10);
        $query = Ambulancia::with('tipo');
        if ($search = $request->get('search')) {
            $query->where('placa', 'LIKE', "%{$search}%")
                  ->orWhere('estado', 'LIKE', "%{$search}%")
                  ->orWhereHas('tipo', function ($q) use ($search) {
                      $q->where('nombre_tipo', 'LIKE', "%{$search}%");
                  });
        }
        $ambulancias = $query->paginate($porPagina)->appends($request->query());
        return view('ambulancias.index', compact('ambulancias', 'porPagina'));
    }

    public function create()
    {
        $tipos = TipoAmbulancia::all();
        return view('ambulancias.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'placa'              => 'required|string|max:20',
            'estado'             => 'required|in:Disponible,En servicio,En mantenimiento',
            'id_tipo_ambulancia' => 'required|exists:tipo_ambulancia,id_tipo_ambulancia',
        ]);
        Ambulancia::create($data);
        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia creada.');
    }

    public function show(Ambulancia $ambulancia)
    {
        $ambulancia->load(['tipo']);
        return view('ambulancias.show', compact('ambulancia'));
    }

    public function edit(Ambulancia $ambulancia)
    {
        $tipos = TipoAmbulancia::all();
        return view('ambulancias.edit', compact('ambulancia', 'tipos'));
    }

    public function update(Request $request, Ambulancia $ambulancia)
    {
        $data = $request->validate([
            'placa'              => 'required|string|max:20',
            'estado'             => 'required|in:Disponible,En servicio,En mantenimiento',
            'id_tipo_ambulancia' => 'required|exists:tipo_ambulancia,id_tipo_ambulancia',
        ]);
        $ambulancia->update($data);
        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia actualizada.');
    }

    public function destroy(Ambulancia $ambulancia)
    {
        $ambulancia->delete();
        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia eliminada.');
    }
}
