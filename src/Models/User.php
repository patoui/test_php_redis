<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Models;

final class User
{
    public int    $id;
    public string $uuid;
    public string $name;
    public string $email;
    public string $created_at;
}
