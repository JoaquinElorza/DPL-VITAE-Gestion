<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Cotizacion;
use App\Models\Ambulancia;
use App\Models\Paramedico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    public function miPanel()
    {
        $user = Auth::user();
        $user->load(['operador', 'paramedico']);

        $servicios   = collect();
        $reservas    = collect();
        $ambulancias = collect();
        $ambulanciasDisponibles = collect();
        $todosParamedicos = collect();

        if ($user->operador) {
            // Obtenemos todas las ambulancias para el navbar y el listado general
            $ambulancias = Ambulancia::with('tipo')->get();
            $ambulanciasDisponibles = Ambulancia::where('estado', 'Disponible')->get();
            $todosParamedicos = Paramedico::with('usuario')->get();

            // Consulta de servicios sin la relación 'evento'
            $servicios = Servicio::with(['paramedicos.usuario', 'cliente.usuario', 'ambulancia.tipo'])
                ->orderBy('fecha_hora')
                ->get();

            $reservas = Cotizacion::where('decision_cliente', 'confirmada')
                ->where('estado', 'Aceptada')
                ->whereNotNull('fecha_requerida')
                ->get();

        } elseif ($user->paramedico) {
            $servicios = $user->paramedico->servicios()
                ->with(['ambulancia.tipo', 'cliente.usuario', 'paciente.direccion'])
                ->orderBy('fecha_hora')
                ->get();

            $idStr = (string) $user->paramedico->id_usuario;
            $reservas = Cotizacion::where('decision_cliente', 'confirmada')
                ->where('estado', 'Aceptada')
                ->whereNotNull('fecha_requerida')
                ->where(function ($q) use ($idStr) {
                    $q->whereJsonContains('paramedicos_ids', $idStr)
                      ->orWhereJsonContains('paramedicos_ids', (int) $idStr);
                })
                ->get();
        }

        $hoy         = Carbon::now();
        $inicioMes   = $hoy->copy()->startOfMonth();
        $finMes      = $hoy->copy()->endOfMonth();

        $esteMes     = $servicios->filter(fn($s) => Carbon::parse($s->fecha_hora)->between($inicioMes, $finMes));
        $proximos    = $servicios->filter(fn($s) => Carbon::parse($s->fecha_hora)->isFuture() && $s->estado !== 'Cancelado')
                                ->sortBy('fecha_hora')
                                ->take(6);
        $completados = $servicios->where('estado', 'Finalizado')->count();

        $colorPorEstado = [
            'Activo'     => '#696cff',
            'Finalizado' => '#8592a3',
            'Cancelado'  => '#ff3e1d',
        ];

        $eventosServicios = $servicios->map(function ($s) use ($colorPorEstado) {
            $color  = $colorPorEstado[$s->estado] ?? '#ffab00';
            $titulo = ($s->tipo ?? 'Servicio');
            
            return [
                'id'    => 'srv-' . $s->id_servicio,
                'title' => $titulo,
                'start' => Carbon::parse($s->fecha_hora)->toIso8601String(),
                'end'   => $s->hora_salida 
                    ? Carbon::parse($s->hora_salida)->toIso8601String() 
                    : Carbon::parse($s->fecha_hora)->addHours(2)->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'extendedProps'   => [
                    'tipo_evento' => 'servicio',
                    'estado'      => $s->estado,
                    'ambulancia'  => $s->ambulancia?->placa ?? '—',
                    'tipo_amb'    => $s->ambulancia?->tipo?->nombre_tipo ?? '—',
                    'observaciones' => $s->observaciones ?? '—',
                ],
            ];
        });

        $eventosReservas = $reservas->map(function ($c) {
            $horas  = (float) ($c->horas_servicio ?? 2);
            $inicio = Carbon::parse($c->fecha_requerida);
            return [
                'id'    => 'cot-' . $c->id_cotizacion,
                'title' => 'Reserva: ' . $c->tipo_servicio,
                'start' => $inicio->toIso8601String(),
                'end'   => $inicio->copy()->addHours($horas)->toIso8601String(),
                'backgroundColor' => '#ff9f43',
                'borderColor'     => '#ff9f43',
                'extendedProps'   => [
                    'tipo_evento'   => 'reserva',
                    'guia'          => $c->numero_guia,
                    'cliente'       => $c->nombre,
                    'origen'        => $c->origen ?? '—',
                    'destino'       => $c->destino ?? '—',
                    'costo'         => $c->costo ? '$' . number_format($c->costo, 2) . ' MXN' : '—',
                ],
            ];
        });

        $eventosCalendario = $eventosServicios->concat($eventosReservas)->values();

        // Agregamos todas las variables necesarias al compact para evitar errores en las vistas y navbar
        $data = compact(
            'user', 
            'ambulancias', 
            'servicios', 
            'esteMes', 
            'proximos', 
            'completados', 
            'eventosCalendario',
            'ambulanciasDisponibles',
            'todosParamedicos'
        );

        if ($user->operador) {
            return view('empleado.operador', $data);
        }

        if ($user->paramedico) {
            return view('empleado.paramedico', $data);
        }

        return redirect('/')->with('error', 'No tienes un rol de empleado asignado.');
    }

    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombre'     => 'required|string|max:100',
            'ap_paterno' => 'required|string|max:100',
            'ap_materno' => 'nullable|string|max:100',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'required|email|max:150|unique:users,email,' . $user->id_usuario . ',id_usuario',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only('nombre', 'ap_paterno', 'ap_materno', 'telefono', 'email');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('empleado.mi-panel')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    public function finalizarServicio(Servicio $servicio)
    {
        $servicio->update([
            'estado' => 'Finalizado',
            'hora_salida' => Carbon::now()
        ]);

        return back()->with('success', 'Servicio finalizado exitosamente.');
    }

    public function despacharReserva(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'id_ambulancia' => 'required|exists:ambulancia,id_ambulancia',
            'paramedicos'   => 'required|array',
            'paramedicos.*' => 'exists:paramedico,id_usuario'
        ]);

        Log::info('Iniciando despacho de reserva', [
            'cotizacion_id' => $cotizacion->id_cotizacion,
            'ambulancia_id' => $request->id_ambulancia,
            'paramedicos' => $request->paramedicos,
        ]);

        DB::beginTransaction();

        try {
            $servicio = Servicio::create([
                'costo_total'   => $cotizacion->costo,
                'estado'        => 'Activo',
                'fecha_hora'    => $cotizacion->fecha_requerida ?? now(),
                'tipo'          => $cotizacion->tipo_servicio,
                'id_ambulancia' => $request->id_ambulancia,
                'id_cliente'    => $cotizacion->user_id,
                'id_operador'   => Auth::id(),
                'observaciones' => $cotizacion->descripcion
            ]);

            $servicio->paramedicos()->attach($request->paramedicos);

            if ($cotizacion->tipo_servicio === 'Traslado') {
                
                $servicio->traslado()->create([
                    'km_distancia'           => $cotizacion->km_distancia ?? 0,
                    'horas_servicio'         => $cotizacion->horas_servicio ?? 1,
                    'oxigeno_lpm'            => 0, 
                    'costo_padecimiento_num' => 0,
                    'tipo_ambulancia_num'    => ($cotizacion->tipo_ambulancia_preferida === 'Premium'),
                    'num_paramedicos'        => count($request->paramedicos),
                    'precio_modelo'          => $cotizacion->costo,
                    'precio_final'           => $cotizacion->costo,
                    'padecimientos'          => $cotizacion->padecimientos_paciente,
                    'usable_para_modelo'     => true,
                ]);

                $jsonPaciente = $cotizacion->datos_paciente;

                Paciente::create([
                    'nombre'           => $jsonPaciente['nombre'] ?? ($cotizacion->nombre_paciente ?? 'Paciente por confirmar'),
                    'ap_paterno'       => $jsonPaciente['ap_paterno'] ?? 'S/P',
                    'ap_materno'       => $jsonPaciente['ap_materno'] ?? null,
                    'oxigeno'          => 0,
                    'fecha_nacimiento' => $jsonPaciente['nacimiento'] ?? now()->subYears(30)->toDateString(),
                    'sexo'             => $jsonPaciente['sexo'] ?? 'M',
                    'peso'             => $jsonPaciente['peso'] ?? 70.00,
                    'id_servicio'      => $servicio->id_servicio,
                    'id_direccion'     => null,
                ]);
            }

            Ambulancia::where('id_ambulancia', $request->id_ambulancia)->update(['estado' => 'En servicio']);
            $cotizacion->update(['estado' => 'Despachada']);

            DB::commit();
            return back()->with('success', 'Unidad despachada y traslado operativo iniciado con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al despachar reserva', [
                'cotizacion_id' => $cotizacion->id_cotizacion,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error operativo al despachar la unidad: ' . $e->getMessage());
        }
    }
}  