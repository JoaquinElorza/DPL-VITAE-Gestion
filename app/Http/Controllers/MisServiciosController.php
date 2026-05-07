<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;

class MisServiciosController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $servicios = collect();

        if ($user->paramedico) {
            $servicios = $user->paramedico->servicios()->with('paciente.direccion.colonia', 'ambulancia')->get();
        } 
        elseif ($user->operador) {
            $servicios = $user->operador->servicios()->with('paciente.direccion.colonia', 'ambulancia')->get();
        }

        return view('mis-servicios.index', compact('servicios'));
    }

    // Nuevo método para ver los detalles
    public function show(Servicio $servicio)
    {
        // Cargamos todas las relaciones necesarias para mostrar detalles completos
        $servicio->load(['paciente.direccion.colonia', 'ambulancia', 'operador.usuario', 'paramedicos.usuario']);
        
        return view('mis-servicios.show', compact('servicio'));
    }
}