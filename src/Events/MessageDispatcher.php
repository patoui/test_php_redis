<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Events;

use Patoui\TestPhpRedis\Messages\Message;
use Redis;
use EventSauce\EventSourcing\Message as EventMessage;
use EventSauce\EventSourcing\MessageDispatcher as BaseMessageDispatcher;

final class MessageDispatcher implements BaseMessageDispatcher
{
    public function __construct(public ?Redis $redis = null)
    {
        $this->redis = $redis ?? redis();
    }

    public function dispatch(EventMessage ...$messages): void
    {
        /** @var EventMessage $message */
        $formatted_messages = array_map(static function ($message) {
            return igbinary_serialize(Message::make($message));
        }, $messages);

        $message_id = $this->redis->xAdd('es_stream', '*', $formatted_messages);

        if (PHP_SAPI === 'cli') {
            echo "ADDED MESSAGE {$message_id}" . PHP_EOL;
        }
    }
}
