<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Controllers;

use DateTimeImmutable;
use Patoui\TestPhpRedis\Consumer;
use Patoui\TestPhpRedis\Group;
use Patoui\TestPhpRedis\Message;
use Patoui\TestPhpRedis\Stream;

final class Controller
{
    use JsonHelper;

    private Stream   $stream;
    private Group    $group;
    private Consumer $consumer;

    public function __construct()
    {
        $this->stream   = new Stream(redis(), 'mystream');
        $this->group    = new Group($this->stream, 'mygroup');
        $this->consumer = new Consumer('myconsumer');
    }

    public function read_group(): void
    {
        $acknowledged = 0;
        $messages     = $this->consumer->readGroupMessages($this->group);
        foreach ($messages as $id => $values) {
            // process messages/values, then acknowledge completion
            $acknowledged += $this->group->acknowledge($id);
        }
        self::json([$acknowledged, $messages]);
    }

    public function range_datetime(): void
    {
        self::json([
            $this->stream->getMessagesInDateTimeRange(
                new DateTimeImmutable($_GET['from'] . ' 00:00:00'),
                new DateTimeImmutable($_GET['to'] . ' 23:59:59'),
                10
            )
        ]);
    }

    public function group_create(): void
    {
        self::json([$this->stream->createGroup($this->group->name)]);
    }

    public function ack(): void
    {
        self::json([
            $this->group->acknowledge(...explode('|', $_GET['message_ids'])),
        ]);
    }

    public function add(): void
    {
        $c = new class extends Message {
            public function __construct(public string $id, public string $email)
            {}
            public function getId(): int|string
            {
                return $this->id;
            }
        };
        self::json([
            $this->stream->addMessage(new $c(
                uniqid('tpr_', true),
                'johndoe@email.com'
            ))
        ]);
    }

    public function del_consumer(): void
    {
        self::json([
            $this->group->deleteConsumer($this->consumer->name)
        ]);
    }

    public function len(): void
    {
        self::json([$this->stream->length()]);
    }

    public function info(): void
    {
        self::json([
            'consumers' => $this->stream->consumers($this->group->name),
            'groups'    => $this->stream->groups(),
            'stream'    => $this->stream->info(),
        ]);
    }
}