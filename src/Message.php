<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis;

abstract class Message
{
    abstract public function getId(): int|string;
}
