<?php

namespace Virulenta\TaloLaravel\DTOs;

class CreatePaymentData
{
    public function __construct(
        public readonly string $external_id,
        public readonly float|int $amount,
        public readonly string $currency = 'ARS',
        public readonly ?string $redirect_url = null,
        public readonly ?string $webhook_url = null,
        public readonly ?string $motive = null,
        public readonly array $items = [],
        public readonly ?ClientData $client_data = null,
        public readonly ?string $user_id = null,
    ) {
    }

    public function toArray(string $defaultUserId): array
    {
        return array_filter([
            'price' => [
                'currency' => $this->currency,
                'amount' => $this->amount,
            ],
            'user_id' => $this->user_id ?: $defaultUserId,
            'redirect_url' => $this->redirect_url,
            'webhook_url' => $this->webhook_url,
            'external_id' => $this->external_id,
            'motive' => $this->motive,
            'items' => empty($this->items) ? null : $this->items,
            'client_data' => $this->client_data?->toArray(),
        ], fn ($value) => !is_null($value));
    }
}
