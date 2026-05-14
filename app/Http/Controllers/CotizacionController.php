<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Ambulancia;
use App\Models\Paramedico;
use App\Models\Insumo;
use App\Models\Empresa;
use App\Models\Operador;
use App\Models\Servicio;
use App\Models\TipoAmbulancia;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function create()
    {
        $empresa = Empresa::first();

        // FIX REAL: relación correcta + scope limpio
        $tiposAmbulancia = TipoAmbulancia::conDisponibles()
            ->orderBy('costo_base', 'desc')
            ->get();

        return view('cotizaciones.create', compact(
            'empresa',
            'tiposAmbulancia'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'correo' => 'nullable|email|max:150',
            'tipo_servicio' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha_requerida' => 'nullable|date|after_or_equal:today',
            'origen' => 'nullable|string|max:500',
            'lat_origen' => 'nullable|numeric|between:-90,90',
            'lng_origen' => 'nullable|numeric|between:-180,180',
            'destino' => 'nullable|string|max:500',
            'lat_destino' => 'nullable|numeric|between:-90,90',
            'lng_destino' => 'nullable|numeric|between:-180,180',
            'personas' => 'nullable|integer|min:1',
            'padecimientos_paciente' => 'nullable|string',
            'tipo_ambulancia_preferida' => 'nullable|string|max:150',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['numero_guia'] = Cotizacion::generarGuia();
        $data['estado'] = 'Pendiente';

        if (!empty($data['lat_origen']) && !empty($data['lng_origen']) &&
            !empty($data['lat_destino']) && !empty($data['lng_destino'])) {

            $data['km_distancia'] = Cotizacion::haversineKm(
                $data['lat_origen'], $data['lng_origen'],
                $data['lat_destino'], $data['lng_destino']
            );
        }

        $cotizacion = Cotizacion::create($data);

        return redirect()->route('cotizaciones.gracias')
            ->with('numero_guia', $data['numero_guia'])
            ->with('cotizacion_id', $cotizacion->id_cotizacion);
    }

    public function show(Cotizacion $cotizacion)
    {
        if ($cotizacion->estado === 'Pendiente') {
            $cotizacion->update(['estado' => 'En revisión']);
        }

        $empresa = Empresa::first();
        $fecha = $cotizacion->fecha_requerida ?? now()->toDateString();

        $ambulancias = Ambulancia::with('tipo')
            ->where('estado', 'Disponible')
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->get();

        $operadores = Operador::with('usuario')
            ->whereDoesntHave('servicios', function ($q) {
                $q->where('estado', 'Activo');
            })
            ->get();

        $paramedicos = Paramedico::with('usuario')
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->get();

        $insumos = Insumo::orderBy('nombre_insumo')->get();

        return view('cotizaciones.show', compact(
            'cotizacion',
            'empresa',
            'ambulancias',
            'operadores',
            'paramedicos',
            'insumos'
        ));
    }
}