<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Api\v1\DTO\UserData;
use App\Api\v1\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Exception;

class UserSeeder extends BaseSeeder
{
    public function run()
    {
        try {
            $admin_exists = User::where('email', 'admin@admin.com')->exists();

            if (!$admin_exists) {
                (new UserService())
                    ->register(new UserData(
                        email:'admin@admin.com',
                        name: 'Admin',
                        password: 'admin'
                    ))
                    ->attachRole(
                        Role::whereSlug(UserRoles::ADMIN->value)->first()
                    );
            }
        } catch (Exception $e) {
            $this->command->error($e->getMessage());
            \Log::info(['user seeder error' => $e->getMessage()]);
        }
    }
}
