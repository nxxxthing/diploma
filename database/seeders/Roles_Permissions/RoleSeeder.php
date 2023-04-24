<?php

declare(strict_types=1);

namespace Database\Seeders\Roles_Permissions;

use App\Api\v1\Enums\UserRoles;
use App\Models\Role;
use Database\Seeders\BaseSeeder;

class RoleSeeder extends BaseSeeder
{
    public function run()
    {
        $data = [
            UserRoles::ADMIN() => [
                'en' => [
                    'title' => 'Admin'
                ],
                'ua' => [
                    'title' => 'Адміністратор',
                ]
            ]
        ];

        foreach ($data as $item => $value) {
            Role::firstOrCreate(['slug' => $item], $value);
        }
    }
}
