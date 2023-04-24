<?php

declare(strict_types=1);

namespace App\Api\v1\Enums;

use ArchTech\Enums\InvokableCases;

enum UserRoles : string
{
    use InvokableCases;

    case ADMIN = 'admin';

    public static function getAdmins(): array
    {
        return [
            self::ADMIN,
        ];
    }
}
