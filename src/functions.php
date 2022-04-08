<?php

declare(strict_types=1);

use Patoui\TestPhpRedis\Models\User;
use Ramsey\Uuid\Uuid;

if (!function_exists('redis')) {
    function redis(): Redis
    {
        static $redis = null;

        if ($redis) {
            return $redis;
        }

        $redis = new Redis();
        $redis->connect('redis');
        return $redis;
    }
}

if (!function_exists('db')) {
    function db(): PDO
    {
        try {
            return new PDO(
                'mysql:host=mysql;port=3306;dbname=db;charset=utf8mb4',
                'admin',
                'strong-password',
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (Throwable $throwable) {
            die($throwable->getMessage());
        }
    }
}
