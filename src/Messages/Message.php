<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Messages;

use EventSauce\EventSourcing\Message as EventMessage;

class Message
{
    public function __construct(
        private string $aggregate_root_id,
        private string $type,
        private array $data
    ) {}

    public static function make(EventMessage $message): self
    {
        return new self(
            $message->aggregateRootId()->toString(),
            self::getEventType($message),
            self::getEventData($message->event())
        );
    }

    public function getAggregateRootId(): string
    {
        return $this->aggregate_root_id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    private static function getEventData(object $event): array
    {
        $data = method_exists($event, 'toPayload')
            ? $event->toPayload()
            : (array) $event;

        return array_filter($data, static function ($item) {
            // only accept stdClass objects
            if (is_object($item)) {
                return get_class($item) !== 'stdClass';
            }

            return true;
        });
    }

    private static function getEventType(EventMessage $message): string
    {
        $type_parts = explode('.', $message->header('__event_type'));
        return end($type_parts);
    }
}