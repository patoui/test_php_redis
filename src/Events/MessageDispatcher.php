<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Events;

use Redis;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDispatcher as BaseMessageDispatcher;

final class MessageDispatcher implements BaseMessageDispatcher
{
    public function __construct(public ?Redis $redis = null)
    {
        $this->redis = $redis ?? redis();
    }

    public function dispatch(Message ...$messages): void
    {
        $this->redis->xAdd('es_stream_v2', '*', array_map('igbinary_serialize', $messages));
    }
}
