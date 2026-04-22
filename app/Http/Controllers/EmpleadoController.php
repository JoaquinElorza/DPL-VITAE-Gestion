<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    public function miPanel()
    {
        $user = auth()->user();
        $user->load(['operador.ambulancias.tipo', 'paramedico']);

        $rol         = null;
        $servicios   = collect();
        $ambulancias = collect();

        $reservas = collect();

        if ($user->operador) {
            $rol         = 'operador';
            $ambulancias = $user->operador->ambulancias;
            $ids         = $ambulancias->pluck('id_ambulancia');

            $servicios = Servicio::whereIn('id_ambulancia', $ids)
                ->with(['evento', 'paramedicos.usuario', 'cliente.usuario', 'ambulancia.tipo'])
                ->orderBy('fecha_hora')
                ->get();

            $reservas = Cotizacion::whereIn('id_ambulancia', $ids)
                ->where('decision_cliente', 'confirmada')
                ->whereNotNull('fecha_requerida')
                ->get();

        } elseif ($user->paramedico) {
            $rol = 'paramedico';

            $servicios = $user->paramedico->servicios()
                ->with(['evento', 'ambulancia.tipo', 'cliente.usuario'])
                ->orderBy('fecha_hora')
                ->get();

            // JSON stores IDs as strings; search both variants to be safe
            $idStr = (string) $user->paramedico->id_usuario;
            $reservas = Cotizacion::where('decision_cliente', 'confirmada')
                ->whereNotNull('fecha_requerida')
                ->where(function ($q) use ($idStr) {
                    $q->whereJsonContains('paramedicos_ids', $idStr)
                      ->orWhereJsonContains('paramedicos_ids', (int) $idStr);
                })
                ->get();
        }

        $hoy       = Carbon::now();
        $inicioMes = $hoy->copy()->startOfMonth();
        $finMes    = $hoy->copy()->endOfMonth();

        $esteMes    = $servicios->filter(fn($s) => Carbon::parse($s->fecha_hora)->between($inicioMes, $finMes));
        $proximos   = $servicios->filter(fn($s) => Carbon::parse($s->fecha_hora)->isFuture() && $s->estado !== 'Cancelado')
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
            if ($s->evento) {
                $titulo = 'Evento: ' . $titulo;
            }

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
                    'tipo'        => $s->tipo ?? '—',
                    'ambulancia'  => $s->ambulancia?->placa ?? '—',
                    'tipo_amb'    => $s->ambulancia?->tipo?->nombre_tipo ?? '—',
                    'es_evento'   => $s->evento !== null,
                    'duracion'    => $s->evento?->duracion ?? '—',
                    'personas'    => $s->evento?->personas ?? '—',
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
                    'tipo_evento'  => 'reserva',
                    'guia'         => $c->numero_guia,
                    'tipo_servicio'=> $c->tipo_servicio,
                    'cliente'      => $c->nombre,
                    'telefono'     => $c->telefono,
                    'origen'       => $c->origen ?? '—',
                    'destino'      => $c->destino ?? '—',
                    'horas'        => $horas,
                    'costo'        => $c->costo ? '$' . number_format($c->costo, 2) . ' MXN' : '—',
                    'paciente'     => $c->datos_paciente['nombre'] ?? null,
                ],
            ];
        });

        $eventosCalendario = $eventosServicios->concat($eventosReservas)->values();

        return view('empleado.mi-panel', compact(
            'user', 'rol', 'ambulancias', 'servicios',
            'esteMes', 'proximos', 'completados', 'eventosCalendario'
        ));
    }

    public function actualizarPerfil(Request $request)
    {
        $user = auth()->user();

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
}
