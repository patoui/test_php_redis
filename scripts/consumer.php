<?php

declare(strict_types=1);

use Patoui\TestPhpRedis\Consumer;
use Patoui\TestPhpRedis\Group;
use Patoui\TestPhpRedis\Messages\UserCreated;
use Patoui\TestPhpRedis\Stream;

require_once dirname(__DIR__) . '/src/bootstrap.php';

$opts          = getopt('n:', ['name:']);
$consumer_name = $opts['n'] ?? $opts['name'] ?? null;

if (!$consumer_name) {
    throw new InvalidArgumentException("Consumer 'name' is required.");
}

$redis    = redis();
$stream   = new Stream($redis, 'mystream');
$group    = new Group($stream, 'mygroup');
$consumer = new Consumer($consumer_name);

while (true) {
    $messages = $consumer->readGroupMessages($group);
    /** @var array<int, UserCreated> $message */
    foreach ($messages as $key => $message) {
        $content = current($message)->id;
        echo "READ MESSAGE: {$key} : {$content}" . PHP_EOL;
    }
}
