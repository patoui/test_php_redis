<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Messages;

final class NotificationHandler extends MessageHandler
{
    public function handle(): void
    {
        // TODO: consider parsing messages into domain objects
        if ($this->message->getType() === 'deposit_processed') {
            $this->handleDepositProcessed();
        } elseif ($this->message->getType() === 'withdrawal_processed') {
            $this->handleWithdrawalProcessed();
        }
    }

    private function handleDepositProcessed(): void
    {
        echo sprintf(
<<<NOTIFICATION

****************** NOTIFICATION ******************

ACCOUNT %s
DEPOSITED %s$

****************** NOTIFICATION ******************

NOTIFICATION,

                 $this->message->getAggregateRootId(),
                 $this->message->getData()['amount']
             ) . PHP_EOL;
    }

    private function handleWithdrawalProcessed(): void
    {
        echo sprintf(
<<<NOTIFICATION

****************** NOTIFICATION ******************

ACCOUNT %s
WITHDRAWAL %s$ 

****************** NOTIFICATION ******************

NOTIFICATION,
                 $this->message->getAggregateRootId(),
                 $this->message->getData()['amount']
             ) . PHP_EOL;
    }
}
