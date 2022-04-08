<?php

declare(strict_types=1);

use Patoui\TestPhpRedis\Controllers\Controller;
use Patoui\TestPhpRedis\Controllers\NewController;

require_once __DIR__ . '/bootstrap.php';

$path = parse_url(ltrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH);

$uri_parts = explode('/', $path);
$action = end($uri_parts);

if (str_starts_with($path, 'new') && method_exists(NewController::class, $action)) {
    (new NewController())->$action();
    return;
} elseif (!method_exists(Controller::class, $action)) {
    (new Controller())->$action();
    return;
}

die('Nada');
