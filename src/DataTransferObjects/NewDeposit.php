<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\DataTransferObjects;

final class NewDeposit
{
    public int $amount;
    public int $timestamp;

    public function __construct(
        int $amount,
        int $timestamp = null
    ) {
        $this->amount    = $amount;
        $this->timestamp = $timestamp ?? time();
    }
}
