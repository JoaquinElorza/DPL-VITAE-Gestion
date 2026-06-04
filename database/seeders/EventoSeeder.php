<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Sembrando 500 eventos con estados balanceados...');

        $clienteIds = DB::table('cliente')->pluck('id_usuario')->all();
        $ambulanciaIds = DB::table('ambulancia')->pluck('id_ambulancia')->all();
        $operadorIds = DB::table('operador')->pluck('id_usuario')->all();

        if (empty($clienteIds) || empty($ambulanciaIds)) {
            $this->command->error('No hay clientes o ambulancias disponibles para sembrar eventos.');
            return;
        }

        $estados = ['Pendiente', 'En curso', 'Finalizado', 'Cancelado'];
        $cantidadPorEstado = 125;

        foreach ($estados as $estado) {
            for ($i = 0; $i < $cantidadPorEstado; $i++) {
                $fecha = match ($estado) {
                    'Pendiente' => Carbon::now()->addDays(rand(1, 30)),
                    'En curso'  => Carbon::now()->subMinutes(rand(0, 120)),
                    'Finalizado' => Carbon::now()->subDays(rand(1, 90)),
                    'Cancelado' => Carbon::now()->subDays(rand(1, 60)),
                    default => Carbon::now(),
                };

                $idServicio = DB::table('servicio')->insertGetId([
                    'costo_total'   => rand(500, 1800),
                    'estado'        => $estado,
                    'fecha_hora'    => $fecha,
                    'hora_salida'   => null,
                    'observaciones' => 'Evento semilla generado para estado ' . $estado,
                    'tipo'          => 'Evento',
                    'id_ambulancia' => $ambulanciaIds[array_rand($ambulanciaIds)],
                    'id_cliente'    => $clienteIds[array_rand($clienteIds)],
                    'id_operador'   => $operadorIds[array_rand($operadorIds)] ?? null,
                ], 'id_servicio');

                DB::table('evento')->insert([
                    'id_servicio' => $idServicio,
                    'duracion'    => rand(1, 5) . ' horas',
                    'personas'    => rand(1, 20),
                ]);
            }
        }

        $this->command->info('500 eventos sembrados correctamente.');
    }
}
