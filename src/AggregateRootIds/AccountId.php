<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\AggregateRootIds;

use EventSauce\EventSourcing\AggregateRootId;

class AccountId implements AggregateRootId
{
    private function __construct(private string $uuid)
    {}

    public function toString(): string
    {
        return $this->uuid;
    }

    public static function fromString(string $uuid): AggregateRootId
    {
        return new static($uuid);
    }
}
