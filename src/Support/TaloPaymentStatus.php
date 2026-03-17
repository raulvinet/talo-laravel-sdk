<?php

namespace Virulenta\TaloLaravel\Support;

class TaloPaymentStatus
{
    public const PENDING = 'PENDING';
    public const SUCCESS = 'SUCCESS';
    public const REJECTED = 'REJECTED';
    public const CANCELLED = 'CANCELLED';
    public const UNDER_REVIEW = 'UNDER_REVIEW';
    public const OVERPAID = 'OVERPAID';
    public const UNDERPAID = 'UNDERPAID';

    public static function isPaid(string $status): bool
    {
        return in_array($status, [
            self::SUCCESS,
            self::OVERPAID,
            self::UNDERPAID,
        ], true);
    }
}
