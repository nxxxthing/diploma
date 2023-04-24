<?php

declare(strict_types=1);

namespace App\Api\v1\DTO;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public ?string $email,
        public ?string $name = null,
        public ?string $password = null,
    ) {
    }
}
