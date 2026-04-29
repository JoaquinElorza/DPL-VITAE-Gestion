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

  
    $lat = 17.0654; 
    $lng = -96.7236;

    return view('analitica.index', compact(
        'totalServicios', 'ingresosTotales', 'promedioCosto', 
        'coloniaFrecuente', 'labelsGrafica', 'valoresGrafica', 'lat', 'lng'
    ));
}
}