<?php

namespace App\Core\Invoice\Domain\Status;

enum InvoiceStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case CANCELED = 'canceled';

    public static function fromString(string $status): self
    {
        return match ($status) {
            self::NEW->value => self::NEW,
            self::PAID->value => self::PAID,
            self::CANCELED->value => self::CANCELED,
            default => throw new \InvalidArgumentException("Invalid status: $status"),
        };
    }
}
