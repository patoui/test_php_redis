<?php

declare(strict_types=1);

use Patoui\TestPhpRedis\Messages\UserCreated;
use Patoui\TestPhpRedis\Stream;

require_once dirname(__DIR__) . '/src/bootstrap.php';

$opts          = getopt('n:', ['name:']);
$consumer_name = $opts['n'] ?? $opts['name'] ?? null;

if (!$consumer_name) {
    throw new InvalidArgumentException("Consumer 'name' is required.");
}

$redis  = redis();
$stream = new Stream($redis, 'mystream');

$last_read_key = "consumer:{$consumer_name}";
$last_read_id  = $redis->get($last_read_key);

$start_read_id = $last_read_id ? "({$last_read_id}" : '-';
$end_read_id   = '+';

while (true) {
    $messages = $stream->getMessagesInRange($start_read_id, $end_read_id, 10);
    /** @var array<int, UserCreated> $message */
    foreach ($messages as $key => $message) {
        $content = current($message)->id;
        echo "READ MESSAGE: {$key} : {$content}" . PHP_EOL;
    }
    if ($messages) {
        $last_message_id = array_key_last($messages);
        $redis->setNx($last_read_key, $last_message_id);
        $start_read_id = "({$last_message_id}";
        echo "STORED LAST MSG ID: {$last_message_id}" . PHP_EOL;
    }
}
