<?php

namespace App\Services;

use App\Models\Traslado;
use Illuminate\Support\Facades\DB;

class MineriaDatosService
{
    public function ejecutarLimpieza()
    {
        $this->detectarOutliers();
        $this->crearClusters();
    }


    public function detectarOutliers()
    {
        // Calculamos media (μ) y desviación estándar (σ)
        $stats = DB::table('traslados')
            ->selectRaw('AVG(precio_final) as media, COALESCE(STDDEV_SAMP(precio_final), 1) as desviacion')
            ->whereNotNull('precio_final')
            ->first();

        $media = $stats->media ?? 0;
        $desviacion = $stats->desviacion ?? 1;
        if ($desviacion == 0) $desviacion = 1; // Evitar división por cero

  
        DB::statement("
            UPDATE traslados 
            SET z_score = (precio_final - ?) / ?,
                es_outlier = CASE WHEN ABS((precio_final - ?) / ?) >= 3 THEN true ELSE false END
            WHERE precio_final IS NOT NULL
        ", [$media, $desviacion, $media, $desviacion]);
    }

    public function crearClusters()
    {
        $maxPrecio = Traslado::limpio()->max('precio_final');
        $minPrecio = Traslado::limpio()->min('precio_final');
        
        if (!$maxPrecio || $maxPrecio == $minPrecio) return;

 
        $rango = $maxPrecio - $minPrecio;
        $tercio = $rango / 3;

        $limiteBajo = $minPrecio + $tercio;
        $limiteMedio = $minPrecio + ($tercio * 2);

        DB::statement("
            UPDATE traslados 
            SET cluster = CASE 
                WHEN precio_final <= ? THEN 'Bajo'
                WHEN precio_final > ? AND precio_final <= ? THEN 'Medio'
                ELSE 'Alto'
            END
            WHERE es_outlier = false AND precio_final IS NOT NULL
        ", [$limiteBajo, $limiteBajo, $limiteMedio]);
    }


    public function obtenerPromedios()
    {
        return [
            'precio_promedio' => Traslado::limpio()->avg('precio_final') ?? 0,
            'distancia_promedio' => Traslado::limpio()->avg('km_distancia') ?? 0,
            'horas_promedio' => Traslado::limpio()->avg('horas_servicio') ?? 0,
        ];
    }
}