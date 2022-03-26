<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis;

use Redis;

final class Stream
{
    public function __construct(
        public Redis $redis,
        public string $name
    ) {}

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
        return $this->redis->xInfo('CONSUMERS', $this->name, $group);
    }

    /**
     * Get a list of groups for the current stream
     * @return array
     */
    public function groups(): array
    {
        return $this->redis->xInfo('GROUPS', $this->name);
    }

    /**
     * Get information about the current stream
     * @return array
     */
    public function info(): array
    {
        return $this->redis->xInfo('STREAM', $this->name);
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
     * @return string
     */
    public function addMessage(Message $message): string
    {
        return $this->redis->xAdd(
            $this->name,
            '*',
            [igbinary_serialize($message)]
        );
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
}
