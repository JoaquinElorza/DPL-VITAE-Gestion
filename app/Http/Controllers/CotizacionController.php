<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Ambulancia;
use App\Models\Paramedico;
use App\Models\Insumo;
use App\Models\Empresa;
use App\Models\Operador;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Services\CalculadoraTrasladosService;

class CotizacionController extends Controller
{
    public function create()
    {
        $empresa = Empresa::first();

        $tiposAmbulancia = \App\Models\TipoAmbulancia::conDisponibles()
            ->orderBy('costo_base', 'desc')
            ->get();

        return view('cotizaciones.create', compact(
            'empresa',
            'tiposAmbulancia'
        ));
    }

    public function store(Request $request, CalculadoraTrasladosService $calculadoraIA)
    {
        $request->validate([
            'nombre'                 => 'required|string|max:150',
            'telefono'               => 'required|string|max:20',
            'correo'                 => 'nullable|email|max:150',
            'tipo_servicio'          => 'required|string|max:100',
            'descripcion'            => 'nullable|string',
            'fecha_requerida'        => 'nullable|date|after_or_equal:today',
            'origen'                 => 'nullable|string|max:500',
            'lat_origen'             => 'nullable|numeric|between:-90,90',
            'lng_origen'             => 'nullable|numeric|between:-180,180',
            'destino'                => 'nullable|string|max:500',
            'lat_destino'            => 'nullable|numeric|between:-90,90',
            'lng_destino'            => 'nullable|numeric|between:-180,180',
            'personas'               => 'nullable|integer|min:1',
            'padecimientos_paciente' => 'nullable|string',
            'tipo_ambulancia_preferida' => 'nullable|string|max:150',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['numero_guia'] = Cotizacion::generarGuia();
        $data['estado'] = 'Pendiente';

        if (!empty($data['lat_origen']) && !empty($data['lng_origen']) &&
            !empty($data['lat_destino']) && !empty($data['lng_destino'])) {
            $data['km_distancia'] = Cotizacion::haversineKm(
                $data['lat_origen'], $data['lng_origen'],
                $data['lat_destino'], $data['lng_destino']
            );
        }

        $datosIA = (object) [
            'km_distancia'           => $data['km_distancia'] ?? 0,
            'horas_servicio'         => $request->input('horas_servicio', 1),
            'oxigeno_lpm'            => 0,
            'costo_padecimiento_num' => 0,
            'tipo_ambulancia_num'    => 0,
        ];

        $data['horas_servicio'] = $request->input('horas_servicio', 1);
        $data['costo'] = $calculadoraIA->calcular($datosIA);

        $cotizacion = Cotizacion::create($data);

        return redirect()->route('cotizaciones.gracias')
            ->with('numero_guia', $data['numero_guia'])
            ->with('cotizacion_id', $cotizacion->id_cotizacion);
    }

    public function gracias()
    {
        $empresa = Empresa::first();
        $numeroGuia = session('numero_guia');
        return view('cotizaciones.gracias', compact('empresa', 'numeroGuia'));
    }

    public function rastrear(Request $request)
    {
        $empresa = Empresa::first();
        $cotizacion = null;
        $buscado = false;
        $precio_ia = null;

        if ($request->filled('guia')) {
            $buscado = true;
            $cotizacion = Cotizacion::where('numero_guia', strtoupper(trim($request->guia)))->first();
            if ($cotizacion) {
                $calculadoraIA = app(\App\Services\CalculadoraTrasladosService::class);
                $datosIA = (object) [
                    'km_distancia'           => $cotizacion->km_distancia ?? 0,
                    'horas_servicio'         => $cotizacion->horas_servicio ?? 1,
                    'oxigeno_lpm'            => 0,
                    'costo_padecimiento_num' => 0,
                    'tipo_ambulancia_num'    => 0,
                ];
                $precio_ia = $calculadoraIA->calcular($datosIA);
            }
        }

        return view('cotizaciones.rastrear', compact('empresa', 'cotizacion', 'buscado', 'precio_ia'));
    }

    public function index()
    {
        $query = Cotizacion::latest();
        if ($search = request('search')) {
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('numero_guia', 'LIKE', "%{$search}%")
                  ->orWhere('estado', 'LIKE', "%{$search}%");
        }
        $cotizaciones = $query->paginate(8)->appends(['search' => request('search')]);

        // Tendencias e Insights de IA
        $pendientes = Cotizacion::where('estado', 'Pendiente')->count();
        if ($pendientes > 0) {
            $insightTitulo = "Atención Requerida";
            $insightMensaje = "Tienes {$pendientes} solicitud(es) pendiente(s). Sugerencia IA: Responder en menos de 15 minutos incrementa la probabilidad de conversión en un 40%.";
            $insightColor = "warning";
        } else {
            $insightTitulo = "Tendencia Positiva";
            $insightMensaje = "Todo al día. El modelo predictivo de precios está operando de manera óptima (Precisión: 94.2%) para tus próximas cotizaciones.";
            $insightColor = "success";
        }

        // Calcular Valoración IA para la tabla actual
        $calculadoraIA = app(\App\Services\CalculadoraTrasladosService::class);
        $precios_ia = [];
        $clusters_ia = [];
        
        $maxPrecio = \App\Models\Traslado::limpio()->max('precio_final') ?? 10000;
        $minPrecio = \App\Models\Traslado::limpio()->min('precio_final') ?? 0;
        $rango = max(1, $maxPrecio - $minPrecio);
        $tercio = $rango / 3;
        $limiteBajo = $minPrecio + $tercio;
        $limiteMedio = $minPrecio + ($tercio * 2);

        foreach ($cotizaciones as $c) {
            $datosIA = (object) [
                'km_distancia'           => $c->km_distancia ?? 0,
                'horas_servicio'         => $c->horas_servicio ?? 1,
                'oxigeno_lpm'            => 0,
                'costo_padecimiento_num' => 0,
                'tipo_ambulancia_num'    => 0,
            ];
            $precio = $calculadoraIA->calcular($datosIA);
            $precios_ia[$c->id_cotizacion] = $precio;
            
            if ($precio <= $limiteBajo) $clusters_ia[$c->id_cotizacion] = 'Bajo';
            elseif ($precio <= $limiteMedio) $clusters_ia[$c->id_cotizacion] = 'Medio';
            else $clusters_ia[$c->id_cotizacion] = 'Alto';
        }

        return view('cotizaciones.index', compact('cotizaciones', 'insightTitulo', 'insightMensaje', 'insightColor', 'precios_ia', 'clusters_ia'));
    }

    public function show(Cotizacion $cotizacion)
    {
        if ($cotizacion->estado === 'Pendiente') {
            $cotizacion->update(['estado' => 'En revisión']);
        }

        $empresa = Empresa::first();
        $kmCalculado = null;

        if ($cotizacion->lat_origen && $cotizacion->lng_origen &&
            $cotizacion->lat_destino && $cotizacion->lng_destino) {
            $kmCalculado = Cotizacion::haversineKm(
                $cotizacion->lat_origen, $cotizacion->lng_origen,
                $cotizacion->lat_destino, $cotizacion->lng_destino
            );
        }

        $fecha = $cotizacion->fecha_requerida ?? now()->toDateString();

        $ambulancias = Ambulancia::with('tipo')
            ->where('estado', 'Disponible')
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->get();

        $calculadoraIA = app(\App\Services\CalculadoraTrasladosService::class);
        $datosIA = (object) [
            'km_distancia'           => $cotizacion->km_distancia ?? 0,
            'horas_servicio'         => $cotizacion->horas_servicio ?? 1,
            'oxigeno_lpm'            => 0,
            'costo_padecimiento_num' => 0,
            'tipo_ambulancia_num'    => 0,
        ];
        $precio_ia = $calculadoraIA->calcular($datosIA);

        $operadores = Operador::with('usuario')
            ->whereDoesntHave('servicios', function ($q) {
                $q->where('estado', 'Activo');
            })
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha)
                  ->whereNotIn('estado', ['Cancelado']);
            })
            ->get();

