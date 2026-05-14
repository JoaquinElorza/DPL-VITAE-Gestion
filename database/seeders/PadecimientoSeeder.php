<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PadecimientoSeeder extends Seeder
{
    public function run()
    {
        $rutaArchivo = database_path('seeders/data/cie10.csv');

        if (!file_exists($rutaArchivo)) {
            $this->command->error('No se encontró el archivo cie10.csv en database/seeders/data/');
            return;
        }

        $csv = fopen($rutaArchivo, 'r');
        $saltarPrimeraLinea = true; 
        $tamañoLote = 1000;         
        $datosParaInsertar = [];

        $this->command->info('Ajustando a 150 caracteres e insertando padecimientos...');

        while (($fila = fgetcsv($csv, 2000, ',')) !== false) {
            if ($saltarPrimeraLinea) {
                $saltarPrimeraLinea = false;
                continue;
            }

            if (isset($fila[0]) && isset($fila[1])) {
                $textoRaw = trim($fila[0]) . ' - ' . trim($fila[1]);

          
                $textoLimpio = mb_convert_encoding($textoRaw, 'UTF-8', 'Windows-1252');
                $textoLimpio = iconv('UTF-8', 'UTF-8//IGNORE', $textoLimpio);

                $datosParaInsertar[] = [
                  
                    'nombre_padecimiento' => mb_substr($textoLimpio, 0, 150, 'UTF-8'), 
                ];
            }

            if (count($datosParaInsertar) >= $tamañoLote) {
                DB::table('padecimiento')->insertOrIgnore($datosParaInsertar);
                $datosParaInsertar = []; 
            }
        }

        if (!empty($datosParaInsertar)) {
            DB::table('padecimiento')->insertOrIgnore($datosParaInsertar);
        }

        fclose($csv);

        $this->command->info('¡Catálogo CIE-10 inyectado con éxito! ✅');
    }
}