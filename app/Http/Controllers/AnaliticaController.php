<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnaliticaController extends Controller
{
    public function extraerDatosLimpios()
    {
        $datos = DB::table('servicio as s')
            ->leftJoin('paciente as p', 's.id_servicio', '=', 'p.id_servicio')
            ->leftJoin('direccion as d', 'p.id_direccion', '=', 'd.id_direccion')
            ->select('s.costo_total', 's.tipo as tipo_servicio', 'd.id_colonia')
            ->where('s.estado', '=', 'Finalizado')
            ->get();

        $totalServicios = $datos->count();
        $ingresosTotales = $datos->sum('costo_total');
        $promedioCosto = $totalServicios > 0 ? $ingresosTotales / $totalServicios : 0;
        $coloniaFrecuente = $datos->whereNotNull('id_colonia')->countBy('id_colonia')->sortDesc()->keys()->first();

        $graficaTipos = $datos->countBy('tipo_servicio');
        $labelsGrafica = $graficaTipos->keys();
        $valoresGrafica = $graficaTipos->values();

        $graficaEstados = DB::table('servicio')
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');
        $labelsEstados = $graficaEstados->keys();
        $valoresEstados = $graficaEstados->values();

        $ingresosMes = DB::table('servicio')
            ->select(DB::raw('EXTRACT(MONTH FROM fecha_hora) as mes'), DB::raw('SUM(costo_total) as total'))
            ->where('estado', '=', 'Finalizado')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM fecha_hora)'))
            ->orderBy('mes')
            ->pluck('total', 'mes');
        $labelsMeses = $ingresosMes->keys();
        $valoresMeses = $ingresosMes->values();

        $lugares = DB::table('servicio as s')
            ->join('paciente as p', 's.id_servicio', '=', 'p.id_servicio')
            ->join('direccion as d', 'p.id_direccion', '=', 'd.id_direccion')
            ->select('d.id_colonia', DB::raw('count(*) as total'))
            ->whereNotNull('d.id_colonia')
            ->groupBy('d.id_colonia')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'id_colonia');
        $labelsLugares = $lugares->keys()->map(function($id) { return 'Colonia ' . $id; });
        $valoresLugares = $lugares->values();

        $serviciosPorDia = DB::table('servicio')
            ->select(DB::raw('DATE(fecha_hora) as dia'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('DATE(fecha_hora)'))
            ->orderByDesc('dia')
            ->limit(7)
            ->pluck('total', 'dia');
        $labelsDias = $serviciosPorDia->keys()->reverse()->values();
        $valoresDias = $serviciosPorDia->values()->reverse()->values();

        $lat = 17.0654; 
        $lng = -96.7236;

        return view('analitica.index', compact(
            'totalServicios', 'ingresosTotales', 'promedioCosto', 
            'coloniaFrecuente', 'labelsGrafica', 'valoresGrafica',
            'labelsEstados', 'valoresEstados', 'labelsMeses', 'valoresMeses',
            'labelsLugares', 'valoresLugares', 'labelsDias', 'valoresDias',
            'lat', 'lng'
        ));
    }
}