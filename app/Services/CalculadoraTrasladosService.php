<?php

namespace App\Services;

use App\Models\modelo_traslados;

class CalculadoraTrasladosService
{
    public function calcular($traslado): float
    {
        if (is_array($traslado)) {
            $traslado = (object) $traslado;
        }

        $modelo = modelo_traslados::orderBy('id_modelo_servicio', 'desc')->first();

        $b0             = $modelo->b0             ?? 1000.00;
        $b_distancia    = $modelo->b_distancia    ?? 25.00;
        $b_horas        = $modelo->b_horas        ?? 150.00;
        $b_oxigeno      = $modelo->b_oxigeno      ?? 5.00;
        $b_padecimiento = $modelo->b_padecimiento ?? 300.00;
        $b_ambulancia   = $modelo->b_ambulancia   ?? 500.00;

        $horas       = $traslado->horas_servicio ?? 1;
        $km          = $traslado->km_distancia   ?? 0;
        $oxigeno_lpm = $traslado->oxigeno_lpm    ?? 0;
        $costo_pad   = $traslado->costo_padecimiento_num ?? 0;
        $es_premium  = $traslado->tipo_ambulancia_num    ?? false;

        $minutos_totales = $horas * 60;
        
        $precioModelo = $b0 
            + ($b_distancia * $km)
            + ($b_horas * $horas)
            + ($b_oxigeno * ($oxigeno_lpm * $minutos_totales))
            + ($b_padecimiento * $costo_pad)
            + ($b_ambulancia * ($es_premium ? 1 : 0));

        return round($precioModelo, 2);
    }
}