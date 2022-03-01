<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Controllers;

use Redis;

final class Controller
{
    public static function claim(): void
    {
        self::json([
            self::redis()->xClaim(
                'mystream', 'mygroup', 'myconsumer', 0, explode('|', $_GET['message_ids']),
                [
                    'IDLE'       => time() * 1000,
                    'RETRYCOUNT' => 5,
//                    'FORCE',
                    'JUSTID',
                ]
            ),
        ]);
    }

    public static function read_group(): void
    {
        // Consumers are created on the fly
        self::json([
            self::redis()->xReadGroup('mygroup', 'myconsumer', ['mystream' => '>'], 1, 1000),
        ]);
    }

    public static function group_create(): void
    {
        self::json([
            self::redis()->xGroup('CREATE', 'mystream', 'mygroup', $_GET['message_id']),
        ]);
    }

    public static function ack(): void
    {
        self::json([
            self::redis()->xAck('mystream', 'mygroup', explode('|', $_GET['message_ids'])),
        ]);
    }

    public static function add(): void
    {
        self::json([
            self::redis()->xAdd('mystream', '*', ['field' => 'value']),
        ]);
    }

    public static function len(): void
    {
        self::json([
            self::redis()->xLen('mystream'),
        ]);
    }

    public static function info(): void
    {
        self::json([
            'consumers' => self::redis()->xInfo('CONSUMERS', $_GET['stream'], $_GET['group']),
            'groups'    => self::redis()->xInfo('GROUPS', $_GET['stream']),
            'stream'    => self::redis()->xInfo('STREAM', $_GET['stream']),
        ]);
    }

    private static function redis(): Redis
    {
        static $redis = null;

        if ($redis) {
            return $redis;
        }

        $redis = new Redis();
        $redis->connect('redis');
        return $redis;
    }

    private static function json(array $response): void
    {
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
        die();
    }
}