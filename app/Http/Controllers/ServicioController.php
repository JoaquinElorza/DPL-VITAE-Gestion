<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Ambulancia;
use App\Models\Cliente;
use App\Models\Operador;
use App\Models\Traslado;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\CalculadoraTrasladosService;

class ServicioController extends Controller
{
    protected $calculadora;

    public function __construct(CalculadoraTrasladosService $calculadora)
    {
        $this->calculadora = $calculadora;
    }

public function index(Request $request)
{
    $porPagina = $request->get('por_pagina', 10);

    $servicios = Servicio::with(['ambulancia', 'cliente.usuario', 'operador.usuario'])
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('estado', 'LIKE', "%{$search}%")
                  ->orWhere('tipo', 'LIKE', "%{$search}%")
                  ->orWhereHas('cliente.usuario', function ($q2) use ($search) {
                      $q2->where('nombre', 'LIKE', "%{$search}%");
                  });
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($porPagina)
        ->appends($request->query());

    return view('servicios.index', compact('servicios', 'porPagina'));
}

    public function create()
    {
        $ambulancias = Ambulancia::all();
        $clientes = Cliente::with('usuario')->get();
        $operadores = Operador::with('usuario')->get();
        return view('servicios.create', compact('ambulancias', 'clientes', 'operadores'));
    }

    public function store(Request $request)
    {
        $this->validarDisponibilidadOperador($request->id_operador, $request->fecha_hora);

        DB::beginTransaction();

        try {
            $servicio = Servicio::create([
                'costo_total'   => 0, 
                'estado'        => 'Pendiente',
                'fecha_hora'    => $request->fecha_hora,
                'hora_salida'   => null,
                'observaciones' => $request->observaciones,
                'tipo'          => 'Traslado',
                'id_ambulancia' => $request->id_ambulancia,
                'id_cliente'    => $request->id_cliente,
                'id_operador'   => $request->id_operador,
            ]);

            if (!empty($request->paramedicos_ids)) {
                $servicio->paramedicos()->attach($request->paramedicos_ids);
            }

            $tipoAmbulanciaNum = ($request->tipo_ambulancia === 'premium') ? 1 : 0;
            $numParamedicos = count($request->paramedicos_ids ?? []);

            $traslado = Traslado::create([
                'id_servicio'            => $servicio->id_servicio,
                'km_distancia'           => $request->km_distancia,
                'horas_servicio'         => $request->horas_servicio,
                'oxigeno_lpm'            => $request->oxigeno_lpm ?? 0,
                'costo_padecimiento_num' => $request->costo_padecimiento_num ?? 0,
                'tipo_ambulancia_num'    => $tipoAmbulanciaNum,
                'num_paramedicos'        => $numParamedicos,
                'precio_final'           => 0,
                'cluster'                => $request->cluster ?? null,
                'z_score'                => $request->z_score ?? null,
                'es_outlier'             => $request->es_outlier ?? false,
                'padecimientos'          => $request->padecimientos_string ?? null,
                'observaciones_medicas'  => $request->observaciones_medicas ?? null,
                'usable_para_modelo'     => true
            ]);

            $precioCalculado = $this->calculadora->calcular($traslado);

            $traslado->precio_modelo = $precioCalculado;
            $traslado->precio_final  = $request->precio_final ?? $precioCalculado;
            $traslado->save();

            $servicio->costo_total = $traslado->precio_final;
            $servicio->save();

            $paciente = Paciente::create([
                'nombre'           => $request->paciente_nombre ?? 'Paciente por confirmar',
                'ap_paterno'       => $request->paciente_paterno ?? 'S/P',
                'ap_materno'       => $request->paciente_materno ?? null,
                'oxigeno'          => ($request->oxigeno_lpm > 0) ? 1 : 0,
                'fecha_nacimiento' => $request->paciente_fecha_nacimiento ?? now()->subYears(30)->toDateString(),
                'sexo'             => $request->paciente_sexo ?? 'M',
                'peso'             => $request->paciente_peso ?? 70.00,
                'id_servicio'      => $servicio->id_servicio,
                'id_direccion'     => $request->id_direccion_destino ?? null,
            ]);

            if (!empty($request->padecimientos_ids)) {
                $paciente->padecimientos()->attach($request->padecimientos_ids);
            }

            DB::commit();

            return response()->json([
                'ok'       => true,
                'servicio' => $servicio->load('paramedicos', 'ambulancia'),
                'traslado' => $traslado,
                'paciente' => $paciente
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'ok'    => false,
                'error' => 'Error crítico al procesar el traslado en cascada: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Servicio $servicio)
    {
        $servicio->load(['ambulancia', 'cliente.usuario', 'operador.usuario', 'paciente', 'paramedicos.usuario', 'insumos']);
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        $ambulancias = Ambulancia::all();
        $clientes = Cliente::with('usuario')->get();
        $operadores = Operador::with('usuario')->get();
        return view('servicios.edit', compact('servicio', 'ambulancias', 'clientes', 'operadores'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'costo_total'   => 'required|numeric',
            'estado'        => 'required|string',
            'fecha_hora'    => 'required|date',
            'hora_salida'   => 'nullable|date',
            'observaciones' => 'nullable|string',
            'tipo'          => 'nullable|string',
            'id_ambulancia' => 'required|exists:ambulancia,id_ambulancia',
            'id_cliente'    => 'required|exists:cliente,id_usuario',
            'id_operador'   => 'required|exists:operador,id_usuario',
        ]);

        $this->validarDisponibilidadOperador($request->id_operador, $request->fecha_hora, $servicio->id_servicio);

        $servicio->update($data);
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado.');
    }

    private function validarDisponibilidadOperador(int $idOperador, string $fechaHora, ?int $excluirServicioId = null): void
    {
        $activo = Servicio::where('id_operador', $idOperador)
            ->where('estado', 'Activo')
            ->when($excluirServicioId, fn($q) => $q->where('id_servicio', '!=', $excluirServicioId))
            ->exists();

        if ($activo) {
            throw ValidationException::withMessages([
                'id_operador' => 'El operador seleccionado ya tiene un servicio activo en curso y no puede ser asignado.',
            ]);
        }

        $conflicto = Servicio::where('id_operador', $idOperador)
            ->where('fecha_hora', $fechaHora)
            ->when($excluirServicioId, fn($q) => $q->where('id_servicio', '!=', $excluirServicioId))
            ->exists();

        if ($conflicto) {
            throw ValidationException::withMessages([
                'id_operador' => 'El operador seleccionado ya está asignado a otro servicio en esa fecha y hora.',
            ]);
        }
    }
}