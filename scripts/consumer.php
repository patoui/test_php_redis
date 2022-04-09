<?php

declare(strict_types=1);

use Patoui\TestPhpRedis\Consumer;
use Patoui\TestPhpRedis\Group;
use Patoui\TestPhpRedis\Messages\Message;
use Patoui\TestPhpRedis\Messages\MessageHandlerFactory;
use Patoui\TestPhpRedis\Stream;

require_once dirname(__DIR__) . '/src/bootstrap.php';

$opts          = getopt('n:', ['name:']);
$consumer_name = $opts['n'] ?? $opts['name'] ?? null;

if (!$consumer_name) {
    throw new InvalidArgumentException("Consumer 'name' is required.");
}

$redis    = redis();
$stream   = new Stream($redis, 'es_stream');
$group    = new Group($stream, 'mygroup');
$consumer = new Consumer($consumer_name);

while (true) {
    $messages = $consumer->readGroupMessages($group);
    foreach ($messages as $key => $items) {
        /** @var Message $message */
        foreach ($items as $message) {
            if ($message_handlers = MessageHandlerFactory::make($message)) {
                foreach ($message_handlers as $message_handler) {
                    $message_handler->handle();
                }
            }
        }
        echo "READ MESSAGE: {$key}" . PHP_EOL;
    }
}
