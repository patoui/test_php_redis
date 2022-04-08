<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Events;

use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\MessageRepository\IlluminateMessageRepository\IlluminateUuidV4MessageRepository;
use Illuminate\Database\MySqlConnection;

final class MessageRepository extends IlluminateUuidV4MessageRepository
{
    public static function make(): self
    {
        return new self(
            connection: new MySqlConnection(db(), 'app_db'),
            tableName: 'events',
            serializer: new ConstructingMessageSerializer(),
        );
    }
}
