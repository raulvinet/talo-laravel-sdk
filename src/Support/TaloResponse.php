<?php

namespace Virulenta\TaloLaravel\Support;

class TaloResponse
{
    public function __construct(
        public readonly bool $ok,
        public readonly ?string $message,
        public readonly bool $error,
        public readonly mixed $data,
        public readonly array $raw,
        public readonly int $status,
    ) {
    }

    public static function fromHttp(array $json, int $status): self
    {
        return new self(
            ok: $status >= 200 && $status < 300 && !($json['error'] ?? false),
            message: $json['message'] ?? null,
            error: (bool) ($json['error'] ?? false),
            data: $json['data'] ?? null,
            raw: $json,
            status: $status,
        );
    }
}
