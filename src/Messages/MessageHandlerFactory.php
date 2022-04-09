<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Messages;

final class MessageHandlerFactory
{
    /**
     * @param Message $message
     * @return MessageHandler[]
     */
    public static function make(Message $message): array
    {
        if (
            $message->getType() === 'deposit_processed'
            || $message->getType() === 'withdrawal_processed'
        ) {
            return [new NotificationHandler($message)];
        }

        // TODO: Add logging for unhandled messages?

        return [];
    }
}
