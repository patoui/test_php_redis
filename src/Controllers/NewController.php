<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Controllers;

use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use InvalidArgumentException;
use Patoui\TestPhpRedis\AggregateRootIds\AccountId;
use Patoui\TestPhpRedis\AggregateRoots\Account;
use Patoui\TestPhpRedis\DataTransferObjects\NewDeposit;
use Patoui\TestPhpRedis\DataTransferObjects\NewWithdrawal;
use Patoui\TestPhpRedis\Events\MessageDispatcher;
use Patoui\TestPhpRedis\Events\MessageRepository;
use Ramsey\Uuid\Uuid;

final class NewController
{
    use JsonHelper;

    private MessageDispatcher                   $message_dispatcher;
    private MessageRepository                   $message_repository;
    private EventSourcedAggregateRootRepository $aggregate_root_repository;

    public function __construct()
    {
        $this->message_dispatcher        = new MessageDispatcher();
        $this->message_repository        = MessageRepository::make();
        $this->aggregate_root_repository = new EventSourcedAggregateRootRepository(
            Account::class,
            $this->message_repository,
            $this->message_dispatcher
        );
    }

    public function add(): void
    {
        $amount = filter_var($_GET['a'], FILTER_VALIDATE_INT);
        $account_uuid = $_GET['uuid'] ?? Uuid::uuid4()->toString();

        $account_id = AccountId::fromString($account_uuid);

        $account = Account::initiate($account_id);

        $amount > 0
            ? $account->deposit(new NewDeposit($amount))
            : $account->withdraw(new NewWithdrawal($amount));

        // TODO: implement consumer for dispatched messages/events
        $this->aggregate_root_repository->persist($account);

        self::json(['Successfully updated account: ' . $account_id->toString()]);
    }

    public function show(): void
    {
        if (empty($_GET['uuid'])) {
            throw new InvalidArgumentException("Get parameter 'uuid' is required");
        }

        $account = $this->aggregate_root_repository->retrieve(
            AccountId::fromString($_GET['uuid'])
        );

        self::json([$account->balance]);
    }
}