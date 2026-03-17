<?php

namespace Virulenta\TaloLaravel\DTOs;

class CreateCustomerData
{
    public function __construct(
        public readonly string $alias,
        public readonly string $customer_id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $webhook_url,
        public readonly ?string $user_id = null,
    ) {
    }

    public function toArray(string $defaultUserId): array
    {
        return [
            'user_id' => $this->user_id ?: $defaultUserId,
            'alias' => $this->alias,
            'customer_id' => $this->customer_id,
            'name' => $this->name,
            'contact' => [
                'email' => $this->email,
            ],
            'webhook_url' => $this->webhook_url,
        ];
    }
}
