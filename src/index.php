<?php

declare(strict_types=1);

use Patoui\TestPhpRedis\Controllers\Controller;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$action = parse_url(ltrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH);

if (!method_exists(Controller::class, $action)) {
    die('Nada');
}

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

(new Controller())->$action();
