<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Controllers;

trait JsonHelper
{
    protected static function json(array $response): void
    {
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
        die();
    }
}
