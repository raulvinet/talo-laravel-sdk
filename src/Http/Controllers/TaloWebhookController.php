<?php

namespace TuVendor\TaloLaravel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use TuVendor\TaloLaravel\Support\Webhook\TaloWebhookHandler;

class TaloWebhookController extends Controller
{
    public function __invoke(Request $request, TaloWebhookHandler $handler): JsonResponse
    {
        $secret = config('talo.webhook_secret');

        if ($secret) {
            $incomingSecret = $request->header('X-Talo-Webhook-Secret')
                ?: $request->query('secret')
                ?: $request->input('secret');

            if (!hash_equals($secret, (string) $incomingSecret)) {
                Log::warning('Webhook Talo rechazado por secreto inválido.');

                return response()->json([
                    'ok' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }
        }

        $result = $handler->handle($request->all());

        return response()->json([
            'ok' => true,
            'type' => $result['type'],
        ]);
    }
}
