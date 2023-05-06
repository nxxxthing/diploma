<?php

declare(strict_types=1);

namespace App\Api\v1\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;

enum UserRoles : string
{
    use InvokableCases;
    use Options;

    case ADMIN = 'admin';
    case STUDENT = 'student';
    case TEACHER = 'teacher';

    public static function getAdmins(): array
    {
        return [
            self::ADMIN,
        ];
    }
}
