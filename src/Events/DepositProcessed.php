<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Patoui\TestPhpRedis\DataTransferObjects\NewDeposit;

final class DepositProcessed implements SerializablePayload
{
    public function __construct(public NewDeposit $new_deposit)
    {}

    public function toPayload(): array
    {
        return [
            'amount'    => $this->new_deposit->amount,
            'timestamp' => $this->new_deposit->timestamp,
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(new NewDeposit(...$payload));
    }
}
