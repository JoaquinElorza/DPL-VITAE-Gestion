<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Ambulancia;
use App\Models\Cliente;
use App\Models\Operador;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\CalculadoraTrasladosService;

class ServicioController extends Controller
{
   public function index()
    {
        $query = Servicio::with(['ambulancia', 'cliente.usuario', 'operador.usuario']);
        if ($search = request('search')) {
            $query->where('estado', 'LIKE', "%{$search}%")
                  ->orWhere('tipo', 'LIKE', "%{$search}%")
                  ->orWhereHas('cliente.usuario', function($q) use ($search) {
                      $q->where('nombre', 'LIKE', "%{$search}%");
                  });
        }
        $servicios = $query->orderBy('created_at', 'desc')->paginate(8)->appends(['search' => request('search')]);
        return view('servicios.index', compact('servicios'));
    } 

    public function create()
    {
        $ambulancias = Ambulancia::all();
        $clientes = Cliente::with('usuario')->get();
        $operadores = Operador::with('usuario')->get();
        return view('servicios.create', compact('ambulancias', 'clientes', 'operadores'));
    }

/*    public function store(Request $request)
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

        $this->validarDisponibilidadOperador($request->id_operador, $request->fecha_hora);

        Servicio::create($data);
        return redirect()->route('servicios.index')->with('success', 'Servicio creado.');
    }

    */

    public function store(Request $request)
{
    DB::beginTransaction();

    try {

        // =====================
        // SERVICIO
        // =====================

        $servicio = Servicio::create([

            'costo_total' => 0,

            'estado' => 'pendiente',

            'fecha_hora' => $request->fecha_hora,

            'hora_salida' => null,

            'observaciones' => $request->observaciones,

            'tipo' => 'traslado',

            'id_ambulancia' => $request->id_ambulancia,

            'id_cliente' => $request->id_cliente,

            'id_operador' => $request->id_operador,
        ]);

        // =====================
        // VARIABLES NUMÉRICAS
        // =====================

        $tipoAmbulancia = 
            $request->tipo_ambulancia == 'premium'
            ? 1
            : 0;

        $numParamedicos = count(
            $request->paramedicos_ids ?? []
        );

        // =====================
        // TRASLADO
        // =====================

        $traslado = Traslado::create([

            'id_servicio' => $servicio->id_servicio,

            'km_distancia' => $request->km_distancia,

            'horas_servicio' => $request->horas_servicio,

            'oxigeno_lpm' => $request->oxigeno_lpm ?? 0,

            'costo_padecimiento_num' =>
                $request->costo_padecimiento_num ?? 0,

            'tipo_ambulancia_num' =>
                $tipoAmbulancia,

            'num_paramedicos' =>
                $numParamedicos,

            'precio_final' => 0,
        ]);

        $calculadora = new CalculadoraTrasladosService();

        $precio = $calculadora->calcular($traslado);

        $traslado->precio_final = $precio;
        $traslado->save();

        $servicio->costo_total = $precio;
        $servicio->save();

        DB::commit();

        return response()->json([
            'ok' => true,
            'servicio' => $servicio,
            'traslado' => $traslado,
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'ok' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function show(Servicio $servicio)
    {
        $servicio->load(['ambulancia', 'cliente.usuario', 'operador.usuario', 'pacientes', 'paramedicos.usuario', 'insumos']);
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
        // Operador con servicio activo (en curso) no puede ser asignado
        $activo = Servicio::where('id_operador', $idOperador)
            ->where('estado', 'Activo')
            ->when($excluirServicioId, fn($q) => $q->where('id_servicio', '!=', $excluirServicioId))
            ->exists();

        if ($activo) {
            throw ValidationException::withMessages([
                'id_operador' => 'El operador seleccionado ya tiene un servicio activo en curso y no puede ser asignado.',
            ]);
        }

        // Operador ya asignado en la misma fecha y hora exacta
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
