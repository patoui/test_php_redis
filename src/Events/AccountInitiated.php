<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class AccountInitiated implements SerializablePayload
{
    public int $timestamp;

    public function __construct(int $timestamp = null)
    {
        $this->timestamp = $timestamp ?? time();
    }

    public function toPayload(): array
    {
        return ['timestamp' => $this->timestamp];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(...$payload);
    }
}
