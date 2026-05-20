<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MineriaDatosService;

class ProcesarMineria extends Command
{
    protected $signature = 'mineria:procesar';
    protected $description = 'Ejecuta el algoritmo de Z-Score y Clustering para limpiar el dataset de traslados';

    public function handle(MineriaDatosService $mineriaService)
    {
        $this->info('Iniciando escaneo de minería de datos...');
        
        $mineriaService->ejecutarLimpieza();
        
        $this->info('¡Outliers detectados y Clusters generados con éxito!');
        return 0;
    }
}