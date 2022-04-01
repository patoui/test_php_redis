<?php

declare(strict_types=1);

use Patoui\TestPhpRedis\Controllers\Controller;

require_once __DIR__ . '/bootstrap.php';

$action = parse_url(ltrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH);

if (!method_exists(Controller::class, $action)) {
    die('Nada');
}

(new Controller())->$action();