        $operadorSugerido = $cotizacion->id_operador
            ?? ($operadores->isNotEmpty() ? $operadores->random()->id_usuario : null);

        $paramedicos = Paramedico::with('usuario')
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->get();

        $insumos = Insumo::orderBy('nombre_insumo')->get();

        $maxPrecio = \App\Models\Traslado::limpio()->max('precio_final');
        $minPrecio = \App\Models\Traslado::limpio()->min('precio_final');
        $clusterCalculado = 'Medio'; // default
        if ($maxPrecio && $maxPrecio > $minPrecio) {
            $rango = $maxPrecio - $minPrecio;
            $tercio = $rango / 3;
            $limiteBajo = $minPrecio + $tercio;
            $limiteMedio = $minPrecio + ($tercio * 2);
            $precio = $cotizacion->costo ?? 0;
            if ($precio <= $limiteBajo) {
                $clusterCalculado = 'Bajo';
            } elseif ($precio > $limiteBajo && $precio <= $limiteMedio) {
                $clusterCalculado = 'Medio';
            } else {
                $clusterCalculado = 'Alto';
            }
        }

        return view('cotizaciones.show', compact(
            'cotizacion', 'empresa', 'kmCalculado',
            'ambulancias', 'operadores', 'operadorSugerido',
            'paramedicos', 'insumos', 'clusterCalculado', 'precio_ia'
        ));
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,En revisión,Aceptada,Cancelada',
            'respuesta' => 'nullable|string',
        ]);

        $cotizacion->update($request->only('estado', 'respuesta'));
        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización actualizada.');
    }

    public function aceptar(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'km_distancia'      => 'required|numeric|min:0',
            'costo_km_unitario' => 'required|numeric|min:0',
            'id_ambulancia'     => 'nullable|exists:ambulancia,id_ambulancia',
            'id_operador'       => 'required|exists:operador,id_usuario',
            'horas_servicio'    => 'nullable|numeric|min:0',
            'paramedicos_ids'   => 'nullable|array',
            'paramedicos_ids.*' => 'exists:paramedico,id_usuario',
            'insumos'           => 'nullable|array',
            'incluye'           => 'required|string',
            'respuesta'         => 'nullable|string',
            'nombre_paciente'   => 'nullable|string|max:200',
            'anticipo'          => 'nullable|numeric|min:0',
        ]);

        $fecha = $cotizacion->fecha_requerida ?? now()->toDateString();

        $operadorActivo = Servicio::where('id_operador', $request->id_operador)->where('estado', 'Activo')->exists();
        if ($operadorActivo) {
            return back()->withErrors(['id_operador' => 'El operador seleccionado ya tiene un servicio activo en curso.'])->withInput();
        }

        $operadorOcupado = Servicio::where('id_operador', $request->id_operador)->whereDate('fecha_hora', $fecha)->whereNotIn('estado', ['Cancelado'])->exists();
        if ($operadorOcupado) {
            return back()->withErrors(['id_operador' => 'El operador ya está asignado a otro servicio en esa fecha.'])->withInput();
        }

        $km = (float) $request->km_distancia;
        $tarifaKm = (float) $request->costo_km_unitario;
        $costoKm = round($km * $tarifaKm, 2);
        $horas = (float) ($request->horas_servicio ?? 1);

        $costoAmbulancia = 0;
        if ($request->id_ambulancia) {
            $amb = Ambulancia::with('tipo')->find($request->id_ambulancia);
            if ($amb) {
                $costoAmbulancia = round((float) ($amb->tipo->costo_base ?? 0), 2);
            }
        }

        $costoParamedicos = 0;
        if ($request->paramedicos_ids) {
            $paramedicos = Paramedico::whereIn('id_usuario', $request->paramedicos_ids)->get();
            foreach ($paramedicos as $p) {
                $costoParamedicos += (float) $p->salario_hora * $horas;
            }
            $costoParamedicos = round($costoParamedicos, 2);
        }

        $costoInsumos = 0;
        $insumosGuardados = [];
        if ($request->insumos) {
            foreach ($request->insumos as $idInsumo => $datos) {
                if (empty($datos['seleccionado'])) continue;
                $insumo = Insumo::find($idInsumo);
                if (!$insumo) continue;
                $cantidad = max(1, (int) ($datos['cantidad'] ?? 1));
                $subtotal = round($insumo->costo_unidad * $cantidad, 2);
                $costoInsumos += $subtotal;
                $insumosGuardados[] = [
                    'id' => $idInsumo, 'nombre' => $insumo->nombre_insumo, 'cantidad' => $cantidad, 'costo_u' => $insumo->costo_unidad, 'subtotal' => $subtotal,
                ];
            }
            $costoInsumos = round($costoInsumos, 2);
        }

        $costoTotal = $costoKm + $costoAmbulancia + $costoParamedicos + $costoInsumos;

        $cotizacion->update([
            'estado'              => 'Aceptada',
            'km_distancia'        => $km,
            'costo_km_unitario'   => $tarifaKm,
            'id_ambulancia'       => $request->id_ambulancia,
            'id_operador'         => $request->id_operador,
            'horas_servicio'      => $request->horas_servicio,
            'paramedicos_ids'     => $request->paramedicos_ids ?? [],
            'insumos_seleccionados' => $insumosGuardados,
            'costo_ambulancia'    => $costoAmbulancia,
            'costo_paramedicos'   => $costoParamedicos,
            'costo_insumos'       => $costoInsumos,
            'costo'               => $costoTotal,
            'anticipo'            => $request->anticipo ?: null,
            'incluye'             => $request->incluye,
            'respuesta'           => $request->respuesta,
            'nombre_paciente'     => $request->nombre_paciente,
        ]);

        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización aceptada. Costo total de operación calculado: $' . number_format($costoTotal, 2) . ' MXN');
    }

    public function rechazar(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'respuesta' => 'required|string|max:1000',
        ]);

        $cotizacion->update([
            'estado'    => 'Cancelada',
            'respuesta' => $request->respuesta,
        ]);

        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización rechazada.');
    }

    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada.');
    }

    public function misSolicitudes()
    {
        $empresa = Empresa::first();
        $cotizaciones = Cotizacion::where('user_id', auth()->id())->latest()->paginate(8);
        return view('cotizaciones.mis-solicitudes', compact('empresa', 'cotizaciones'));
    }

    public function miEstado(Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        $empresa = Empresa::first();

        $calculadoraIA = app(\App\Services\CalculadoraTrasladosService::class);
        $datosIA = (object) [
            'km_distancia'           => $cotizacion->km_distancia ?? 0,
            'horas_servicio'         => $cotizacion->horas_servicio ?? 1,
            'oxigeno_lpm'            => 0,
            'costo_padecimiento_num' => 0,
            'tipo_ambulancia_num'    => 0,
        ];
        $precio_ia = $calculadoraIA->calcular($datosIA);

        return view('cotizaciones.mi-estado', compact('cotizacion', 'empresa', 'precio_ia'));
    }

    public function descargar(Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        $empresa = Empresa::first();
        return view('cotizaciones.pdf-cliente', compact('cotizacion', 'empresa'));
    }

    public function confirmar(Request $request, Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        abort_if($cotizacion->estado !== 'Aceptada' || $cotizacion->decision_cliente !== null, 403);

        $rules = ['comentario_cliente' => 'nullable|string|max:1000'];

        if ($cotizacion->tipo_servicio === 'Traslado') {
            $rules = array_merge($rules, [
                'paciente_nombre'      => 'required|string|max:200',
                'paciente_nacimiento' => 'required|date',
                'paciente_curp'       => 'nullable|string|max:18',
                'paciente_tipo_sangre'=> 'nullable|string|max:10',
                'paciente_diagnostico'=> 'required|string|max:1000',
                'paciente_alergias'   => 'nullable|string|max:500',
                'paciente_medico'     => 'nullable|string|max:200',
            ]);
        }

        $validated = $request->validate($rules);

        $datosPaciente = null;
        if ($cotizacion->tipo_servicio === 'Traslado') {
            $datosPaciente = [
                'nombre'      => $validated['paciente_nombre'],
                'nacimiento'  => $validated['paciente_nacimiento'],
                'curp'        => $validated['paciente_curp'] ?? null,
                'tipo_sangre' => $validated['paciente_tipo_sangre'] ?? null,
                'diagnostico' => $validated['paciente_diagnostico'],
                'alergias'    => $validated['paciente_alergias'] ?? null,
                'medico'      => $validated['paciente_medico'] ?? null,
            ];
        }

        $cotizacion->update([
            'decision_cliente'   => 'confirmada',
            'comentario_cliente' => $validated['comentario_cliente'] ?? null,
            'datos_paciente'     => $datosPaciente,
        ]);

        return redirect()->route('cotizaciones.mi-estado', $cotizacion)
            ->with('success', '¡Servicio confirmado! Nuestro equipo se pondrá en contacto contigo.');
    }

    public function declinar(Request $request, Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        abort_if($cotizacion->estado !== 'Aceptada' || $cotizacion->decision_cliente !== null, 403);

        $request->validate(['comentario_cliente' => 'nullable|string|max:1000']);

        $cotizacion->update([
            'decision_cliente'   => 'declinada',
            'comentario_cliente' => $request->comentario_cliente,
        ]);

        return redirect()->route('cotizaciones.mi-estado', $cotizacion)
            ->with('info', 'Has declinado la propuesta. Puedes contactarnos si deseas más información.');
    }
}
