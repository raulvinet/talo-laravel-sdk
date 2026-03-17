<?php

namespace TuVendor\TaloLaravel\DTOs;

class UpdatePaymentPriceData
{
    public function __construct(
        public readonly float|int $amount,
        public readonly string $currency = 'ARS',
    ) {
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->currency,
            'amount' => $this->amount,
        ];
    }
}
