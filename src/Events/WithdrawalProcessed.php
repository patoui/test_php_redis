<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Patoui\TestPhpRedis\DataTransferObjects\NewWithdrawal;

final class WithdrawalProcessed implements SerializablePayload
{
    public function __construct(public NewWithdrawal $new_withdrawal)
    {}

    public function toPayload(): array
    {
        return [
            'amount'    => $this->new_withdrawal->amount,
            'timestamp' => $this->new_withdrawal->timestamp,
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(new NewWithdrawal(...$payload));
    }
}
