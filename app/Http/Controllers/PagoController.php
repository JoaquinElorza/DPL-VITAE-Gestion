<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PagoController extends Controller
{
    public function iniciar(Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        abort_if($cotizacion->estado !== 'Aceptada', 403);
        abort_if($cotizacion->mp_pago_estado === 'approved', 403);

        $anticipo = (float) $cotizacion->anticipo;
        abort_if($anticipo <= 0, 400, 'No hay anticipo definido.');

        $response = Http::withToken(config('services.mercadopago.access_token'))
            ->withoutVerifying()          // solo en desarrollo local
            ->post('https://api.mercadopago.com/checkout/preferences', [
                'items' => [[
                    'title'       => 'Anticipo servicio ' . $cotizacion->numero_guia,
                    'quantity'    => 1,
                    'unit_price'  => $anticipo,
                    'currency_id' => 'MXN',
                ]],
                'payer' => [
                    'name'  => auth()->user()->nombre,
                    'email' => auth()->user()->email,
                ],
                'back_urls' => [
                    'success' => route('cotizaciones.pago.success', $cotizacion),
                    'failure' => route('cotizaciones.pago.failure', $cotizacion),
                    'pending' => route('cotizaciones.pago.pending', $cotizacion),
                ],
                'auto_return'       => 'approved',
                'external_reference'=> (string) $cotizacion->id_cotizacion,
                'statement_descriptor' => config('app.name'),
            ]);

        if (! $response->successful()) {
            return back()->withErrors(['mp' => 'No se pudo conectar con MercadoPago. Intenta más tarde.']);
        }

        $data = $response->json();

        $cotizacion->update(['mp_preference_id' => $data['id']]);

        // En sandbox usar sandbox_init_point, en producción init_point
        $url = app()->isProduction() ? $data['init_point'] : $data['sandbox_init_point'];

        return redirect($url);
    }

    public function success(Request $request, Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);

        $paymentId = $request->query('payment_id');
        $status    = $request->query('status');

        if ($status === 'approved' && $paymentId) {
            $cotizacion->update([
                'mp_payment_id'  => $paymentId,
                'mp_pago_estado' => 'approved',
            ]);
        }

        return redirect()
            ->route('cotizaciones.mi-estado', $cotizacion)
            ->with('success', '¡Anticipo pagado! Ya puedes confirmar tu servicio.');
    }

    public function failure(Request $request, Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);

        $cotizacion->update(['mp_pago_estado' => 'rejected']);

        return redirect()
            ->route('cotizaciones.mi-estado', $cotizacion)
            ->with('error', 'El pago fue rechazado. Puedes intentarlo de nuevo.');
    }

    public function pending(Request $request, Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);

        $paymentId = $request->query('payment_id');

        $cotizacion->update([
            'mp_payment_id'  => $paymentId,
            'mp_pago_estado' => 'pending',
        ]);

        return redirect()
            ->route('cotizaciones.mi-estado', $cotizacion)
            ->with('info', 'Tu pago está pendiente de acreditación. Te avisaremos cuando se confirme.');
    }

    // MercadoPago IPN webhook
    public function webhook(Request $request)
    {
        if ($request->query('type') !== 'payment') {
            return response()->json(['ok' => true]);
        }

        $paymentId = $request->query('data_id') ?? $request->input('data.id');
        if (! $paymentId) {
            return response()->json(['ok' => true]);
        }

        $response = Http::withToken(config('services.mercadopago.access_token'))
            ->withoutVerifying()
            ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

        if (! $response->successful()) {
            return response()->json(['ok' => false], 422);
        }

        $payment  = $response->json();
        $cotizId  = $payment['external_reference'] ?? null;
        $status   = $payment['status'] ?? null;

        if ($cotizId && $status) {
            Cotizacion::where('id_cotizacion', $cotizId)->update([
                'mp_payment_id'  => $paymentId,
                'mp_pago_estado' => $status,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
