<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Ambulancia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ambulancias = Ambulancia::select('id_ambulancia', 'placa')->get();

        $servicios = Servicio::with(['ambulancia', 'cliente.usuario'])
        ->when($request->buscar, function ($q, $buscar) {
            $q->where('id_servicio', 'LIKE', "%$buscar%")
              ->orWhere('tipo', 'LIKE', "%$buscar%");
        })
        ->when($request->tipo, function ($q, $tipo) {
            $q->where('tipo', $tipo);
        })
        ->when($request->estado, function ($q, $estado) {
            $q->where('estado', $estado);
        })
        ->when($request->ambulancia, function ($q, $ambulancia) {
            $q->where('id_ambulancia', $ambulancia);
        })
        ->when($request->fecha_inicio, function ($q, $fecha) {
            $q->whereDate('fecha_hora', '>=', $fecha);
        })
        ->when($request->fecha_fin, function ($q, $fecha) {
            $q->whereDate('fecha_hora', '<=', $fecha . ' 23:59:59');
        })
        ->orderByDesc('fecha_hora')
        ->paginate(10);

        $tipos = [
            'Traslado' => 'Traslados',
            'Evento' => 'Eventos',
            'Otro' => 'Otros'
        ];

        $estados = [
            'Pendiente' => 'Pendiente',
            'En curso' => 'En curso',
            'Finalizado' => 'Finalizado',
            'Cancelado' => 'Cancelado'
        ];

        return view('dashboard', compact('servicios', 'tipos', 'estados', 'ambulancias'));
    }
}