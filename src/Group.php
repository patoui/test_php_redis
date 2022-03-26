<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis;

final class Group
{
    public function __construct(
        public Stream $stream,
        public string $name
    ) {
        $group_names = array_column($this->stream->groups(), 'name');

        if (!in_array($name, $group_names, true)) {
            $stream->createGroup($this->name);
        }
    }

    /**
     * Acknowledge message ids for the current consumer group
     * @param string ...$message_ids
     * @return int
     */
    public function acknowledge(string ...$message_ids): int
    {
        return $this->stream->redis->xAck(
            $this->stream->name,
            $this->name,
            $message_ids
        );
    }
}
