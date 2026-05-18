<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Traslado;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrasladoController extends Controller
{
    // Si usas un servicio de calculadora, asegúrate de inyectarlo en el constructor
    protected $calculadora;

    public function __construct()
    {
        // Si tu calculadora es una clase externa, instánciala aquí. 
    }

    public function store(Request $request)
    {

        $request->validate([
            'km_distancia'   => 'required|numeric|min:0',
            'horas_servicio' => 'required|numeric|min:1', 
            'oxigeno_lpm'    => 'nullable|numeric|min:0', 
            'id_ambulancia'  => 'required',
            'id_cliente'     => 'required',
            'id_operador'    => 'required',
        ]);

        // Asegúrate de traer esta función a este controlador si la necesitas
        // $this->validarDisponibilidadOperador($request->id_operador, $request->fecha_hora);

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

            $precioCalculado = 1500; // Valor de prueba (reemplaza por tu calculadora real)

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
}