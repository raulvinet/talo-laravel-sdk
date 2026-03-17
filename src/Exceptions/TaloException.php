<?php

namespace Virulenta\TaloLaravel\Exceptions;

use Exception;

class TaloException extends Exception
{
    public function __construct(
        string $message = 'Talo error.',
        protected array $context = [],
        int $code = 0,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function context(): array
    {
        return $this->context;
    }
}
