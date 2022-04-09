<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Messages;

abstract class MessageHandler
{
    public function __construct(protected Message $message)
    {}

    abstract public function handle(): void;
}
