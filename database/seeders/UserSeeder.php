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
            $admin_exists = User::where('email', 'admin@admin.com')
                ->orWhereHas(
                    'role',
                    function ($q) {
                        $q->where('slug', UserRoles::ADMIN->value);
                    }
                )
                ->exists();

            if (!$admin_exists) {
                User::create([
                    'email' => 'admin@admin.com',
                    'first_name' => 'Admin',
                    'password' => bcrypt('admin'),
                    'role_id' => Role::whereSlug(UserRoles::ADMIN->value)->first()?->id,
                ]);
            }
        } catch (Exception $e) {
            $this->command->error($e->getMessage());
            \Log::info(['user seeder error' => $e->getMessage()]);
        }
    }
}
