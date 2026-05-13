<?php

namespace App\Services;

class CalculadoraTrasladosService
{
    public function calcular($traslado)
    {
        $precio = 0;

        $precio += $traslado->horas_servicio * 40;

        $precio += $traslado->num_paramedicos * $precio;

        $precio += $traslado->km_distancia * 0.5;

        $precio += $traslado->oxigeno_lpm * ($horas_servicio * 60);

        $precio += $traslado->costo_padecimiento_num;

        if ($traslado->tipo_ambulancia_num) {
            $precio *= 1.2;
        }

        return round($precio, 2);
    }
}