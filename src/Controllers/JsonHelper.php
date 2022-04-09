<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Controllers;

trait JsonHelper
{
    protected static function json(array $response, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($response, JSON_PRETTY_PRINT);
        die();
    }
}
