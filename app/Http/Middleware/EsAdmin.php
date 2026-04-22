<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $user->loadMissing(['operador', 'paramedico', 'cliente']);

        if ($user->esEmpleado()) {
            return redirect()->route('empleado.mi-panel');
        }

        if ($user->esCliente()) {
            return redirect()->route('cotizaciones.mis-solicitudes');
        }

        return $next($request);
    }
}
