<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\AggregateRoots;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Patoui\TestPhpRedis\AggregateRootIds\AccountId;
use Patoui\TestPhpRedis\AggregateRoots\Exceptions\InsufficientFundsException;
use Patoui\TestPhpRedis\DataTransferObjects\NewAccount;
use Patoui\TestPhpRedis\DataTransferObjects\NewDeposit;
use Patoui\TestPhpRedis\DataTransferObjects\NewWithdrawal;
use Patoui\TestPhpRedis\Events\AccountInitiated;
use Patoui\TestPhpRedis\Events\DepositProcessed;
use Patoui\TestPhpRedis\Events\WithdrawalProcessed;

class Account implements AggregateRoot
{
    use AggregateRootBehaviour;

    private int $balance = 0;
    private int $created_at;
    private int $last_updated_at;

    public static function initiate(AccountId $id): Account
    {
        $instance = new static($id);
        $instance->recordThat(new AccountInitiated());
        return $instance;
    }

    public function applyAccountInitiated(AccountInitiated $account_initiated): void
    {
        $this->created_at      = $account_initiated->timestamp;
        $this->last_updated_at = $account_initiated->timestamp;
    }

    public function deposit(NewDeposit $new_deposit): void
    {
        $this->recordThat(new DepositProcessed($new_deposit));
    }

    public function applyDepositProcessed(DepositProcessed $deposited): void
    {
        $this->balance         += $deposited->new_deposit->amount;
        $this->last_updated_at = $deposited->new_deposit->timestamp;
    }

    public function withdraw(NewWithdrawal $new_withdrawal): void
    {
        if ($this->balance < $new_withdrawal->amount) {
            throw InsufficientFundsException::make($new_withdrawal->amount);
        }

        $this->recordThat(new WithdrawalProcessed($new_withdrawal));
    }

    public function applyWithdrawalProcessed(WithdrawalProcessed $withdrawal): void
    {
        $this->balance         -= $withdrawal->new_withdrawal->amount;
        $this->last_updated_at = $withdrawal->new_withdrawal->timestamp;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }
}
