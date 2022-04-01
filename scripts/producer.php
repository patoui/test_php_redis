<?php

declare(strict_types=1);

use Patoui\TestPhpRedis\Messages\UserCreated;
use Patoui\TestPhpRedis\Stream;

require_once dirname(__DIR__) . '/src/bootstrap.php';

$opts = getopt('c:', ['count:']);
$message_count = (int) ($opts['c'] ?? $opts['count'] ?? 100);

$stream = new Stream(redis(), 'mystream');

for ($i = 1; $i <= $message_count; $i++) {
    $message_id = $stream->addMessage(
        new UserCreated(
            (string) $i,
            'johndoe@email.com'
        )
    );
    echo "MESSAGE ID ADDED: {$message_id}" . PHP_EOL;
}
