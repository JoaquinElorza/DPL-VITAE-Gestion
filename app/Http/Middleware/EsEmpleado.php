<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EsEmpleado
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $user->loadMissing(['operador', 'paramedico', 'cliente']);

        if (!$user->esEmpleado()) {
            if ($user->esCliente()) {
                return redirect()->route('cotizaciones.mis-solicitudes');
            }
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
