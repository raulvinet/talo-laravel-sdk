<?php

namespace Virulenta\TaloLaravel\Support\Webhook;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Virulenta\TaloLaravel\Support\Talo;

class TaloWebhookHandler
{
    public function __construct(
        protected readonly Talo $talo
    ) {
    }

    public function handle(array $payload): array
    {
        if (isset($payload['paymentId'])) {
            $paymentId = (string) $payload['paymentId'];
            $externalId = Arr::get($payload, 'externalId');

            $payment = $this->talo->getPayment($paymentId);

            Log::info('Talo payment webhook confirmado.', [
                'payment_id' => $paymentId,
                'external_id' => $externalId,
                'payment' => $payment->data,
            ]);

            return [
                'type' => 'payment',
                'resource_id' => $paymentId,
                'external_id' => $externalId,
                'resource' => $payment->data,
            ];
        }

        if (isset($payload['transactionId'], $payload['customerId'])) {
            $transactionId = (string) $payload['transactionId'];
            $customerId = (string) $payload['customerId'];

            $transaction = $this->talo->getCustomerTransaction($customerId, $transactionId);

            Log::info('Talo customer transaction webhook confirmado.', [
                'customer_id' => $customerId,
                'transaction_id' => $transactionId,
                'transaction' => $transaction->data,
            ]);

            return [
                'type' => 'customer_transaction',
                'resource_id' => $transactionId,
                'customer_id' => $customerId,
                'resource' => $transaction->data,
            ];
        }

        Log::warning('Webhook de Talo no reconocido.', [
            'payload' => $payload,
        ]);

        return [
            'type' => 'unknown',
            'resource' => $payload,
        ];
    }
}
