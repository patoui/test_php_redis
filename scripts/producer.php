<?php

declare(strict_types=1);

use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use Patoui\TestPhpRedis\AggregateRootIds\AccountId;
use Patoui\TestPhpRedis\AggregateRoots\Account;
use Patoui\TestPhpRedis\DataTransferObjects\NewDeposit;
use Patoui\TestPhpRedis\Events\MessageDispatcher;
use Patoui\TestPhpRedis\Events\MessageRepository;
use Ramsey\Uuid\Uuid;

require_once dirname(__DIR__) . '/src/bootstrap.php';

$opts          = getopt('c:k:', ['count:', 'chunk:']);
$message_count = (int) ($opts['c'] ?? $opts['count'] ?? 100);
$chunk_size    = (int) ($opts['k'] ?? $opts['chunk'] ?? 10);

$aggregate_root_repository = new EventSourcedAggregateRootRepository(
    Account::class,
    MessageRepository::make(),
    new MessageDispatcher()
);

$account = $aggregate_root_repository->retrieve(
    AccountId::fromString(Uuid::uuid4()->toString())
);

$current_count = 0;
for ($i = 1; $i <= $message_count; $i++) {
    $account->deposit(new NewDeposit($i));
    $current_count++;
    if ($chunk_size <= $current_count) {
        $aggregate_root_repository->persist($account);
        $current_count = 0;
    }
}

