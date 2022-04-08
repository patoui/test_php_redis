<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\AggregateRoots;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Patoui\TestPhpRedis\AggregateRootIds\AccountId;
use Patoui\TestPhpRedis\DataTransferObjects\NewDeposit;
use Patoui\TestPhpRedis\DataTransferObjects\NewWithdrawal;
use Patoui\TestPhpRedis\Events\DepositProcessed;
use Patoui\TestPhpRedis\Events\WithdrawalProcessed;

class Account implements AggregateRoot
{
    use AggregateRootBehaviour;

    public int $balance = 0;

    public static function initiate(AccountId $id): Account
    {
        return new static($id);
    }

    public function deposit(NewDeposit $new_deposit): void
    {
        $this->recordThat(new DepositProcessed($new_deposit));
    }

    public function applyDepositProcessed(DepositProcessed $deposited): void
    {
        $this->balance += $deposited->new_deposit->amount;
    }

    public function withdraw(NewWithdrawal $new_withdrawal): void
    {
        $this->recordThat(new WithdrawalProcessed($new_withdrawal));
    }

    public function applyWithdrawlProcessed(WithdrawalProcessed $withdrawl): void
    {
        $this->balance -= $withdrawl->new_withdrawl->amount;
    }
}
