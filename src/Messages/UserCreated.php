<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Messages;

use Patoui\TestPhpRedis\Message;

final class UserCreated extends Message
{
    public function __construct(
        public string $id,
        public string $email
    ) {}

    public function getId(): int|string
    {
        return $this->id;
    }
}
