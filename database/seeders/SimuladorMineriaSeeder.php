<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimuladorMineriaSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Fabricando 500 servicios históricos para el simulador...');

        $idAmbulancia = DB::table('ambulancia')->value('id_ambulancia');
        $idCliente = DB::table('cliente')->value('id_usuario');

        if (!$idAmbulancia) {
            $idTipo = DB::table('tipo_ambulancia')->value('id_tipo_ambulancia') ?? DB::table('tipo_ambulancia')->insertGetId(['nombre_tipo' => 'Básica', 'costo_base' => 1000], 'id_tipo_ambulancia');
            $idAmbulancia = DB::table('ambulancia')->insertGetId(['id_tipo_ambulancia' => $idTipo, 'estado' => 'Disponible', 'placas' => 'XYZ-123'], 'id_ambulancia');
        }
        if (!$idCliente) {
            $idUsuario = DB::table('users')->insertGetId(['name' => 'Paciente Prueba', 'email' => 'paciente@prueba.com', 'password' => bcrypt('password')]);
            $idCliente = DB::table('cliente')->insertGetId(['id_usuario' => $idUsuario, 'telefono' => '9510000000'], 'id_cliente');
        }

        DB::table('traslados')->delete();
        DB::table('servicio')->where('tipo', 'Simulación Minería')->delete();

        $ahora = Carbon::now();

        for ($i = 0; $i < 480; $i++) {
            $km = rand(5, 60);
            $horas = round(($km / 40) + (rand(1, 30) / 10), 1);
            
            $precioFinal = 1200 + ($km * 45) + ($horas * 150) + rand(-200, 200);

            $idServicio = DB::table('servicio')->insertGetId([
                'costo_total' => $precioFinal,
                'estado' => 'Finalizado',
                'fecha_hora' => $ahora->subDays(rand(1, 180)),
                'id_ambulancia' => $idAmbulancia,
                'id_cliente' => $idCliente,
                'tipo' => 'Simulación Minería'
            ], 'id_servicio');

            DB::table('traslados')->insert([
                'id_servicio' => $idServicio,
                'km_distancia' => $km,
                'horas_servicio' => $horas,
                'oxigeno_lpm' => rand(0, 5),
                'costo_padecimiento_num' => rand(0, 500),
                'tipo_ambulancia_num' => rand(0, 1),
                'num_paramedicos' => rand(1, 2),
                'precio_final' => $precioFinal,
                'es_outlier' => false,
                'usable_para_modelo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        for ($i = 0; $i < 20; $i++) {
            $km = rand(1, 5);
            $horas = 0.5;
            $precioAbsurdo = rand(75000, 95000); 

            $idServicio = DB::table('servicio')->insertGetId([
                'costo_total' => $precioAbsurdo,
                'estado' => 'Finalizado',
                'fecha_hora' => $ahora,
                'id_ambulancia' => $idAmbulancia,
                'id_cliente' => $idCliente,
                'tipo' => 'Simulación Minería'
            ], 'id_servicio');

            DB::table('traslados')->insert([
                'id_servicio' => $idServicio,
                'km_distancia' => $km,
                'horas_servicio' => $horas,
                'oxigeno_lpm' => 0,
                'costo_padecimiento_num' => 0,
                'tipo_ambulancia_num' => 0,
                'num_paramedicos' => 1,
                'precio_final' => $precioAbsurdo,
                'es_outlier' => false,
                'usable_para_modelo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('¡Simulación sembrada con éxito! 500 registros listos.');
    }
}