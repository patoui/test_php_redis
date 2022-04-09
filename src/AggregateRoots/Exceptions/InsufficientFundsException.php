<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\AggregateRoots\Exceptions;

use RuntimeException;

final class InsufficientFundsException extends RuntimeException
{
    public static function make(int $amount): self
    {
        return new self("Insufficient funds to make the requested withdrawal of: {$amount}");
    }
}
