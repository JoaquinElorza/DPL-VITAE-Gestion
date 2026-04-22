<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'webhooks/mercadopago',
        ]);

        $middleware->alias([
            'es.admin'    => \App\Http\Middleware\EsAdmin::class,
            'es.empleado' => \App\Http\Middleware\EsEmpleado::class,
            'es.cliente'  => \App\Http\Middleware\EsCliente::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
