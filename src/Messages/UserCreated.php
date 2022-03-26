<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Messages;

use Patoui\TestPhpRedis\Message;

final class UserCreated extends Message
{
    public string $id = 'ABC321';

    public string $email = 'johndoe@email.com';

    public function getId(): int|string
    {
        return $this->id;
    }
}
