<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis;

final class Consumer
{
    public function __construct(public string $name)
    {
    }

    /**
     * Read the given groups messages as the current consumer
     * @param Group $group
     * @param int   $count number of messages to retrieve
     * @param int   $wait  wait up to X milliseconds until a message arrives
     * @return array
     */
    public function readGroupMessages(
        Group $group,
        int $count = 10,
        int $wait = 100
    ): array {
        // triggering xReadGroup will create the consumer if it does not exist
        $raw_group_messages = $group->stream->redis->xReadGroup(
                $group->name,
                $this->name,
                // '>' get messages not yet consumed by the group within the given stream
                [$group->stream->name => '>'],
                $count,
                $wait
            )[$group->stream->name] ?? [];

        $parsed_messages = [];

        foreach ($raw_group_messages as $message_id => $messages) {
            if (!isset($parsed_messages[$message_id])) {
                $parsed_messages[$message_id] = [];
            }

            foreach ($messages as $message) {
                $parsed_messages[$message_id][] = igbinary_unserialize($message);
            }
        }

        return $parsed_messages;
    }

    /**
     * Claim a group of message ids for the current consumer
     * @param Group  $group
     * @param string ...$message_ids
     * @return array
     */
    public function claim(Group $group, string ...$message_ids): array
    {
        return $group->stream->redis->xClaim(
            $group->stream->name, $group->name, $this->name, 0, $message_ids,
            [
                'IDLE'       => time() * 1000,
                'RETRYCOUNT' => 5,
                'JUSTID',
            ]
        );
    }
}
