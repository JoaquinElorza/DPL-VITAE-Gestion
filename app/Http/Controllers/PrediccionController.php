<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrediccionController extends Controller
{
    public function predecir(Request $request)
    {
        $modelo = DB::table('modelo_traslados')->first();

        $precio =
            $modelo->intercepto +

            (15 * $modelo->coef_km_distancia) +

            (3 * $modelo->coef_horas_servicio) +

            (2 * $modelo->coef_oxigeno_lpm) +

            (4 * $modelo->coef_costo_padecimiento) +

            (2 * $modelo->coef_tipo_ambulancia) +

            (2 * $modelo->coef_num_paramedicos);

        return response()->json([
            'precio_sugerido' => round($precio, 2)
        ]);
    }
}