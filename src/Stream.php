<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis;

use DateTimeImmutable;
use Redis;

final class Stream
{
    public function __construct(
        public Redis $redis,
        public string $name
    ) {
    }

    /**
     * Get the length (number of messages) of the current stream
     * @return int
     */
    public function length(): int
    {
        return $this->redis->xLen($this->name);
    }

    /**
     * Get a list of consumers for the current stream and a given group
     * @param string $group
     * @return array
     */
    public function consumers(string $group): array
    {
        return $this->redis->xInfo('CONSUMERS', $this->name, $group) ?: [];
    }

    /**
     * Get a list of groups for the current stream
     * @return array
     */
    public function groups(): array
    {
        return $this->redis->xInfo('GROUPS', $this->name) ?: [];
    }

    /**
     * Get information about the current stream
     * @return array
     */
    public function info(): array
    {
        return $this->redis->xInfo('STREAM', $this->name) ?: [];
    }

    /**
     * Create a group on the current stream
     * @param string $group_name
     * @return bool
     */
    public function createGroup(string $group_name): bool
    {
        return $this->redis->xGroup('CREATE', $this->name, $group_name, '0');
    }

    /**
     * Add a single message to the stream group
     * @param Message $message
     * @return string|null
     */
    public function addMessage(Message $message): ?string
    {
        return $this->redis->xAdd(
            $this->name,
            '*',
            [igbinary_serialize($message)]
        ) ?: null;
    }

    /**
     * Add multiple messages to the stream group
     * @param Message ...$messages
     * @return void
     */
    public function addMessages(Message ...$messages): void
    {
        $this->redis->xAdd(
            $this->name,
            '*',
            array_map('igbinary_serialize', $messages)
        );
    }

    /**
     * Get messages in a given datetime range
     * @param DateTimeImmutable $from
     * @param DateTimeImmutable $to
     * @param int|null          $count Number of messages to extract `null` will
     *                                 get all messages in the range
     * @return array
     */
    public function getMessagesInDateTimeRange(
        DateTimeImmutable $from,
        DateTimeImmutable $to,
        ?int $count
    ): array {
        return $this->getMessagesInRange(
            (string) ($from->getTimestamp() * 1000),
            (string) ($to->getTimestamp() * 1000),
            $count
        ) ?: [];
    }

    /**
     * Get messages in a given range
     * @param string   $start Example in milliseconds (e.g. 1648267200000), or
     *                         special identifier `-`, see https://redis.io/commands/xrange/
     *                         for possible values
     * @param string   $end   Example in milliseconds (e.g. 1648353599000), or
     *                         special identifier `+`, see https://redis.io/commands/xrange/
     *                         for possible values
     * @param int|null $count Number of messages to extract `null` will
     *                         get all messages in the range
     * @return array
     */
    public function getMessagesInRange(
        string $start,
        string $end,
        ?int $count
    ): array {
        return $this->redis->xRange($this->name, $start, $end, $count) ?: [];
    }
}
