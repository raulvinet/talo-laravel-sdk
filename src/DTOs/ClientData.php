<?php

namespace Virulenta\TaloLaravel\DTOs;

class ClientData
{
    public function __construct(
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly ?string $phone = null,
        public readonly ?string $email = null,
        public readonly ?string $dni = null,
        public readonly ?string $cuit = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'dni' => $this->dni,
            'cuit' => $this->cuit,
        ], fn ($value) => !is_null($value) && $value !== '');
    }
}
