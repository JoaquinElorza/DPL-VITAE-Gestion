<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrasladoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Sembrando 500 traslados con estados balanceados...');

        $clienteIds = DB::table('cliente')->pluck('id_usuario')->all();
        $ambulanciaIds = DB::table('ambulancia')->pluck('id_ambulancia')->all();
        $operadorIds = DB::table('operador')->pluck('id_usuario')->all();

        if (empty($clienteIds) || empty($ambulanciaIds)) {
            $this->command->error('No hay clientes o ambulancias disponibles para sembrar traslados.');
            return;
        }

        $estados = ['Pendiente', 'En curso', 'Finalizado', 'Cancelado'];
        $clusters = ['bajo', 'medio', 'alto'];
        $cantidadPorEstado = 125;

        foreach ($estados as $estado) {
            for ($i = 0; $i < $cantidadPorEstado; $i++) {
                $km = rand(5, 60);
                $horas = round(max(0.5, ($km / 40) + (rand(1, 20) / 10)), 1);
                $precioFinal = round(max(500, 1200 + ($km * 40) + ($horas * 140) + rand(-200, 200)), 2);

                $fecha = match ($estado) {
                    'Pendiente' => Carbon::now()->addDays(rand(1, 30)),
                    'En curso'  => Carbon::now()->subMinutes(rand(0, 120)),
                    'Finalizado' => Carbon::now()->subDays(rand(1, 90)),
                    'Cancelado' => Carbon::now()->subDays(rand(1, 60)),
                    default => Carbon::now(),
                };

                $horaSalida = $fecha->copy()->addHours($horas);

                $idServicio = DB::table('servicio')->insertGetId([
                    'costo_total'   => $precioFinal,
                    'estado'        => $estado,
                    'fecha_hora'    => $fecha,
                    'hora_salida'   => $horaSalida,
                    'observaciones' => 'Traslado semilla generado para estado ' . $estado,
                    'tipo'          => 'Traslado',
                    'id_ambulancia' => $ambulanciaIds[array_rand($ambulanciaIds)],
                    'id_cliente'    => $clienteIds[array_rand($clienteIds)],
                    'id_operador'   => $operadorIds[array_rand($operadorIds)] ?? null,
                ], 'id_servicio');

                DB::table('traslados')->insert([
                    'id_servicio'            => $idServicio,
                    'km_distancia'           => $km,
                    'horas_servicio'         => $horas,
                    'oxigeno_lpm'            => rand(0, 5),
                    'costo_padecimiento_num' => rand(0, 500),
                    'tipo_ambulancia_num'    => rand(0, 1),
                    'num_paramedicos'        => rand(1, 3),
                    'costo_distancia'        => round($km * 25, 2),
                    'costo_horas'            => round($horas * 130, 2),
                    'costo_oxigeno'          => round(rand(0, 20) * 5, 2),
                    'costo_paramedicos'      => round(rand(1, 3) * 200, 2),
                    'costo_insumos'          => round(rand(0, 3) * 120, 2),
                    'precio_modelo'          => round($precioFinal * (rand(90, 110) / 100), 2),
                    'precio_final'           => $precioFinal,
                    'cluster'                => $clusters[array_rand($clusters)],
                    'z_score'                => round(rand(-200, 200) / 100, 2),
                    'es_outlier'             => false,
                    'padecimientos'          => 'Padecimiento semilla',
                    'observaciones_medicas'  => 'Observaciones médicas de prueba',
                    'usable_para_modelo'     => true,
                    'created_at'             => Carbon::now(),
                    'updated_at'             => Carbon::now(),
                ]);
            }
        }

        $this->command->info('500 traslados sembrados correctamente.');
    }
}
