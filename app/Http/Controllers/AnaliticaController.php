<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnaliticaController extends Controller
{
    public function extraerDatosLimpios()
    {
        $datos = DB::table('servicio as s')
            ->leftJoin('ambulancia as a', 's.id_ambulancia', '=', 'a.id_ambulancia')
            ->leftJoin('paciente as p', 's.id_servicio', '=', 'p.id_servicio')
            ->leftJoin('direccion as d', 'p.id_direccion', '=', 'd.id_direccion')
            ->select(
                's.id_servicio',
                's.fecha_hora',
                's.hora_salida',
                's.costo_total',
                's.tipo as tipo_servicio',
                'a.placa',
                'p.peso',
                'p.oxigeno',
                'd.id_colonia'
            )
            ->where('s.estado', '=', 'Finalizado')
            ->whereNotNull('s.hora_salida')
            ->where('s.costo_total', '>', 0)
            ->whereNull('s.deleted_at')
            ->get();

        $ingresosTotales = round($datos->sum('costo_total'), 2);
        $promedioCosto = round($datos->avg('costo_total'), 2);
        $totalServicios = $datos->count();

        $coloniaFrecuente = $datos->whereNotNull('id_colonia')
            ->countBy('id_colonia')
            ->sortDesc()
            ->keys()
            ->first();

        $graficaTipos = $datos->countBy('tipo_servicio');
        $labelsGrafica = $graficaTipos->keys();
        $valoresGrafica = $graficaTipos->values();

        // Variables faltantes para el mapa (Oaxaca Centro)
        $lat = 17.0654; 
        $lng = -96.7236;

        return view('analitica.index', compact(
            'totalServicios', 
            'ingresosTotales', 
            'promedioCosto', 
            'coloniaFrecuente',
            'labelsGrafica',
            'valoresGrafica',
            'lat',
            'lng'
        ));
    }
}