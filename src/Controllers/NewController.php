<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Controllers;

use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
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

    public function store(): void
    {
        $initial_deposit = null;

        if (isset($_GET['i'])) {
            $initial_deposit = filter_var($_GET['i'], FILTER_VALIDATE_INT);

            if ($initial_deposit === false) {
                self::json([
                    'message' => 'Invalid value for initial deposit.',
                ], 422);
            }
        }

        $account_id = AccountId::fromString(Uuid::uuid4()->toString());

        $account = Account::initiate($account_id);

        if ($initial_deposit) {
            $account->deposit(new NewDeposit($initial_deposit));
        }

        $this->aggregate_root_repository->persist($account);

        self::json([
            'message' => 'Successfully created account: ' . $account_id->toString(),
            'account' => $account_id->toString(),
        ]);
    }

    public function update()
    {
        if (empty($_GET['a'])) {
            self::json([
                'message' => 'Missing get parameter \'a\' for amount, must be a valid integer.',
            ], 422);
        }

        $amount = filter_var($_GET['a'], FILTER_VALIDATE_INT);

        if ($amount === false) {
            self::json([
                'message' => 'Invalid value for amount.',
            ], 422);
        }

        $account_uuid = self::getUuidParams();

        $account = $this->aggregate_root_repository->retrieve(
            AccountId::fromString($account_uuid)
        );

        $amount > 0
            ? $account->deposit(new NewDeposit($amount))
            : $account->withdraw(new NewWithdrawal(abs($amount)));

        $this->aggregate_root_repository->persist($account);

        self::json([
            'message' => 'Successfully updated account: ' . $account_uuid,
            'account' => $account_uuid,
            'balance' => $account->getBalance(),
        ]);
    }

    public function show(): void
    {
        $account_uuid = self::getUuidParams();

        $account = $this->aggregate_root_repository->retrieve(
            AccountId::fromString($account_uuid)
        );

        self::json([
            'account' => $account_uuid,
            'balance' => $account->getBalance(),
        ]);
    }

    private static function getUuidParams(): string
    {
        $uuid = $_GET['uuid'] ?? '';

        if (!Uuid::isValid($uuid)) {
            self::json([
                'message' => 'Invalid uuid in get parameter.',
            ], 422);
        }

        return $uuid;
    }
}