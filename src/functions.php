<?php

declare(strict_types=1);

if (!function_exists('redis')) {
    function redis(): Redis {
        static $redis = null;

        if ($redis) {
            return $redis;
        }

        $redis = new Redis();
        $redis->connect('redis');
        return $redis;
    }
}